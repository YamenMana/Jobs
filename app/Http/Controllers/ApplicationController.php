<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    // POST /api/applications
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'cover_letter' => 'nullable|string'
        ]);

        // التحقق من عدم التقديم المسبق
        $existingApplication = Application::where('user_id', $request->user()->id)
                                        ->where('job_id', $validated['job_id'])
                                        ->exists();

        if ($existingApplication) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قدمت بالفعل على هذه الوظيفة'
            ], 409);
        }

        $application = $request->user()->applications()->create([
            'job_id' => $validated['job_id'],
            'cover_letter' => $validated['cover_letter']
        ]);

        return response()->json([
            'success' => true,
            'data' => $application
        ], 201);
    }

    // GET /api/applications?user_id={id}
    public function userApplications(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // التحقق من أن المستخدم يطلب طلباته فقط (ما لم يكن مديراً)
        if ($request->user()->id != $request->user_id && $request->user()->type !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بمشاهدة هذه الطلبات'
            ], 403);
        }

        $applications = Application::with('job')
            ->where('user_id', $request->user_id)
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $applications
        ]);
    }

    // GET /api/applications?job_id={id}
    public function jobApplications(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id'
        ]);

        $job = Job::find($request->job_id);

        // التحقق من أن المستخدم صاحب العمل أو مدير
        if ($request->user()->id !== $job->user_id && $request->user()->type !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بمشاهدة المتقدمين لهذه الوظيفة'
            ], 403);
        }

        $applications = Application::with('user')
            ->where('job_id', $request->job_id)
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $applications
        ]);
    }
}