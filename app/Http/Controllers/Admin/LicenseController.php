<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Constants;
use App\Helpers\DateUtils;
use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\User;
use App\Models\WorkingHour;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
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
        $licenses = License::where("subscriber_id", Auth::user()->subscriber_id)
            ->paginate(5);

        return view('admin.licenses.index', [
            'title' => (object) ['icon' => 'icofont-history', 'title' => 'Licenças', 'subtitle' => 'Cadastre as licenças e férias dos colaboradores',],
            'licenses' => $licenses,
            'user' => Auth::user()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $users = User::where('subscriber_id', $user->subscriber_id)->get();

        $selectedUserId = $user->id;
        if ($user->is_admin) {
            $users = User::get();
            $selectedUserId = $request->input('user') ? intval($request->input('user')) : $user->id;
        }

        return view('admin.licenses.create', [
            'title' => (object) ['icon' => 'icofont-history', 'title' => 'Licenças', 'subtitle' => 'Cadastre as licenças e férias dos colaboradores',],
            'user' => $user,
            'users' => $users,
            'selectedUserId' => $selectedUserId
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
        $data = $request->only([
            'user_id',
            'license_type',
            'start_bonus_date',
            'end_bonus_date',
            'start_discount_date',
            'end_discount_date'
        ]);

        $user = Auth::user();

        if (!$user->is_admin) {
            $data['user_id'] = $user->id;
        }

        $validator = Validator::make($data, [
            'user_id' => ['required'],
            'license_type' => ['required']
        ], $this->message());

        if (!$validator->fails()) {
            if (!empty($data['start_bonus_date']) && empty($data['end_bonus_date'])) {
                $validator->errors()->add('start_bonus_date', 'Data Fim abodado pela empresa não informado.');
            }

            if (!empty($data['start_discount_date']) && empty($data['end_discount_date'])) {
                $validator->errors()->add('end_discount_date', 'Data Fim descontado em banco de horas não informado.');
            }
        }

        if ($validator && count($validator->errors()) > 0) {
            return redirect()->route('licenses.create')
                ->withErrors($validator)
                ->withInput();
        }

        $license = new License();
        $license->subscriber_id = Auth::user()->subscriber_id;
        $license->user_id = $data['user_id'];
        $license->license_type = $data['license_type'];

        $license->start_bonus_date = $data['start_bonus_date'];
        $license->end_bonus_date = $data['end_bonus_date'];

        if (!empty($license->end_bonus_date)) {
            $license->bonus_days = DateUtils::getWorkingDays($license->start_bonus_date, $license->end_bonus_date);
        }

        $license->start_discount_date = $data['start_discount_date'];
        $license->end_discount_date = $data['end_discount_date'];

        if (!empty($license->end_discount_date)) {
            $license->discount_days = DateUtils::getWorkingDays($license->start_discount_date, $license->end_discount_date);
        }

        $license->save();

        return $this->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $users = User::where('subscriber_id', $user->subscriber_id)->get();

        $registry = License::where('subscriber_id', $user->subscriber_id)
            ->where("id", $id)
            ->first();

        return view('admin.licenses.edit', [
            'title' => (object) ['icon' => 'icofont-history', 'title' => 'Licenças', 'subtitle' => 'Cadastre as licenças e férias dos colaboradores',],
            'user' => $user,
            'users' => $users,
            'registry' => $registry
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $action = $request->input('action', 'editable');
        switch ($action) {
            case 'approved':
                return $this->approved($request, $id);
                break;
            case 'denied':
                return $this->denied($request, $id);
                break;
            case 'processed':
                return $this->processed($request, $id);
                break;
            default:
                return $this->save($request, $id);
                break;
        }
    }

    private function save(Request $request, $id)
    {
        $license = License::where("subscriber_id", Auth::user()->subscriber_id)->where('id', $id)->first();
        if (!$license) {
            $this->back();
        }

        $data = $request->only([
            'user_id',
            'license_type',
            'start_bonus_date',
            'end_bonus_date',
            'start_discount_date',
            'end_discount_date'
        ]);

        $validator = Validator::make($data, [
            'user_id' => ['required'],
            'license_type' => ['required']
        ], $this->message());

        if (!$validator->fails()) {
            if (!empty($data['start_bonus_date']) && empty($data['end_bonus_date'])) {
                $validator->errors()->add('start_bonus_date', 'Data Fim abodado pela empresa não informado.');
            }

            if (!empty($data['start_discount_date']) && empty($data['end_discount_date'])) {
                $validator->errors()->add('end_discount_date', 'Data Fim descontado em banco de horas não informado.');
            }
        }

        if ($validator && count($validator->errors()) > 0) {
            return redirect()->route('licenses.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $license->user_id = $data['user_id'];
        $license->license_type = $data['license_type'];

        $license->start_bonus_date = $data['start_bonus_date'];
        $license->end_bonus_date = $data['end_bonus_date'];

        if (!empty($license->end_bonus_date)) {
            $license->bonus_days = DateUtils::getWorkingDays($license->start_bonus_date, $license->end_bonus_date);
        }

        $license->start_discount_date = $data['start_discount_date'];
        $license->end_discount_date = $data['end_discount_date'];

        if (!empty($license->end_discount_date)) {
            $license->discount_days = DateUtils::getWorkingDays($license->start_discount_date, $license->end_discount_date);
        }

        $license->save();

        return $this->back();
    }

    private function approved(Request $request, $id)
    {
        $license = License::where("subscriber_id", Auth::user()->subscriber_id)->where('id', $id)->first();
        if (!$license) {
            return $this->back();
        }

        $license->status = "approved";
        $license->save();

        return $this->back();
    }

    private function denied(Request $request, $id)
    {
        $license = License::where("subscriber_id", Auth::user()->subscriber_id)->where('id', $id)->first();
        if (!$license) {
            return $this->back();
        }

        $license->status = "denied";
        $license->save();

        return $this->back();
    }

    private function processed(Request $request, $id)
    {
        $license = License::where("subscriber_id", Auth::user()->subscriber_id)->where('id', $id)->first();
        if (!$license) {
            return $this->back();
        }

        if (!empty($license->start_bonus_date)) {

            $begin = new DateTime($license->start_bonus_date);
            $end = (new DateTime($license->end_bonus_date))->modify('+1 day');;

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                $workDate = $dt->format('Y-m-d');

                if (!DateUtils::isWeekend($dt)) {
                    $workingHours = WorkingHour::where("subscriber_id", Auth::user()->subscriber_id)
                        ->where('user_id', $license->user_id)
                        ->where('work_date', $workDate)
                        ->first();

                    if (!$workingHours) {
                        $workingHours = new WorkingHour();
                        $workingHours->subscriber_id = Auth::user()->subscriber_id;
                        $workingHours->user_id = $license->user_id;
                        $workingHours->work_date = $workDate;
                        $workingHours->status = 'bonus-vocation';
                        $workingHours->save();
                    }
                }
            }
        }

        if (!empty($license->start_discount_date)) {

            $begin = new DateTime($license->start_discount_date);
            $end = (new DateTime($license->end_discount_date))->modify('+1 day');;

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                $workDate = $dt->format('Y-m-d');

                if (!DateUtils::isWeekend($dt)) {
                    $workingHours = WorkingHour::where("subscriber_id", Auth::user()->subscriber_id)
                        ->where('user_id', $license->user_id)
                        ->where('work_date', $workDate)
                        ->first();

                    if (!$workingHours) {
                        $workingHours = new WorkingHour();
                        $workingHours->subscriber_id = Auth::user()->subscriber_id;
                        $workingHours->user_id = $license->user_id;
                        $workingHours->work_date = $workDate;
                        $workingHours->worked_time = Constants::DAILY_TIME * (-1);
                        $workingHours->status = 'discounted-vocation';
                        $workingHours->save();
                    }
                }
            }
        }

        $license->status = "processed";
        $license->save();

        return $this->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $license = License::where("subscriber_id", Auth::user()->subscriber_id)->where('id', $id)->first();
        if (!$license) {
            return $this->back();
        }
        $license->delete();
        return $this->back();
    }

    private function back()
    {
        return redirect()->route('licenses.index');
    }

    private function message()
    {
        return array(
            'user_id.required' => 'Campo usuário é requerido.',
            'license_type.required' => 'Campo tipo é requerido.',
            'start_bonus_date.required' => 'Campo Data Inicio abodado pela empresa não informado..',
            'end_bonus_date.required' => 'Campo Data Fim abodado pela empresa não informado..',
            'start_discount_date.required' => 'Campo Data Inicio descontado em banco de horas não informado.',
            'end_discount_date.required' => 'Campo Data Fim descontado em banco de horas não informado.',
        );
    }
}
