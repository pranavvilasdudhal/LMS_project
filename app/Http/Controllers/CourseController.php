<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\courses;
use App\Models\subject;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('subject')->get();
        return view('course.index', compact('courses'));
    }


    public function create()
    {
        $subjects = subject::all();
        return view('course.create', compact('subjects'));
    }

    public function edit($course_id)
    {
        $course = Course::with('subject')->findOrFail($course_id);
        $subjects = subject::all();

        return view('course.edit', compact('course', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_description' => 'required|string',
            'course_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mrp' => 'nullable|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'subject' => 'required|array',
            'subject.*' => 'exists:subject,subject_id',
        ]);

        $course = new Course();
        $course->course_name = $validated['course_name'];
        $course->course_description = $validated['course_description'];
        $course->mrp = $validated['mrp'] ?? null;
        $course->sell_price = $validated['sell_price'] ?? null;

        if ($request->hasFile('course_image')) {
            $image = $request->file('course_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/course_images'), $imageName);
            $course->course_image = $imageName;
        }

        $course->save();
        $course->subject()->attach($validated['subject']);

        return redirect('/courselist')->with('success', 'Course added successfully!');
    }

    public function update(Request $request, $course_id)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_description' => 'required|string',
            'course_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mrp' => 'nullable|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'subject' => 'required|array',
            'subject.*' => 'exists:subject,subject_id',
        ]);

        $course = Course::findOrFail($course_id);
        $course->course_name = $validated['course_name'];
        $course->course_description = $validated['course_description'];
        $course->mrp = $validated['mrp'] ?? null;
        $course->sell_price = $validated['sell_price'] ?? null;

        if ($request->hasFile('course_image')) {
            $image = $request->file('course_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/course_images'), $imageName);
            $course->course_image = $imageName;
        }

        $course->save();
        $course->subject()->sync($validated['subject']);

        return redirect()->route('courselist')->with('success', 'Course updated successfully!');
    }


    public function destroy($course_id)
    {
        $course = Course::findOrFail($course_id);
        $course->subject()->detach();
        if ($course->course_image && file_exists(public_path('storage/course_images/' . $course->course_image))) {
            unlink(public_path('storage/course_images/' . $course->course_image));
        }
        $course->delete();

        return redirect()->route('courselist')->with('success', 'Course deleted successfully!');
    }
}
