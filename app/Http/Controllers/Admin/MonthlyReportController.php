<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Constants;
use App\Helpers\DateUtils;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkingHour;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MonthlyReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentDate = new DateTime();

        $user = Auth::user();
        $users = User::where('subscriber_id', $user->subscriber_id)->get();

        $selectedUserId = $user->id;
        if ($user->is_admin) {
            $users = User::get();
            $selectedUserId = $request->input('user') ? intval($request->input('user')) : $user->id;
        }

        $selectedUser = User::find($selectedUserId);

        $selectedPeriod = $request->input('period') ? $request->input('period') : $currentDate->format('Y-m');
        $periods = $this->getPeriods();

        $registries = WorkingHour::getMonthlyReport($selectedUserId, $selectedPeriod);

        $report = [];
        $workDay = 0;
        $bonusDay = 0;
        $sumOfWorkedTime = 0;
        $lastDay = DateUtils::getLastDayOfMonth($selectedPeriod)->format('d');

        Session::flash('selectedUserId', $selectedUser->id);
        Session::flash('selectedPeriod', $selectedPeriod);

        for ($day = 1; $day <= $lastDay; $day++) {
            $date = $selectedPeriod . '-' . sprintf('%02d', $day);

            try {
                $registry = $registries[$date];
                if (DateUtils::isPastWorkday($date)) {
                    $workDay++;
                    if ($registry->status === 'bonus-vocation') {
                        $bonusDay++;
                    }
                }
                $sumOfWorkedTime += $registry->worked_time ?? 0;
                array_push($report, $registry);
            } catch (\Throwable $th) {
                array_push($report, new WorkingHour([
                    'work_date' => $date,
                    'worked_time' => 0
                ]));
            }
        }

        $totalBalanceTime = (WorkingHour::where('user_id', $selectedUser->id)
            ->where('work_date', '<', ($selectedPeriod . '-' . sprintf('%02d', 1)))
            ->sum('worked_time')) + DateUtils::getSecondsToTimeString($selectedUser->time_balance);

        if ($selectedUser->signal === '-') {
            $totalBalanceTime = $totalBalanceTime * (-1);
        }

        $totalBalance = DateUtils::getTimeStringFromSeconds($totalBalanceTime);

        $expectedTime = ($workDay - $bonusDay) * Constants::DAILY_TIME;
        $balance = DateUtils::getTimeStringFromSeconds($sumOfWorkedTime - $expectedTime + $totalBalanceTime);

        return view('admin.monthlyReport', [
            'title' => (object) ['icon' => 'icofont-ui-calendar', 'title' => __('custom.titles.managerReport'), 'subtitle' => __('custom.titles.manage-your-work-team'),],
            'users' => $users,
            'user' => $user,
            'report' => $report,
            'sumOfWorkedTime' => DateUtils::getTimeStringFromSeconds($sumOfWorkedTime),
            'balance' => $balance,
            'totalBalance' => (object) ['balance' => "{$totalBalance}", 'class' => (($selectedUser->signal === '-') ? 'danger' : 'success')],
            'selectedPeriod' => $selectedPeriod,
            'periods' => $periods,
            'selectedUserId' => $selectedUser->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function execute(Request $request)
    {
        $action = $request->input('action');
        switch ($action) {
            case 'calcBalance':
                return $this->calcBalance($request);
                break;
            case 'calcBalanceAll':
                return $this->calcBalanceAll($request);
                break;
            default:
                break;
        }
    }

    private function calcBalance(Request $request)
    {
        $id = intval($request->input('id'));

        $workingHour = WorkingHour::where('subscriber_id', Auth::user()->subscriber_id)
            ->where('id', $id)
            ->first();

        if ($workingHour) {
            $workingHour->worked_time = DateUtils::getSecondsFromDateInterval(WorkingHour::getWorkedInterval($workingHour));
            $workingHour->save();
        }

        return redirect()->route("monthlyReport");
    }

    private function calcBalanceAll(Request $request)
    {
        $selectedUserId = intval(Session::get('selectedUserId'));
        $selectedPeriod = Session::get('selectedPeriod');

        $firstDay = DateUtils::getFirstDayOfMonth($selectedPeriod)->format('Y-m-d');
        $lastDay = DateUtils::getLastDayOfMonth($selectedPeriod)->format('Y-m-d');

        $workingHours = WorkingHour::where('subscriber_id', Auth::user()->subscriber_id)
            ->where('user_id', $selectedUserId)
            ->where('status', 'normal')
            ->where('work_date', '>=', $firstDay)
            ->where('work_date', '<=', $lastDay)
            ->get();

        foreach ($workingHours as $w) {
            $workingHour = WorkingHour::find($w->id);
            $workingHour->worked_time = DateUtils::getSecondsFromDateInterval(WorkingHour::getWorkedInterval($workingHour));
            $workingHour->save();
        }

        return redirect()->route("monthlyReport");
    }

    private function getPeriods()
    {
        $periods = [];
        for ($yearDiff = 0; $yearDiff <= 2; $yearDiff++) {
            $year = date('Y') - $yearDiff;
            for ($month = 12; $month >= 1; $month--) {
                $date = new DateTime("{$year}-{$month}-1");
                $periods[$date->format('Y-m')] = strftime('%B de %Y', $date->getTimestamp());
            }
        }
        return $periods;
    }
}
