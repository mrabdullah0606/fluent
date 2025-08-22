<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminWalletController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Auth\TeacherAuthController;
use App\Http\Controllers\Dashboard\AvailabilityController;
use App\Http\Controllers\Dashboard\ChatController;
use App\Http\Controllers\Dashboard\LessonTrackingController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\Dashboard\TeacherController;
use App\Http\Controllers\Dashboard\UserAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ZoomMeetingController;
use App\Http\Controllers\Dashboard\StripeController;
use App\Http\Controllers\Dashboard\WalletController;
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
Route::get('/tutor/{id}', [HomeController::class, 'tutor'])->name('tutor');
Route::get('/tutor-booking/{id}', [HomeController::class, 'tutorBooking'])->name('tutor.booking');
Route::get('/one-on-one-tutors', [HomeController::class, 'oneOnOneTutors'])->name('one.on.one.tutors');
Route::get('/group-lesson', [HomeController::class, 'groupLesson'])->name('group.lesson');
Route::get('/become/tutor', [HomeController::class, 'becomeTutor'])->name('become.tutor');
Route::get('/careers', [HomeController::class, 'careers'])->name('careers');
// Route::get('/apply/form', [HomeController::class, 'applyForm'])->name('apply.form');
Route::get('/apply/{career}', [HomeController::class, 'applyForm'])->name('apply.form');
Route::post('/apply', [HomeController::class, 'submitApplication'])->name('apply.submit');
Route::get('/apply/general', [HomeController::class, 'applyGeneral'])->name('apply.general');
Route::get('/public/availability/monthly/{teacherId}', [HomeController::class, 'monthlyAvailability']);
Route::get('/public/availability/date/{teacherId}', [HomeController::class, 'dateAvailability']);
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
        Route::get('add-reviews', [StudentController::class, 'addReviews'])->name('student.add.review');
        // Route::post('reviews/{teacher}', [StudentController::class, 'storeReview'])->name('student.reviews.store');
        Route::post('reviews', [StudentController::class, 'reviewStore'])->name('student.reviews.store');
        Route::get('/find-tutor', [StudentController::class, 'findTutor'])->name('student.find.tutor');
        Route::get('/one-on-one-tutors', [StudentController::class, 'oneOnOneTutors'])->name('student.one.on.one.tutors');
        Route::get('/group-lesson', [StudentController::class, 'groupLesson'])->name('student.group.lesson');
        Route::get('/lesson-tracking', [LessonTrackingController::class, 'lessonTracking'])->name('student.lesson.tracking');
        Route::get('zoom/join/{id}', [LessonTrackingController::class, 'join'])
            ->name('student.zoom.join');
        /* ********************************** CHAT ROUTES ********************************** */
        Route::get('chats', [ChatController::class, 'studentChatList'])->name('student.chats.index');
        Route::get('chat/{user}', [ChatController::class, 'index'])->name('student.chat.index');
        Route::post('chat/send', [ChatController::class, 'send'])->name('student.chat.send');

        // Logout route
        Route::post('logout', [StudentAuthController::class, 'logout'])->name('student.logout');
        /* ********************************** zoom ********************************** */
        Route::get('zoom-meetings', [ZoomMeetingController::class, 'indexStudent'])->name('zoom.meetings.index');
        Route::post('zoom-meetings', [ZoomMeetingController::class, 'store'])->name('zoom.meetings.store');
        /************************** Stripe *************************************************/
        Route::get('/checkout', [StudentController::class, 'checkout'])->name('student.tutor.checkout');
        Route::post('/create-checkout-session', [StripeController::class, 'create'])->name('student.stripe.checkout');
    });
});

