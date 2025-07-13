<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;

// الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
});

// مسارات المصادقة
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// مسارات تحتاج مصادقة
Route::middleware('auth')->group(function () {
    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // مسارات المستخدمين (للمدير فقط)
    Route::middleware('can:admin')->group(function () {
        Route::resource('users', UserController::class);
    });
    
    // مسارات الوظائف
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/search', [JobController::class, 'search'])->name('jobs.search');
    
    // إنشاء وتعديل الوظائف لصاحب العمل أو المدير
    Route::middleware('can:employer,admin')->group(function () {
        Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    });
    
    Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
    
    // التعديل والحذف للمدير فقط
    Route::middleware('can:admin')->group(function () {
        Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');
    });
    
    // مسارات التقديم على الوظائف
    Route::middleware('can:job_seeker')->group(function () {
        Route::get('/applications/create/{job}', [ApplicationController::class, 'create'])->name('applications.create');
        Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
    });
    
    Route::get('/applications/user', [ApplicationController::class, 'userApplications'])->name('applications.user');
    
    Route::middleware('can:employer,admin')->group(function () {
        Route::get('/applications/job/{job}', [ApplicationController::class, 'jobApplications'])->name('applications.job');
    });
    
    // مسارات البروفايل
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
    });
    
    // مسارات الرسائل
    Route::resource('messages', MessageController::class)->except(['edit', 'update']);
});

// مسارات عامة للرسائل (لا تحتاج مصادقة)
Route::post('/contact', [MessageController::class, 'store'])->name('contact.store');