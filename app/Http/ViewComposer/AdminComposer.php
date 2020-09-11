<?php

/**
 * Created by PhpStorm.
 * User: Danny
 * Date: 30/03/2017
 * Time: 3:57 PM
 */


namespace App\Http\ViewComposer;

use App\Models\WorkingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminComposer
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        if (Auth::check()) {
            $view->with('user', Auth::user());
        }

        $user = Auth::user();

        $workingHours = WorkingHour::loadFromUserAndDate($user->sibscriber_id, $user->id, date('Y-m-d'));
        if ($workingHours) {
            $workedInterval = WorkingHour::getWorkedInterval($workingHours)->format('%H:%I:%S');
            $exitTime = WorkingHour::getExitTime($workingHours)->format('H:i:s');
            $activeClock = WorkingHour::getActiveClock($workingHours);
        }

        $view->with('workedInterval', $workedInterval ?? '');
        $view->with('exitTime', $exitTime ?? '');
        $view->with('activeClock', $activeClock ?? '');
    }
}
