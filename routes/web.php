<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use Illuminate\Auth\Middleware\Authenticate;
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

Route::get('/', function () {
    if(Auth::user()){
        return redirect()->route('home');
    }else{
        return view('auth.login');
    }
});
Auth::routes(['register' => false]);
Route::group(['middleware'=>['auth']],function(){
    Route::get('/home', [App\Http\Controllers\CompanyController::class, 'index'])->name('home');
    Route::resource('companies', CompanyController::class);
});