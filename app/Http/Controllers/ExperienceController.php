<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperiencesController extends Controller
{
    /**
     * عرض قائمة الخبرات.
     */
    public function index()
    {
        $experiences = Experience::paginate(10);
        return view('experiences.index', compact('experiences'));
    }

    /**
     * عرض نموذج إضافة خبرة جديدة.
     */
    public function create()
    {
        return view('experiences.create');
    }

    /**
     * تخزين خبرة جديدة.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        Experience::create($request->all());
        return redirect()->route('experiences.index')->with('success', 'تم إضافة الخبرة بنجاح.');
    }

    /**
     * عرض بيانات خبرة واحدة.
     */
    public function show(Experience $experience)
    {
        return view('experiences.show', compact('experience'));
    }

    /**
     * عرض نموذج تعديل خبرة.
     */
    public function edit(Experience $experience)
    {
        return view('experiences.edit', compact('experience'));
    }

    /**
     * تحديث بيانات الخبرة.
     */
    public function update(Request $request, Experience $experience)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $experience->update($request->all());
        return redirect()->route('experiences.index')->with('success', 'تم تعديل الخبرة بنجاح.');
    }

    /**
     * حذف خبرة.
     */
    public function destroy(Experience $experience)
    {
        $experience->delete();
        return redirect()->route('experiences.index')->with('success', 'تم حذف الخبرة بنجاح.');
    }
}