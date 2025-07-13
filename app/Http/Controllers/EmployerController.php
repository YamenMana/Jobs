<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    /**
     * عرض قائمة أصحاب العمل.
     */
    public function index()
    {
        $employers = Employer::paginate(10);
        return view('employers.index', compact('employers'));
    }

    /**
     * عرض نموذج إضافة صاحب عمل جديد.
     */
    public function create()
    {
        return view('employers.create');
    }

    /**
     * تخزين صاحب عمل جديد.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employers,email',
            'phone' => 'nullable|string'
        ]);

        Employer::create($request->all());
        return redirect()->route('employers.index')->with('success', 'تم إضافة صاحب العمل بنجاح.');
    }

    /**
     * عرض بيانات صاحب عمل واحد.
     */
    public function show(Employer $employer)
    {
        return view('employers.show', compact('employer'));
    }

    /**
     * عرض نموذج تعديل صاحب عمل.
     */
    public function edit(Employer $employer)
    {
        return view('employers.edit', compact('employer'));
    }

    /**
     * تحديث بيانات صاحب العمل.
     */
    public function update(Request $request, Employer $employer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employers,email,' . $employer->id,
            'phone' => 'nullable|string'
        ]);

        $employer->update($request->all());
        return redirect()->route('employers.index')->with('success', 'تم تعديل صاحب العمل بنجاح.');
    }

    /**
     * حذف صاحب عمل.
     */
    public function destroy(Employer $employer)
    {
        $employer->delete();
        return redirect()->route('employers.index')->with('success', 'تم حذف صاحب العمل بنجاح.');
    }
}