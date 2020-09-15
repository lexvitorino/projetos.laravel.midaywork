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
use Illuminate\Support\Facades\Config;

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

        $userSel = User::find($selectedUserId);

        $selectedPeriod = $request->input('period') ? $request->input('period') : $currentDate->format('Y-m');
        $periods = $this->getPeriods();

        $registries = WorkingHour::getMonthlyReport($selectedUserId, $selectedPeriod);

        $report = [];
        $workDay = 0;
        $sumOfWorkedTime = 0;
        $lastDay = DateUtils::getLastDayOfMonth($selectedPeriod)->format('d');

        for ($day = 1; $day <= $lastDay; $day++) {
            $date = $selectedPeriod . '-' . sprintf('%02d', $day);

            try {
                $registry = $registries[$date];
                if (DateUtils::isPastWorkday($date)) {
                    $workDay++;
                }
                $sumOfWorkedTime += $registry->worked_time;
                array_push($report, $registry);
            } catch (\Throwable $th) {
                array_push($report, new WorkingHour([
                    'work_date' => $date,
                    'worked_time' => 0
                ]));
            }
        }

        $totalBalanceTime = (WorkingHour::where('user_id', $userSel->id)
            ->where('work_date', '<', ($selectedPeriod . '-' . sprintf('%02d', 1)))
            ->sum('worked_time')) + DateUtils::getSecondsToTimeString($userSel->time_balance);

        $totalBalance = DateUtils::getTimeStringFromSeconds($totalBalanceTime);
        if ($totalBalanceTime > 0) {
            $signBalance = '+';
        } else if ($totalBalanceTime < 0) {
            $signBalance = '-';
        } else {
            $signBalance = '';
        }

        $expectedTime = $workDay * Constants::DAILY_TIME;
        $balance = DateUtils::getTimeStringFromSeconds(abs($sumOfWorkedTime - $expectedTime) + $totalBalanceTime);
        $sign = ($sumOfWorkedTime >= $expectedTime) ? '+' : '-';

        return view('admin.monthlyReport', [
            'title' => (object) ['icon' => 'icofont-ui-calendar', 'title' => 'Relatório Mensal', 'subtitle' => 'Acompanhe seu saldo de horas',],
            'users' => $users,
            'user' => $user,
            'report' => $report,
            'sumOfWorkedTime' => DateUtils::getTimeStringFromSeconds($sumOfWorkedTime),
            'balance' => "{$sign}{$balance}",
            'totalBalance' => (object) ['balance' => "{$signBalance}{$totalBalance}", 'class' => ($totalBalance < 0 ? 'danger' : 'success')],
            'selectedPeriod' => $selectedPeriod,
            'periods' => $periods,
            'selectedUserId' => $selectedUserId
        ]);
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
