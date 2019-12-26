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
    Route::get('/add', 'Question\RegisterController@show')->name('question.add');
    Route::post('/add', 'Question\RegisterController@add')->name('question.add.post');
    Route::get('/list', 'Question\ListController@showList')->name('question.list');
    Route::get('/{question_id}', 'Question\ViewerController@show')->name('question.view');
});


Route::group(['prefix' => 'mypage'], function() {
    Route::get('/', 'Auth\MypageController@show')->name('mypage');
    Route::get('/question', 'MyPage\ProfessorController@showList')->name('mypage.question.list');
    Route::get('/submission', 'MyPage\StudentController@showList')->name('mypage.submission.list');
});

Route::get('/create/{problem_num}', 'Problem\CreateController@showAnswer')->name('Answer');
Route::post('/create/{problem_num}', 'Problem\CreateController@addBlinkBlock')->name('Blink_Block');

Route::get('/createaa/{problem_num}', 'Problem\CreateController@inputAnswer')->name('input.answer.view');
Route::post('/createaa/{problem_num}', 'Problem\CreateController@inputAnswer')->name('input.answer.post');

Route::get('/solve/{question_num}', 'Question\SolveController@show');
Route::post('/solve/{question_num}', 'Question\SolveController@grade');
