<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Profile;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * عرض بروفايل المستخدم الحالي
     */
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found'
            ], 404);
        }

        return response()->json([
            'profile' => $profile
        ]);
    }

    /**
     * إنشاء أو تحديث البروفايل
     */
    
    
    
    
    
    
    
    
    
    

    
    
    
    
public function update(Request $request)
{
    try {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string|max:500'
        ]);

        // الطريقة الأكثر أماناً
        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error updating profile',
            'error' => $e->getMessage()
        ], 500);
    }
}
    
    
    
    
    
}