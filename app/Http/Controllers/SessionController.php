<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    // ================= LIST =================
    public function index($section_id)
    {
        $section = Section::with('sessions')->findOrFail($section_id);
        return view('session.index', compact('section'));
    }

    // ================= CREATE =================
    public function create($section_id = null)
    {
        $sections = Section::all();
        return view('session.create', compact('sections', 'section_id'));
    }

    // ================= STORE =================
    public function store(Request $request, $section_id)
    {
        $request->validate([
            'titel' => 'required|string|max:255',
            'type'  => 'required|in:video,pdf,task,exam',

            'video' => 'nullable|url',
            'pdf'   => 'nullable|file|mimes:pdf',
            'task'  => 'nullable|file',
            'exam'  => 'nullable|file',
        ]);

  
        $sectionId = $section_id;

 
        $isFirstSession = !Session::where('section_id', $sectionId)->exists();

        $data = [
            'section_id' => $sectionId,
            'titel'      => $request->titel,
            'type'       => $request->type,
            'video'      => '',
            'pdf'        => '',
            'task'       => '',
            'exam'       => '',
            'unlocked'   => $isFirstSession ? 1 : 0, // ⭐ MAIN LOGIC
        ];

        if ($request->type === 'video') {
            $data['video'] = $request->video ?? '';
        }

        if ($request->type === 'pdf' && $request->hasFile('pdf')) {
            $data['pdf'] = $request->file('pdf')->store('pdf', 'public');
        }

        if ($request->type === 'task' && $request->hasFile('task')) {
            $data['task'] = $request->file('task')->store('task', 'public');
        }

        if ($request->type === 'exam' && $request->hasFile('exam')) {
            $data['exam'] = $request->file('exam')->store('exam', 'public');
        }

        Session::create($data);

        return redirect()
            ->route('sessions.index', ['section_id' => $sectionId])
            ->with('success', 'Session created successfully!');
    }


    // ================= EDIT =================
    public function edit($section_id, $id)
    {
        $section = Section::findOrFail($section_id);
        $session = Session::findOrFail($id);

        return view('session.edit', compact('section', 'session'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $section_id, $id)
    {
        $session = Session::findOrFail($id);

        $request->validate([
            'titel' => 'required|string|max:255',
            'type'  => 'required|in:video,pdf,task,exam',

            'video' => 'nullable|url',
            'pdf'   => 'nullable|file|mimes:pdf',
            'task'  => 'nullable|file',
            'exam'  => 'nullable|file',
        ]);

        $session->titel = $request->titel;
        $session->type  = $request->type;

        // reset all
        $session->video = '';
        $session->pdf   = '';
        $session->task  = '';
        $session->exam  = '';

        if ($request->type === 'video') {
            $session->video = $request->video ?? '';
        }

        if ($request->type === 'pdf' && $request->hasFile('pdf')) {
            $session->pdf = $request->file('pdf')->store('pdf', 'public');
        }

        if ($request->type === 'task' && $request->hasFile('task')) {
            $session->task = $request->file('task')->store('task', 'public');
        }

        if ($request->type === 'exam' && $request->hasFile('exam')) {
            $session->exam = $request->file('exam')->store('exam', 'public');
        }

     
        $session->save();

        return redirect()
            ->route('sessions.index', ['section_id' => $section_id])
            ->with('success', 'Session updated successfully!');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $session = Session::findOrFail($id);
        $section_id = $session->section_id;

        $session->delete();

        return redirect()
            ->route('sessions.index', ['section_id' => $section_id])
            ->with('success', 'Session deleted successfully!');
    }
}
