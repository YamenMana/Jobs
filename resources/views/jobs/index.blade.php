@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">الوظائف المتاحة</h1>
        
        @if(auth()->user() && in_array(auth()->user()->type, ['employer', 'admin']))
        <a href="{{ route('jobs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة وظيفة
        </a>
        @endif
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <form action="{{ route('jobs.search') }}" method="GET" class="card card-body bg-light">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="q" class="form-control" placeholder="ابحث عن وظيفة..." value="{{ request('q') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="location" class="form-control" placeholder="الموقع" value="{{ request('location') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-select">
                            <option value="">كل أنواع الوظائف</option>
                            <option value="full_time" {{ request('type') == 'full_time' ? 'selected' : '' }}>دوام كامل</option>
                            <option value="part_time" {{ request('type') == 'part_time' ? 'selected' : '' }}>دوام جزئي</option>
                            <option value="remote" {{ request('type') == 'remote' ? 'selected' : '' }}>عن بعد</option>
                            <option value="hybrid" {{ request('type') == 'hybrid' ? 'selected' : '' }}>هجين</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @foreach($jobs as $job)
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title">{{ $job->title }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $job->user->company_name ?? 'غير محدد' }}</h6>
                        </div>
                        <span class="badge bg-{{ $job->type == 'full_time' ? 'primary' : ($job->type == 'part_time' ? 'info' : 'success') }}">
                            {{ $job->type == 'full_time' ? 'دوام كامل' : ($job->type == 'part_time' ? 'دوام جزئي' : ($job->type == 'remote' ? 'عن بعد' : 'هجين')) }}
                        </span>
                    </div>
                    
                    <p class="card-text mt-3">{{ Str::limit($job->description, 150) }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <i class="fas fa-map-marker-alt"></i> {{ $job->location ?? 'غير محدد' }}
                        </div>
                        <small class="text-muted">منذ {{ $job->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i> التفاصيل
                    </a>
                    
                    @if(auth()->user() && auth()->user()->type == 'job_seeker')
                    <a href="{{ route('applications.create', $job->id) }}" class="btn btn-primary btn-sm float-end">
                        <i class="fas fa-paper-plane"></i> التقديم
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $jobs->links() }}
    </div>
</div>
@endsection