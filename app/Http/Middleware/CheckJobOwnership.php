<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\Job;
use Illuminate\Http\Request;

class CheckJobOwnership
{
    public function handle(Request $request, Closure $next)
    {
        $job = $request->route('job');
        
        if ($job && $job->user_id !== $request->user()->id) {
            abort(403, 'غير مسموح لك بالوصول إلى هذه الوظيفة');
        }
        
        return $next($request);
    }
}