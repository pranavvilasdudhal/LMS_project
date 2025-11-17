<?php

namespace App\Http\Controllers;

use App\Models\subject;
use App\Models\User;
use Illuminate\Http\Request;

class subjectController extends Controller
{

    public function index()
    {
        $subject = subject::all();
        return view('subject.index', compact('subject'));
    }

    // Store new subject
    public function store(Request $request)
    {

        $subject = new subject();
        $subject->subject_name = $request->name;
        $subject->subject_description = $request->description;
        if ($request->hasFile("image")) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/profiles/'), $imageName);
            $subject->subject_image = $imageName;
        }
        $subject->save();

        return redirect('/subject')->with('succsess', 'student registration succsess!');
    }

    public function create()
    {
        return view('subject.create');
    }


    //   Show edit form
    public function edit($subject_id)
    {
        $subject = subject::findOrFail($subject_id);
        return view('subject.edit', compact('subject'));
    }

    public function update(Request $request, $subject_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $subject = Subject::findOrFail($subject_id);
        $subject->subject_name = $request->name;
        $subject->subject_description = $request->description;

        if ($request->hasFile("image")) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/profiles/'), $imageName);
            $subject->subject_image = $imageName;
        }

        $subject->save();

        return redirect()->route('subject.index')->with('success', 'Subject updated successfully!');
    }

    // Delete student
    public function destroy($subject_id)
    {

        $subject = subject::findorFail($subject_id);
        $subject->delete();
        return redirect('/subject')->with('success', 'Student deleted successfully');
    }
}
