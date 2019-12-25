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

use Illuminate\Support\Facades\Route;
use function foo\func;


Route::get('/login', function (){
    return view('/auth/login');
})->name('login');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Auth::routes();

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::group(['prefix' => 'question'],function(){
    Route::get('/list', 'Question\ListController@showList')->name('question.list');
    Route::get('/{question_num}', 'Question\ViewerController@show')->name('question.view');
});


Route::group(['prefix' => 'mypage'], function() {
    Route::get('/', 'Auth\MypageController@show')->name('mypage');
    Route::get('/question', 'MyPage\ProfessorController@showList')->name('mypage.question.list');
    Route::get('/submission', 'MyPage\StudentController@showList')->name('mypage.submission.list');
});

Route::get('/create/{problem_num}', 'Problem\CreateController@showAnswer')->name('Answer');
Route::post('/create/{problem_num}', 'Problem\CreateController@addBlinkBlock')->name('Blink_Block');

Route::get('/solve/{problem_num}', 'Problem\SolveController@show');
