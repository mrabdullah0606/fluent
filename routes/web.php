<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\Dashboard\TeacherController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


/* ************************************************************************** */
/*                               WEBSITE ROUTES                               */
/* ************************************************************************** */

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/messages', [HomeController::class, 'messages'])->name('messages');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/calender', [HomeController::class, 'calender'])->name('calender');
Route::get('/find-tutor', [HomeController::class, 'findTutor'])->name('find.tutor');
Route::get('/live-class', [HomeController::class, 'liveClasses'])->name('live.class');
// Route::get('/admin-login', [HomeController::class, 'adminLogin'])->name('admin.login');
// Route::get('/message', [HomeController::class, 'message'])->name('message');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/* ************************************************************************** */
/*                               STUDENT ROUTES                               */
/* ************************************************************************** */

Route::group(['prefix' => 'student', 'middleware' => ['auth', 'isStudent']], function () {
    Route::get('/dashboard', [StudentController::class, 'index'])->name('student.index');
});

/* ************************************************************************** */
/*                               TEACHER ROUTES                               */
/* ************************************************************************** */

Route::group(['prefix' => 'teacher', 'middleware' => ['auth', 'isTeacher']], function () {
    Route::get('/dashboard', [TeacherController::class, 'index'])->name('teacher.index');
});

/* ************************************************************************** */
/*                                ADMIN Routes                                */
/* ************************************************************************** */
// Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'isAdmin']], function () {
//     Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.index');
// });
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    Route::middleware(['auth', 'isAdmin'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.index');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
