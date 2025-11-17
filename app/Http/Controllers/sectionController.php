<?php

namespace App\Http\Controllers;

use App\Models\section;
use App\Models\subject;
use Illuminate\Http\Request;

class sectionController extends Controller
{

    // List all sections for a subject
    public function index($subject_id)
    {
        $subject = Subject::with('sections')->findOrFail($subject_id);
        return view('section.index', compact('subject'));
    }

    // Show create form
    public function create($subject_id)
    {
        $subject = Subject::findOrFail($subject_id);
        return view('section.create', compact('subject'));
    }

    // Store section
    public function store(Request $request, $subject_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Section::create([
            'sec_title' => $request->name,
            'sec_description' => $request->description,
            'subject_id' => $subject_id,
        ]);

        return redirect()->route('sections.index', ['id' => $subject_id])
            ->with('success', 'Section added successfully!');
    }

    // Edit form
    public function edit($id)
    {
        $section = Section::findOrFail($id);
        $subjects = Subject::all();
        return view('section.edit', compact('section', 'subjects'));
    }


    // public function sectionform($subject_id = null)
    // {
    //     $subject = subject::all();
    //     return view('section.create', [
    //         'subject' => $subject,
    //         'selectedSubId' => $subject_id
    //     ]);
    // }


    // Update
    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);

        $section->update([
            'sec_title' => $request->name,
            'sec_description' => $request->description,
            'subject_id' => $request->subject_id,
        ]);

        return redirect()->route('sections.index', ['id' => $section->subject_id])
            ->with('success', 'Section updated successfully!');
    }

    // Delete
    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $subject_id = $section->subject_id;
        $section->delete();

        return redirect()->route('sections.index', ['id' => $subject_id])
            ->with('success', 'Section deleted successfully!');
    }
}
