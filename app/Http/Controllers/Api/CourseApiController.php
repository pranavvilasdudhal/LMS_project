<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\subject;
use Illuminate\Http\Request;

class CourseApiController extends Controller
{
  
    public function index()
    {
        $courses = Course::with('subject')->get();

        return response()->json([
            'status' => true,
            'message' => 'Courses fetched successfully',
            'data' => $courses->map(function ($course) {
                return [
                    'id' => $course->course_id,
                    'course_name' => $course->course_name,
                    'description' => $course->course_description,
                    'image' => $course->course_image ? url('storage/course_images/' . $course->course_image) : null,
                    'mrp' => $course->mrp,
                    'sell_price' => $course->sell_price,
                    'subjects' => $course->subject->map(function ($sub) {
                        return [
                            'subject_id' => $sub->subject_id,
                            'subject_name' => $sub->subject_name,
                        ];
                    }),
                ];
            }),
        ], 200);
    }

  
    public function show($id)
    {
        $course = Course::with('subject')->find($id);

        if (!$course) {
            return response()->json(['status' => false, 'message' => 'Course not found'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $course->course_id,
                'course_name' => $course->course_name,
                'description' => $course->course_description,
                'image' => $course->course_image ? url('storage/course_images/' . $course->course_image) : null,
                'mrp' => $course->mrp,
                'sell_price' => $course->sell_price,
                'subjects' => $course->subject->map(function ($sub) {
                    return [
                        'subject_id' => $sub->subject_id,
                        'subject_name' => $sub->subject_name,
                    ];
                }),
            ],
        ]);
    }

    // ✅ Course Create API
    public function store(Request $request)
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

        return response()->json([
            'status' => true,
            'message' => 'Course added successfully',
            'data' => $course
        ], 201);
    }

    // ✅ Update Course
    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['status' => false, 'message' => 'Course not found'], 404);
        }

        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_description' => 'required|string',
            'course_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mrp' => 'nullable|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'subject' => 'required|array',
            'subject.*' => 'exists:subject,subject_id',
        ]);

        $course->update([
            'course_name' => $validated['course_name'],
            'course_description' => $validated['course_description'],
            'mrp' => $validated['mrp'] ?? null,
            'sell_price' => $validated['sell_price'] ?? null,
        ]);

        if ($request->hasFile('course_image')) {
            $image = $request->file('course_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/course_images'), $imageName);
            $course->course_image = $imageName;
            $course->save();
        }

        $course->subject()->sync($validated['subject']);

        return response()->json(['status' => true, 'message' => 'Course updated successfully']);
    }

    // ✅ Delete Course
    public function destroy($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['status' => false, 'message' => 'Course not found'], 404);
        }

        $course->subject()->detach();

        if ($course->course_image && file_exists(public_path('storage/course_images/' . $course->course_image))) {
            unlink(public_path('storage/course_images/' . $course->course_image));
        }

        $course->delete();

        return response()->json(['status' => true, 'message' => 'Course deleted successfully']);
    }
}
