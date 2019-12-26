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

Route::get('/solve/{question_num}', 'Submission\SolveController@makeSubmission')->name('submission.create');
Route::get('/solve/{question_num}/{submission_num}', 'Submission\SolveController@showSubmission')->name('submission.view');
Route::get('/block/{question_num}', 'Question\ViewerController@getBlockList')->name('question.blocklist');
Route::post('/solve/{question_num}/{submission_num}/grade', 'Submission\EvalFileController@grade')->name('submission.grade');



Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::group(['middleware' => 'permit:Professor,Student', 'prefix' => 'question'],function(){
    Route::get('/add', 'Question\RegisterController@show')->name('question.add');
    Route::post('/add', 'Question\RegisterController@add')->name('question.add.post');
    Route::get('/edit/{problem_num}', 'Question\RegisterController@editAnswer')->name('question.edit');
    Route::post('/edit/{problem_num}', 'Question\RegisterController@addBlinkBlock')->name('question.edit.post');

    Route::get('/list', 'Question\ListController@showList')->name('question.list');
});


Route::group(['middleware' => 'permit:Professor,Student', 'prefix' => 'mypage'], function() {
    Route::get('/', 'Auth\MypageController@show')->name('mypage');
    Route::get('/question', 'MyPage\ProfessorController@showList')->name('mypage.question.list');
    Route::get('/submission', 'MyPage\StudentController@showList')->name('mypage.submission.list');
});
