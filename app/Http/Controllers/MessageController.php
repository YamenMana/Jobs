<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * عرض جميع الرسائل (للوحة التحكم)
     */
    public function index()
    {
        $messages = Message::all();
        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * تخزين رسالة جديدة (من نموذج الاتصال)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $message = Message::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الرسالة بنجاح',
            'data' => $message
        ], 201);
    }

    /**
     * عرض رسالة معينة
     */
    public function show(Message $message)
    {
        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    /**
     * حذف رسالة
     */
    public function destroy(Message $message)
    {
        $message->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'تم حذف الرسالة بنجاح'
        ]);
    }
}