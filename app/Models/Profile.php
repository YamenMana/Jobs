<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'date_of_birth',
        'bio'
    ];

    /**
     * العلاقة مع المستخدم.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}