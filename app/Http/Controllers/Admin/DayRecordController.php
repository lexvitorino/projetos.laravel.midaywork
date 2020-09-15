<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DateUtils;
use App\Helpers\MessageUtils;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DayRecordController extends Controller
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
        $date = (new DateTime())->getTimestamp();
        $today = strftime('%d de %B de %Y', $date);

        $user = Auth::user();
        $workingHours = WorkingHour::loadFromUserAndDate($user->sibscriber_id, $user->id, date('Y-m-d'));

        return view("admin.dayRecord", [
            'title' => (object) ['icon' => 'icofont-check-alt', 'title' => 'Registrar Ponto', 'subtitle' => 'Mantenha seu ponto consistente',],
            'user' => Auth::user(),
            'workingHours' => $workingHours,
            'today' => $today
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $forcedTime = $request->input('forcedTime');
        $forcedDate = $request->input('forcedDate');

        $date = (new DateTime())->getTimestamp();
        $today = strftime('%d de %B de %Y', $date);

        $workDate = date('Y-m-d');

        if (!empty($forcedTime) && !empty($forcedDate)) {
            $workDate = $forcedDate;
        }

        $user = Auth::user();
        $workingHours = WorkingHour::loadFromUserAndDate($user->subscriber_id, $user->id, $workDate);

        if (!$workingHours) {
            $workingHours->user_id = Auth::id();
            $workingHours->work_date = $workDate;
            $workingHours->work_time = 0;
        }

        $currentTime = strftime('%H:%M:%S', time());

        if (!empty($forcedTime)) {
            $currentTime = $forcedTime;
            if ($forcedDate) {
                $workingHours->work_date = $forcedDate;
            }
        }

        switch (WorkingHour::getNextTime($workingHours)) {
            case 'time1':
                $workingHours->time1 = $currentTime;
                break;
            case 'time2':
                $workingHours->time2 = $currentTime;
                break;
            case 'time3':
                $workingHours->time3 = $currentTime;
                break;
            case 'time4':
                $workingHours->time4 = $currentTime;
                break;
            case 'time5':
                $workingHours->time5 = $currentTime;
                break;
            case 'time6':
                $workingHours->time6 = $currentTime;
                break;
            default:
                MessageUtils::warning('Você já fez os quatro apontamentos do dia!');
                return view("admin.dayRecord", [
                    'title' => (object) ['icon' => 'icofont-check-alt', 'title' => 'Reistrar Ponto', 'subtitle' => 'Mantenha seu ponto consistente',],
                    'user' => Auth::user(),
                    'today' => $today,
                    'workingHours' => $workingHours,
                ]);
                break;
        }

        $workingHours->worked_time = DateUtils::getSecondsFromDateInterval(WorkingHour::getWorkedInterval($workingHours));
        $workingHours->subscriber_id = $user->subscriber_id;
        $workingHours->save();

        MessageUtils::success('Ponto recebido com sucesso!');

        return redirect()->route('dayRecord');
    }
}
