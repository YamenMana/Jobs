    @extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    التقديم على وظيفة: {{ $job->title }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('applications.store') }}">
                        @csrf
                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                        <div class="mb-3">
                            <label class="form-label">الشركة</label>
                            <input type="text" class="form-control" value="{{ $job->user->company_name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">نوع الوظيفة</label>
                            <input type="text" class="form-control" value="{{ $job->type == 'full_time' ? 'دوام كامل' : ($job->type == 'part_time' ? 'دوام جزئي' : ($job->type == 'remote' ? 'عن بعد' : 'هجين')) }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">الموقع</label>
                            <input type="text" class="form-control" value="{{ $job->location ?? 'غير محدد' }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="cover_letter" class="form-label">رسالة التقديم</label>
                            <textarea id="cover_letter" class="form-control @error('cover_letter') is-invalid @enderror" name="cover_letter" rows="5" required>{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-paper-plane"></i> تقديم الطلب
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection