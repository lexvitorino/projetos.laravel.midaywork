<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Constants;
use App\Helpers\DateUtils;
use App\Http\Controllers\Controller;
use App\Mail\Balance;
use App\Models\User;
use App\Models\WorkingHour;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ManagerReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentDate = new DateTime();

        $user = Auth::user();

        $activeUsersCount = User::where('subscriber_id', Auth::user()->subscriber_id)
            ->whereNull('end_date')
            ->count();

        $absentUsers = User::select('name')
            ->where('subscriber_id', $user->subscriber_id)
            ->whereNull('end_date')
            ->whereNotIn('id', function ($query) {
                $currentDate = new DateTime();
                $query->select('user_id')
                    ->from(with(new WorkingHour())->getTable())
                    ->whereNotNull('time1')
                    ->where('work_date', $currentDate->format('Y-m-d'));
            })
            ->get();

        $startDate = DateUtils::getFirstDayOfMonth($currentDate)->format('Y-m-d');
        $endDate = DateUtils::getLastDayOfMonth($currentDate)->format('Y-m-d');

        $secondsInMonth = WorkingHour::where('user_id', $user->id)
            ->where('work_date', '>=', $startDate)
            ->where('work_date', '<=', $endDate)
            ->sum('worked_time');

        $hoursInMonth = explode(':', DateUtils::getTimeStringFromSeconds($secondsInMonth))[0];

        $balances = $this->getBalances();

        return view('admin.managerReport', [
            'title' => (object) ['icon' => 'icofont-chart-histogram', 'title' => 'Relatório Gerêncial', 'subtitle' => 'Acompanhe seu saldo de horas',],
            'user' => $user,
            'activeUsersCount' => $activeUsersCount ?? '',
            'hoursInMonth' => $hoursInMonth ?? '',
            'absentUsers' => $absentUsers ?? [],
            'balances' => $balances ?? []
        ]);
    }

    private function getBalances()
    {
        $currentDate = new DateTime();
        $balances = [];

        $user = Auth::user();
        $users = User::where('subscriber_id', $user->subscriber_id)->get();

        foreach ($users as $u) {
            $timeBalance = DateUtils::getSecondsToTimeString($u->time_balance);

            if ($u->signal === '-') {
                $timeBalance = $timeBalance * (-1);
            }

            $sumOfWorkedTime = (WorkingHour::where('user_id', $u->id)
                ->where('work_date', '<', $currentDate)
                ->where('time4', '!=', "")
                ->sum('worked_time'));

            $workDay = (WorkingHour::where('user_id', $u->id)
                ->where('work_date', '<', $currentDate)
                ->where('time4', '!=', "")
                ->where('status', 'normal')
                ->count());

            $discounted = (WorkingHour::where('user_id', $u->id)
                ->where('work_date', '<', $currentDate)
                ->where('status', 'discounted-vocation')
                ->count());

            $bonusDay = (WorkingHour::where('user_id', $u->id)
                ->where('work_date', '<', $currentDate)
                ->where('time4', '!=', "")
                ->where('status', 'bonus-vocation')
                ->count());

            $expectedTime = ($workDay + $discounted - $bonusDay) * Constants::DAILY_TIME;
            $balance = DateUtils::getTimeStringFromSeconds($sumOfWorkedTime - $expectedTime + $timeBalance);

            $balances[] = (object) array(
                "name" => $u->name,
                "balance" => $balance
            );
        }

        session('balances', $balances);

        return $balances;
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
            case 'sendMail':
                return $this->sendMail();
                break;
            default:
                return $this->save($request);
                break;
        }
    }

    private function sendMail()
    {
        $user = Auth::user();

        $users = User::where('subscriber_id', $user->subscriber_id)
            ->where('is_admin', 1)
            ->get();

        $toList = [];
        foreach ($users as $u) {
            $toList[] = (object) [
                'email' => $u->email,
                'name' => $u->name,
            ];
        }

        Mail::send(new Balance($toList, session('balances')));
        return redirect()->route("managerReport");
    }
}
