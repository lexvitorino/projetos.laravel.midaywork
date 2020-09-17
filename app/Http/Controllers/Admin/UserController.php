<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
        $users = User::where("subscriber_id", Auth::user()->subscriber_id)->paginate(5);
        return view('admin.users.index', [
            'title' => (object) ['icon' => 'icofont-users', 'title' => 'Usuários', 'subtitle' => 'Cadastre os colaboradores para controle de horas',],
            'users' => $users,
            'userId' => Auth::id()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create', [
            'title' => (object) ['icon' => 'icofont-users', 'title' => 'Usuários', 'subtitle' => 'Cadastre os colaboradores para controle de horas',],
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
            'name', 'email', 'password', 'password_confirmation', 'start_date', 'end_date', 'is_admin', 'time_balance', 'signal'
        ]);

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ], $this->message());

        if ($validator->fails()) {
            return redirect()->route('users.create')
                ->withErrors($validator)
                ->withInput();
        }

        $user = new User();
        $user->subscriber_id = Auth::user()->subscriber_id;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->start_date = $data['start_date'];
        $user->end_date = $data['end_date'];
        $user->time_balance = $data['time_balance'];
        $user->signal = $data['signal'];
        $user->is_admin = (($data['is_admin'] ?? 'off') == 'on') ? true : false;
        $user->save();

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
    public function edit($id)
    {
        $user = User::where("subscriber_id", Auth::user()->subscriber_id)
            ->where('id', $id)
            ->first();

        if ($user) {
            return view('admin.users.edit', [
                'title' => (object) ['icon' => 'icofont-users', 'title' => 'Usuários', 'subtitle' => 'Cadastre os colaboradores para controle de horas',],
                'user' => $user
            ]);
        }

        return $this->back();
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
        $user = User::where("subscriber_id", Auth::user()->subscriber_id)->where('id', $id)->first();
        if (!$user) {
            $this->back();
        }

        $data = $request->only([
            'name', 'email', 'password', 'password_confirmation', 'start_date', 'end_date', 'is_admin', 'time_balance', 'signal'
        ]);

        $validator = $this->editValidation($user, $data);
        if ($validator && count($validator->errors()) > 0) {
            return redirect()->route('users.edit', ['user' => $user->id])
                ->withErrors($validator)
                ->withInput();
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = empty($data['password']) ? $user->password : Hash::make($data['password']);
        $user->start_date = $data['start_date'];
        $user->end_date = $data['end_date'];
        $user->time_balance = $data['time_balance'];
        $user->signal = $data['signal'];
        $user->is_admin = (($data['is_admin'] ?? 'off') == 'on') ? true : false;
        $user->save();

        return $this->back();
    }

    private function editValidation($user, $data)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:100']
        ], $this->message());

        if (!$validator->fails()) {
            if ($data['email'] != $user->email) {
                $hasEmail = User::where("subscriber_id", Auth::user()->subscriber_id)
                    ->where('id', intval($data['id']))
                    ->where('email', $data['email'])->first();
                if (count($hasEmail) === 0) {
                    $validator->errors()->add('email', 'Email informado já existe.');
                }
            }

            if (!empty($data['password'])) {
                if (strlen($data['password']) < 4) {
                    $validator->errors()->add('password', 'Password não atende ao tamanho mínimo de 4 caracteres.');
                } else if ($data['password'] != $data['password_confirmation']) {
                    $validator->errors()->add('password', 'Campo password não compátivel.');
                } else if (empty($data['password_confirmation'])) {
                    $validator->errors()->add('password', 'É necessário repetir a senha.');
                }
            }
        }
        return $validator;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (intval(Auth::id()) != intval($id)) {
            $user = User::where("subscriber_id", Auth::user()->subscriber_id)->where('id', $id)->first();
            if (!$user) {
                return $this->back();
            }
            $user->delete();
        }
        return $this->back();
    }

    private function back()
    {
        return redirect()->route('users.index');
    }

    private function message()
    {
        return array(
            'name.required' => 'Campo nome é requerido.',
            'name.max' => 'Campo nome não pode ser maior que 255 caracteres.',
            'email.required' => 'Campo email é requerido.',
            'email.unique' => 'Email informado já existe.',
            'email.max' => 'Campo email não pode maior que 100 caracteres.',
            'password.required' => 'Campo password é requerido.',
            'password.confirmed' => 'Campo password não compátivel.',
            'password.min' => 'Password não atende ao tamanho mínimo de 4 caracteres.',
        );
    }
}
