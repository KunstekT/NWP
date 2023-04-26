<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth', 'role:nastavnik'])->post('/tasks', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
Route::get('/locale/{locale}', function ($locale) {
    App::setLocale($locale);
    return redirect()->back();
})->name('locale.change');
Route::get('/tasks/signup', 'TaskController@showSignupPage')->name('tasks.signup');
Route::get('/teacher/tasks/{task}/applications', [TaskController::class, 'showApplications'])->middleware(['auth', 'role:nastavnik']);
Route::post('/teacher/tasks/{task}/applications/{application}/approve', [TaskController::class, 'approveApplication'])->middleware(['auth', 'role:nastavnik']);

Auth::routes();