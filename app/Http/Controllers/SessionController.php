<?php

namespace App\Http\Controllers;

use App\Models\section;
use App\Models\session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * List all sessions for a section
     */
    public function index($section_id)
    {

        $section = section::with('sessions')->findOrFail($section_id);
        return view('session.index', compact('section'));
    }

    /**
     * Show create session form
     */
    public function create($section_id = null)
    {
        $sections = section::all();
        return view('session.create', compact('sections', 'section_id'));
    }

    /**
     * Store a new session
     */
    public function store(Request $request, $section_id)
    {
        $request->validate([
            'section_id' => 'required|exists:section,section_id',
            'titel' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'video' => 'nullable|string|url',
            'pdf' => 'nullable|file',
            'task' => 'nullable|file',
            'exam' => 'nullable|file',
        ]);

        // Prepare default data
        $data = [
            'section_id' => $section_id,
            'titel' => $request->titel,
            'type' => $request->type,
            'video' => $request->type === 'video' ? ($request->video ?? '') : '',
            'pdf' => '',
            'task' => '',
            'exam' => '',
        ];

        // Handle uploaded files
        $data['pdf'] = $request->hasFile('pdf') ? $request->file('pdf')->store('pdf', 'public') : '';
        $data['task'] = $request->hasFile('task') ? $request->file('task')->store('task', 'public') : '';
        $data['exam'] = $request->hasFile('exam') ? $request->file('exam')->store('exam', 'public') : '';

        session::create($data);

        return redirect()->route('sessions.index', ['section_id' => $section_id])
            ->with('success', 'Session created successfully.');
    }

    /**
     * Show edit session form
     */
    public function edit($section_id, $id)
    {
        $section = section::findOrFail($section_id);
        $session = session::findOrFail($id);

        return view('session.edit', compact('section', 'session'));
    }

    /**
     * Update session
     */
    public function update(Request $request, $section_id, $id)
    {
        $session = session::findOrFail($id);

        $request->validate([
            'titel' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'video' => 'nullable|string|url',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,wmv',
            'pdf' => 'nullable|file|mimes:pdf',
            'task' => 'nullable|file',
            'exam' => 'nullable|file',
        ]);

        $session->titel = $request->titel;
        $session->type  = $request->type;

        // Video: URL or Uploaded File
        if ($request->filled('video')) {
            $session->video = $request->video;
        } elseif ($request->hasFile('video_file')) {
            $session->video = $request->file('video_file')->store('video', 'public');
        }

        // PDF / Task / Exam
        if ($request->hasFile('pdf'))  $session->pdf  = $request->file('pdf')->store('pdf', 'public');
        if ($request->hasFile('task')) $session->task = $request->file('task')->store('task', 'public');
        if ($request->hasFile('exam')) $session->exam = $request->file('exam')->store('exam', 'public');

        $session->save();

        return redirect()->route('sessions.index', ['section_id' => $section_id])
            ->with('success', 'Session updated successfully.');
    }

    /**
     * Delete session
     */
    public function destroy($id)
    {
        $session = session::findOrFail($id);
        $section_id = $session->section_id;

        $session->delete();

        return redirect()->route('sessions.index', ['section_id' => $section_id])
            ->with('success', 'Session deleted successfully!');
    }
}
