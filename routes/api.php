<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;



// المسارات العامة (بدون مصادقة)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/jobs/search', [JobController::class, 'search']); // بحث بدون مصادقة

// المسارات التي تحتاج مصادقة
Route::middleware('auth:sanctum')->group(function () {
    // مصادقة المستخدم
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // مسارات الوظائف
    Route::apiResource('jobs', JobController::class)->except(['index', 'show']);
    Route::get('/jobs', [JobController::class, 'index']); // عرض الوظائف مع الترقيم
    Route::get('/jobs/{job}', [JobController::class, 'show']); // عرض وظيفة محددة

    // مسارات التقديم على الوظائف
    Route::prefix('applications')->group(function () {
        Route::post('/', [ApplicationController::class, 'store']);
        Route::get('/user', [ApplicationController::class, 'userApplications']); // طلبات المستخدم
        Route::get('/job/{job}', [ApplicationController::class, 'jobApplications']); // متقدمو وظيفة
    });
});
Route::middleware('auth:sanctum')->group(function () {
    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
    });
    
    // يمكنك إضافة مسارات أخرى متعلقة بالمستخدم هنا
});
Route::prefix('messages')->group(function () {
    Route::get('/', [MessageController::class, 'index']); // عرض جميع الرسائل
    Route::post('/', [MessageController::class, 'store']); // إرسال رسالة جديدة
    
    Route::prefix('{message}')->group(function () {
        Route::get('/', [MessageController::class, 'show']); // عرض رسالة معينة
        Route::delete('/', [MessageController::class, 'destroy']); // حذف رسالة
    });
});





