<?php

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

Route::get('/', 'DashboardController@index');

Route::get('login','Auth\LoginController@showLoginForm')->name('login');
Route::post('login','Auth\LoginController@login');
Route::post('logout','Auth\LoginController@logout')->name('logout');
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.update');
Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::get('register/peminjam','Auth\RegisterController@showRegistrationFormPeminjam')->name('register.peminjam');
Route::get('register/penyedia','Auth\RegisterController@showRegistrationFormPenyedia')->name('register.penyedia');
Route::get('register','Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register','Auth\RegisterController@register')->name('register.act');


Route::group(['prefix'=>'dashboard'], function (){
    Route::get('','DashboardController@index')->name('dashboard.index');
    Route::resource('kategori','KategoriController');
    Route::resource('user','UserController')->except(['create','store']);
    Route::resource('ruangan','RuanganController');
    Route::resource('reservasi','ReservasiController');
    Route::resource('report','ReportController');
    Route::post('reservasi/action/{reservasi}','ReservasiController@action')->name('reservasi.action');
    Route::post('user/activate/{user}','UserController@activate')->name('user.activate');
    Route::resource('report','ReportController')->except(['create','store']);
    Route::get('/user/{user}/report','ReportController@create')->name('report.create');
    Route::post('/user/{user}/report','ReportController@store')->name('report.store');
    Route::get('gantipassword','UserController@gantiPassword')->name('user.gantipass');
    Route::post('gantipassword','UserController@gantiPasswordAct')->name('user.gantipass.act');
});

