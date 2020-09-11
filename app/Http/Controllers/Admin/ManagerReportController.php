<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DateUtils;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkingHour;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $users = User::where('subscriber_id', $user->subscriber_id)->get();

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

        return view('admin.managerReport', [
            'title' => (object) ['icon' => 'icofont-chart-histogram', 'title' => 'Relatório Mensal', 'subtitle' => 'Acompanhe seu saldo de horas',],
            'users' => $users,
            'user' => $user,
            'activeUsersCount' => $activeUsersCount ?? '',
            'hoursInMonth' => $hoursInMonth ?? '',
            'absentUsers' => $absentUsers ?? [],
        ]);
    }
}
