<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * عرض قائمة التعليمات.
     */
    public function index()
    {
        $educations = Education::paginate(10);
        return view('educations.index', compact('educations'));
    }

    /**
     * عرض نموذج إضافة تعليم جديد.
     */
    public function create()
    {
        return view('educations.create');
    }

    /**
     * تخزين تعليم جديد.
     */
    public function store(Request $request)
    {
        $request->validate([
            'school' => 'required|string',
            'degree' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        Education::create($request->all());
        return redirect()->route('educations.index')->with('success', 'تم إضافة التعليم بنجاح.');
    }

    /**
     * عرض بيانات تعليم واحد.
     */
    public function show(Education $education)
    {
        return view('educations.show', compact('education'));
    }

    /**
     * عرض نموذج تعديل تعليم.
     */
    public function edit(Education $education)
    {
        return view('educations.edit', compact('education'));
    }

    /**
     * تحديث بيانات التعليم.
     */
    public function update(Request $request, Education $education)
    {
        $request->validate([
            'school' => 'required|string',
            'degree' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $education->update($request->all());
        return redirect()->route('educations.index')->with('success', 'تم تعديل التعليم بنجاح.');
    }

    /**
     * حذف تعليم.
     */
    public function destroy(Education $education)
    {
        $education->delete();
        return redirect()->route('educations.index')->with('success', 'تم حذف التعليم بنجاح.');
    }
}