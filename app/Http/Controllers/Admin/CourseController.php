<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Only a single course is supported — index always routes straight to it.
    public function index()
    {
        $course = Course::first();

        return $course
            ? redirect()->route('admin.course.edit', $course->id)
            : redirect()->route('admin.course.create');
    }

    public function create()
    {
        // Block creating a second course.
        if (Course::exists()) {
            return redirect()->route('admin.course.edit', Course::first()->id);
        }

        return view('admin.pages.course.form', ['course' => new Course()]);
    }

    public function store(Request $request)
    {
        if (Course::exists()) {
            return redirect()->route('admin.course.edit', Course::first()->id)
                ->with('success', 'Only one course is supported — here is your existing course.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $course = Course::create($data);

        return redirect()->route('admin.course.edit', $course->id)->with('success', 'Course created.');
    }

    public function edit(Course $course)
    {
        return view('admin.pages.course.form', [
            'course' => $course,
            'modules' => $course->modules,
        ]);
    }

    public function update(Request $request, Course $course)
    {
        // Price is intentionally not editable here — it's controlled by the Freemius plan
        // and must only be changed there, so it's never accepted from this form.
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $course->update($data);

        return redirect()->route('admin.course.edit', $course->id)->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.course.index')->with('success', 'Course deleted.');
    }

    public function storeModule(Request $request, Course $course)
    {
        $data = $request->validate(['title' => 'required|string|max:255']);
        $data['order'] = $course->modules()->max('order') + 1;
        $course->modules()->create($data);

        return back()->with('success', 'Module added.');
    }

    public function updateModule(Request $request, CourseModule $module)
    {
        $module->update($request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]));

        return back()->with('success', 'Module updated.');
    }

    public function destroyModule(CourseModule $module)
    {
        $module->delete();

        return back()->with('success', 'Module deleted.');
    }
}
