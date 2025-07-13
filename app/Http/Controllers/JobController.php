<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class JobController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $jobs = Job::query()
            ->with('user:id,company_name')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $jobs
        ]);
    }

    public function show(Job $job): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $job->load('user:id,company_name')
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!in_array($request->user()->type, ['employer', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بإنشاء وظائف'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => ['required', Rule::in(['full_time', 'part_time', 'remote', 'hybrid'])],
            'description' => 'required|string',
            'location' => 'nullable|string'
        ]);

        $job = $request->user()->jobs()->create($validated);

        return response()->json([
            'success' => true,
            'data' => $job
        ], 201);
    }

    public function update(Request $request, Job $job): JsonResponse
    {
        if ($request->user()->type !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بتعديل هذه الوظيفة'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'type' => ['sometimes', Rule::in(['full_time', 'part_time', 'remote', 'hybrid'])],
            'description' => 'sometimes|string',
            'location' => 'nullable|string'
        ]);

        $job->update($validated);

        return response()->json([
            'success' => true,
            'data' => $job
        ]);
    }

    public function destroy(Request $request, Job $job): JsonResponse
    {
        if ($request->user()->type !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بحذف هذه الوظيفة'
            ], 403);
        }

        $job->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الوظيفة بنجاح'
        ], 204);
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => ['nullable', Rule::in(['full_time', 'part_time', 'remote', 'hybrid'])]
        ]);

        $jobs = Job::with('user:id,company_name')
            ->when($validated['q'] ?? false, fn($query, $q) => $query->search($q))
            ->when($validated['location'] ?? false, fn($query, $location) => $query->filterLocation($location))
            ->when($validated['type'] ?? false, fn($query, $type) => $query->filterType($type))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return response()->json([
            'success' => true,
            'data' => [
                'jobs' => $jobs->map(fn($job) => [
                    'id' => $job->id,
                    'title' => $job->title,
                    'type' => $job->type,
                    'location' => $job->location,
                    'company' => $job->user->company_name ?? 'غير محدد',
                    'posted_at' => $job->created_at->diffForHumans(),
                    'description' => Str::limit($job->description, 150, '...')
                ]),
                'meta' => [
                    'total' => $jobs->total(),
                    'current_page' => $jobs->currentPage(),
                    'last_page' => $jobs->lastPage(),
                    'per_page' => $jobs->perPage()
                ]
            ]
        ]);
    }
}