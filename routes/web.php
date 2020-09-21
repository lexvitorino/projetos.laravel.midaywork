<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', [Site\HomeController::class, 'index']);*/

Route::get('/', function(){
    return Redirect::to('/painel', 301);
});

Route::prefix('painel')->group(function () {
    Route::get('/', [Admin\DayRecordController::class, 'index'])->name('admin');

    Route::get('login', [Admin\Auth\LoginController::class, 'index'])->name('login');
    Route::post('login', [Admin\Auth\LoginController::class, 'authenticate']);
    Route::get('logout', [Admin\Auth\LoginController::class, 'logout'])->name('logout');

    Route::get('register', [Admin\Auth\RegisterController::class, 'index'])->name('register');
    Route::post('register', [Admin\Auth\RegisterController::class, 'store']);

    Route::resource('users', Admin\UserController::class);

    Route::resource('licenses', Admin\LicenseController::class);

    Route::get('dayRecord', [Admin\DayRecordController::class, 'index'])->name('dayRecord');
    Route::post('dayRecord', [Admin\DayRecordController::class, 'store'])->name('dayRecord.save');
    Route::get('dayRecord/workResume', [Admin\DayRecordController::class, 'getWorkResume'])->name('dayRecord.workResume');

    Route::get('monthlyReport', [Admin\MonthlyReportController::class, 'index'])->name('monthlyReport');
    Route::post('monthlyReport', [Admin\MonthlyReportController::class, 'index']);
    Route::put('monthlyReport', [Admin\MonthlyReportController::class, 'execute']);

    Route::get('managerReport', [Admin\ManagerReportController::class, 'index'])->name('managerReport');
    Route::post('managerReport', [Admin\ManagerReportController::class, 'execute']);
});
