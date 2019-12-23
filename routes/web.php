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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', function (){
    return view('/auth/login');
})->name('login');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Auth::routes();
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::group(['prefix' => 'mypage'], function() {
    Route::get('/', 'Auth\MypageController@show')->name('mypage');
    Route::get('/question', 'MyPage\ProfessorController@showList')->name('mypage.question.list');
    Route::get('/submission', 'MyPage\StudentController@showList')->name('mypage.submission.list');
});
