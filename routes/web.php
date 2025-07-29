<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Auth\TeacherAuthController;
use App\Http\Controllers\Dashboard\ChatController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\Dashboard\TeacherController;
use App\Http\Controllers\Dashboard\UserAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


/* ************************************************************************** */
/*                               WEBSITE ROUTES                               */
/* ************************************************************************** */

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/messages', [HomeController::class, 'messages'])->name('messages');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/calendar', [HomeController::class, 'calendar'])->name('calendar');
Route::get('/find-tutor', [HomeController::class, 'findTutor'])->name('find.tutor');
Route::get('/live-class', [HomeController::class, 'liveClasses'])->name('live.class');
Route::get('/languages/{language}/teachers', [HomeController::class, 'showByLanguage'])->name('languages.teachers');
// Route::get('/admin-login', [HomeController::class, 'adminLogin'])->name('admin.login');
// Route::get('/message', [HomeController::class, 'message'])->name('message');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
Route::get('/switch-to-teacher', function () {
    if (auth()->check() && auth()->user()->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    }
    return redirect()->route('teacher.login');
})->name('switch.to.teacher');
Route::get('send-mail', [MailController::class, 'index']);
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/* ************************************************************************** */
/*                               STUDENT ROUTES                               */
/* ************************************************************************** */

Route::prefix('student')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
        Route::post('login', [StudentAuthController::class, 'login'])->name('student.login.submit');
        Route::get('register', [StudentAuthController::class, 'showRegisterForm'])->name('student.register');
        Route::post('register', [StudentAuthController::class, 'register'])->name('student.register.submit');

        // Email verification routes (accessible to guests)
        Route::get('verify', [StudentAuthController::class, 'showVerifyForm'])->name('student.verify.form');
        Route::post('verify', [StudentAuthController::class, 'verify'])->name('student.verify');
        Route::post('resend-code', [StudentAuthController::class, 'resendCode'])->name('student.resend.code');
    });

    Route::middleware(['auth', 'isStudent'])->group(function () {
        Route::get('dashboard', [StudentController::class, 'index'])->name('student.dashboard');
        Route::get('public-profile', [StudentController::class, 'publicProfile'])->name('student.public.profile');
        Route::get('profile/edit', [StudentController::class, 'editProfile'])->name('student.profile.edit');
        Route::put('profile/update', [StudentController::class, 'updateProfile'])->name('student.profile.update');

        /* ********************************** CHAT ROUTES ********************************** */
        Route::get('chats', [ChatController::class, 'studentChatList'])->name('student.chats.index');
        Route::get('chat/{user}', [ChatController::class, 'index'])->name('student.chat.index');
        Route::post('chat/send', [ChatController::class, 'send'])->name('student.chat.send');

        // Logout route
        Route::post('logout', [StudentAuthController::class, 'logout'])->name('student.logout');
    });
});

Route::prefix('teacher')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [TeacherAuthController::class, 'showLoginForm'])->name('teacher.login');
        Route::post('login', [TeacherAuthController::class, 'login'])->name('teacher.login.submit');
        Route::get('register', [TeacherAuthController::class, 'showRegisterForm'])->name('teacher.register');
        Route::post('register', [TeacherAuthController::class, 'register'])->name('teacher.register.submit');
    });

    Route::middleware(['auth', 'isTeacher'])->group(function () {
        Route::get('dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');
        Route::get('public-profile', [TeacherController::class, 'publicProfile'])->name('teacher.public.profile');
        Route::get('profile/edit', [TeacherController::class, 'editProfile'])->name('teacher.profile.edit');
        Route::put('profile/update', [TeacherController::class, 'updateProfile'])->name('teacher.profile.update');
        Route::get('calendar', [TeacherController::class, 'calendar'])->name('teacher.calendar');

        /* ********************************** CHAT ROUTES ********************************** */
        Route::get('chats', [ChatController::class, 'teacherChatList'])->name('teacher.chats.index');
        Route::get('chat/{user}', [ChatController::class, 'index'])->name('teacher.chat.index');
        Route::post('chat/send', [ChatController::class, 'send'])->name('teacher.chat.send');
    });
});
/* ************************************************************************** */
/*                                ADMIN Routes                                */
/* ************************************************************************** */
// Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'isAdmin']], function () {
//     Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.index');
// });
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminController::class, 'showLoginForm'])->name('admin.login');
        Route::post('login', [AdminController::class, 'login'])->name('admin.login.submit');
    });

    Route::middleware(['auth', 'isAdmin'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.index');

        /* ******************************** Language ******************************** */
        Route::prefix('languages')->group(function () {
            Route::get('/', [AdminController::class, 'languagesIndex'])->name('admin.languages.index');
            Route::get('create', [AdminController::class, 'languageCreate'])->name('admin.languages.create');
            Route::post('/', [AdminController::class, 'languagesStore'])->name('admin.languages.store');
            Route::get('{language}/edit', [AdminController::class, 'languagesEdit'])->name('admin.languages.edit'); // AJAX route
            Route::put('{language}', [AdminController::class, 'languagesUpdate'])->name('admin.languages.update');
            Route::delete('{language}', [AdminController::class, 'languagesDestroy'])->name('admin.languages.destroy');
        });
        /* ****************************** language end ****************************** */
        // Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
