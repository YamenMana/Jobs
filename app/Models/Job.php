<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'description',
        'location'
    ];

    protected $casts = [
        'type' => 'string'
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope للبحث في العنوان والوصف
     */
    public function scopeSearch($query, ?string $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        return $query->where(function($q) use ($keyword) {
            $q->where('title', 'LIKE', "%{$keyword}%")
              ->orWhere('description', 'LIKE', "%{$keyword}%");
        });
    }

    /**
     * Scope للتصفية حسب الموقع
     */
    public function scopeFilterLocation($query, ?string $location)
    {
        if (empty($location)) {
            return $query;
        }
        return $query->where('location', 'LIKE', "%{$location}%");
    }

    /**
     * Scope للتصفية حسب نوع الوظيفة
     */
    public function scopeFilterType($query, ?string $type)
    {
        if (empty($type)) {
            return $query;
        }
        return $query->where('type', $type);
    }
}