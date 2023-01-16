<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\SpeakerSpeechController;
use App\Http\Controllers\SpeechController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/speeches', SpeechController::class);
    Route::resource('/speakers', SpeakerController::class);
    Route::get('/speakers/get-speeches/{speaker}', [SpeakerController::class, 'getSpeeches'])->name('speakers.get-speeches');

    Route::resource('/schedules', ScheduleController::class);
    Route::post('/schedules/save-receive/{schedule}', [ScheduleController::class, 'saveReceive'])->name('schedules.save-receive');
    Route::post('/schedules/save-send/{schedule}', [ScheduleController::class, 'saveSend'])->name('schedules.save-send');

    Route::post('/speaker-speech/{speaker}', [SpeakerSpeechController::class, 'store'])->name('speaker-speech.store');
    Route::delete('/speaker-speech/{speaker}/{speech}', [SpeakerSpeechController::class, 'destroy'])->name('speaker-speech.destroy');
});

require __DIR__ . '/auth.php';
