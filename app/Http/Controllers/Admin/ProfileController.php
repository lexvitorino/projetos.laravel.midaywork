<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        dd($request);
        return view('admin.profile', [
            'title' => (object) ['icon' => 'icofont-ui-calendar', 'title' => __('custom.titles.profile'), 'subtitle' => __('custom.titles.manage-your-work-team'),],
            'subtitle' => 'subtitle',
            'icon' => 'icon',
            'workedInterval' => null,
            'exitTime' => null,
            'activeClock' => 'workedInterval',
            'activeClock' => 'exitTime',
        ]);
    }
}
