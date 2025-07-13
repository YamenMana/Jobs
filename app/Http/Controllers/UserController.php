<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين.
     */
    public function index()
    {
        $users = User::paginate(10); // عرض 10 مستخدمين لكل صفحة
        return view('users.index', compact('users'));
    }

    /**
     * عرض نموذج إضافة مستخدم جديد.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * تخزين مستخدم جديد.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'تم إضافة المستخدم بنجاح.');
    }

    /**
     * عرض بيانات مستخدم واحد.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * عرض نموذج تعديل مستخدم.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * تحديث بيانات المستخدم.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = $request->only(['name', 'email']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'تم تعديل المستخدم بنجاح.');
    }

    /**
     * حذف مستخدم.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح.');
    }
}