Route::prefix('teacher')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [TeacherAuthController::class, 'showLoginForm'])->name('teacher.login');
        Route::post('login', [TeacherAuthController::class, 'login'])->name('teacher.login.submit');
        Route::get('register', [TeacherAuthController::class, 'showRegisterForm'])->name('teacher.register');
        Route::post('register', [TeacherAuthController::class, 'register'])->name('teacher.register.submit');
        // Email verification routes (accessible to guests)
        Route::get('verify', [TeacherAuthController::class, 'showVerifyForm'])->name('teacher.verify.form');
        Route::post('verify', [TeacherAuthController::class, 'verify'])->name('teacher.verify');
        Route::post('resend-code', [TeacherAuthController::class, 'resendCode'])->name('teacher.resend.code');
    });

    Route::middleware(['auth', 'isTeacher'])->group(function () {
        Route::get('dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');
        Route::get('public-profile', [TeacherController::class, 'publicProfile'])->name('teacher.public.profile');
        Route::get('profile/edit', [TeacherController::class, 'editProfile'])->name('teacher.profile.edit');
        Route::put('profile/update', [TeacherController::class, 'updateProfile'])->name('teacher.profile.update');
        Route::get('calendar', [TeacherController::class, 'calendar'])->name('teacher.calendar');
        Route::get('bookings', [TeacherController::class, 'bookings'])->name('teacher.bookings');
        Route::post('bookings/update', [TeacherController::class, 'updateBookingRules'])->name('teacher.bookings.update');
        Route::get('wallet', [TeacherController::class, 'wallet'])->name('teacher.wallet');


        // Route::get('settings', [TeacherController::class, 'settings'])->name('teacher.settings');
        // Settings Management
        Route::prefix('settings')->name('teacher.settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::put('/update', [SettingsController::class, 'update'])->name('update');

            // Individual lesson pricing
            Route::put('/pricing', [SettingsController::class, 'updatePricing'])->name('pricing.update');

            // Lesson packages
            Route::post('/packages', [SettingsController::class, 'storePackage'])->name('packages.store');
            Route::put('/packages/{package}', [SettingsController::class, 'updatePackage'])->name('packages.update');
            Route::delete('/packages/{package}', [SettingsController::class, 'destroyPackage'])->name('packages.destroy');
            Route::patch('/packages/{package}/toggle', [SettingsController::class, 'togglePackage'])->name('packages.toggle');

            // Group classes
            Route::post('/groups', [SettingsController::class, 'storeGroup'])->name('groups.store');
            Route::put('/groups/{group}', [SettingsController::class, 'updateGroup'])->name('groups.update');
            Route::delete('/groups/{group}', [SettingsController::class, 'destroyGroup'])->name('groups.destroy');
            Route::patch('/groups/{group}/toggle', [SettingsController::class, 'toggleGroup'])->name('groups.toggle');
        });

        /* ********************************** CHAT ROUTES ********************************** */
        Route::get('chats', [ChatController::class, 'teacherChatList'])->name('teacher.chats.index');
        Route::get('chat/{user}', [ChatController::class, 'index'])->name('teacher.chat.index');
        Route::post('chat/send', [ChatController::class, 'send'])->name('teacher.chat.send');

        /* ********************************** zoom ********************************** */
        Route::get('zoom-meetings', [ZoomMeetingController::class, 'index'])->name('teacher.zoom.meetings.index');
        Route::post('zoom-meetings', [ZoomMeetingController::class, 'store'])->name('teacher.zoom.meetings.store');
        Route::get('zoom/summaries', [ZoomMeetingController::class, 'getSummaries'])->name('teacher.zoom.getSummaries');
        Route::get('zoom/students', [ZoomMeetingController::class, 'getStudentsBySummary'])->name('teacher.zoom.getStudentsByPackage');
        Route::get('zoom/packages', [ZoomMeetingController::class, 'getPackages'])->name('teacher.zoom.getPackages');


        /* ****************************** Availability ****************************** */
        Route::prefix('availability')->name('teacher.availability.')->group(function () {
            Route::get('/date', [AvailabilityController::class, 'getAvailabilityForDate'])->name('get-date');
            Route::post('/store', [AvailabilityController::class, 'store'])->name('store');
            Route::delete('/{id}', [AvailabilityController::class, 'destroy'])->name('destroy');
            Route::post('/mark-unavailable', [AvailabilityController::class, 'markDayUnavailable'])->name('mark-unavailable');
            Route::get('/monthly', [AvailabilityController::class, 'getMonthlyAvailability'])->name('monthly');
        });
        /* ****************************** Wallet Management ****************************** */
        Route::prefix('wallet')->name('teacher.wallet.')->group(function () {
            Route::get('/', [WalletController::class, 'index'])->name('index');
            Route::post('/withdraw', [WalletController::class, 'withdraw'])->name('withdraw');
            Route::post('/payment-settings', [WalletController::class, 'updatePaymentSettings'])->name('payment-settings');
            Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
        });
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
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('test', [AdminController::class, 'testEmbed'])->name('admin.test');

        /* ******************************** Language ******************************** */
        Route::prefix('languages')->group(function () {
            Route::get('/', [AdminController::class, 'languagesIndex'])->name('admin.languages.index');
            Route::get('create', [AdminController::class, 'languageCreate'])->name('admin.languages.create');
            Route::post('/', [AdminController::class, 'languagesStore'])->name('admin.languages.store');
            Route::get('{language}/edit', [AdminController::class, 'languagesEdit'])->name('admin.languages.edit');
            Route::put('{language}', [AdminController::class, 'languagesUpdate'])->name('admin.languages.update');
            Route::delete('{language}', [AdminController::class, 'languagesDestroy'])->name('admin.languages.destroy');
        });
        /* ****************************** language end ****************************** */

        /* ******************************** User Management ******************************** */
        Route::prefix('users')->group(function () {
            Route::get('/', [AdminController::class, 'usersIndex'])->name('admin.users.index');
            Route::get('create', [AdminController::class, 'userCreate'])->name('admin.users.create');
            Route::post('/', [AdminController::class, 'userStore'])->name('admin.users.store');
            Route::get('edit/{user}', [AdminController::class, 'userEdit'])->name('admin.users.edit');
            Route::put('{user}', [AdminController::class, 'userUpdate'])->name('admin.users.update');
            Route::delete('{user}', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');
        });
        /* ******************************** User Management End ******************************** */

        /* ******************************** Career Management ******************************** */
        Route::prefix('careers')->name('admin.careers.')->group(function () {
            Route::get('/', [AdminController::class, 'careersIndex'])->name('index');
            Route::get('create', [AdminController::class, 'careerCreate'])->name('create');
            Route::post('/', [AdminController::class, 'careerStore'])->name('store');
            Route::get('edit/{career}', [AdminController::class, 'careerEdit'])->name('edit');
            Route::put('{career}', [AdminController::class, 'careerUpdate'])->name('update');
            Route::delete('{career}', [AdminController::class, 'careerDestroy'])->name('destroy');
        });
        /* ******************************** Career Management End ******************************** */

        /* ******************************** Career Management ******************************** */
        Route::prefix('applicants')->name('admin.applicants.')->group(function () {
            Route::get('/', [AdminController::class, 'applicantsIndex'])->name('index');
            Route::get('create', [AdminController::class, 'applicantsCreate'])->name('create');
            Route::post('/', [AdminController::class, 'applicantsStore'])->name('store');
            Route::get('edit/{applicant}', [AdminController::class, 'applicantsEdit'])->name('edit');
            Route::put('{applicant}', [AdminController::class, 'applicantsUpdate'])->name('update');
            Route::delete('{applicant}', [AdminController::class, 'applicantsDestroy'])->name('destroy');
        });

        Route::prefix('reviews')->name('admin.reviews.')->group(function () {
            Route::get('/', [AdminController::class, 'reviewsIndex'])->name('index');
            Route::patch('/{review}/toggle', [AdminController::class, 'approveReview'])->name('toggle');
            Route::delete('/{review}', [AdminController::class, 'reviewsDestroy'])->name('destroy');
        });
        /* ******************************** Career Management End ******************************** */
        Route::prefix('wallet')->name('admin.wallet.')->group(function () {
            Route::get('/withdrawals', [AdminWalletController::class, 'withdrawals'])->name('withdrawals.index');
            Route::get('/withdrawals/{id}', [AdminWalletController::class, 'showWithdrawal'])->name('withdrawals.show');
            Route::post('/withdrawals/{id}/approve', [AdminWalletController::class, 'approveWithdrawal'])->name('withdrawals.approve');
            Route::post('/withdrawals/{id}/reject', [AdminWalletController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
        });

        Route::prefix('customer-support')->name('admin.customer.')->group(function () {
            // Existing routes...
            Route::get('/', [SupportController::class, 'index'])->name('support');
            Route::get('/webhook-config', function () {
                return view('admin.content.webhook-config');
            })->name('support.webhook.config');
            Route::get('/test', function () {
                return view('admin.content.support-test');
            })->name('support.test.page');

            // API endpoints
            Route::get('/stats', [SupportController::class, 'getStats'])->name('support.stats');
            Route::get('/conversations', [SupportController::class, 'getConversations'])->name('support.conversations');
            Route::get('/conversations/{conversationId}/messages', [SupportController::class, 'getMessages'])->name('support.messages');
            Route::post('/conversations/{conversationId}/messages', [SupportController::class, 'sendMessage'])->name('support.send.message');

            // Real-time updates
            Route::get('/live-updates', [SupportController::class, 'getLiveUpdates'])->name('support.live.updates');
            Route::get('/webhook-logs', [SupportController::class, 'getWebhookLogs'])->name('support.webhook.logs');
            Route::get('/conversations/{conversationId}/typing', [SupportController::class, 'getTypingStatus'])->name('support.typing.status');

            // NEW ENHANCED ENDPOINTS
            Route::get('/status', [SupportController::class, 'getStatus'])->name('support.status');
            Route::post('/clear-cache', [SupportController::class, 'clearCache'])->name('support.clear.cache');

            // Utility routes
            Route::get('/test-connection', [SupportController::class, 'testConnection'])->name('support.test');
            Route::get('/redirect/{section?}', [SupportController::class, 'redirect'])->name('support.redirect');
        });
    });
});

Route::post('/admin/customer-support/webhook', [SupportController::class, 'webhook'])
    ->name('admin.customer.support.webhook')
    ->withoutMiddleware(['web']);

Route::get('/admin/customer-support/webhook-test', function () {
    return response()->json([
        'status' => 'Webhook endpoint is reachable',
        'url' => route('admin.customer.support.webhook'),
        'timestamp' => now()
    ]);
});
Route::get('/admin/customer-support/stream-events', [SupportController::class, 'streamEvents'])
    ->name('admin.customer.support.stream.events');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
