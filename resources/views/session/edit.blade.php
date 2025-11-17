@extends('layout.admin.master')

@section('admincontent')
<div class="container mt-5">
    <div class="card p-4">
        <h3 class="mb-4 text-center">Edit Session</h3>

        <form action="{{ route('sessions.update', [$section->section_id, $session->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Title</label>
                <input type="text" name="titel" class="form-control" value="{{ $session->titel }}" required>
            </div>

            {{-- Type --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="">-- Select Type --</option>
                    <option value="video" {{ $session->type === 'video' ? 'selected' : '' }}>Video</option>
                    <option value="pdf" {{ $session->type === 'pdf' ? 'selected' : '' }}>PDF</option>
                    <option value="Task" {{ $session->type === 'Task' ? 'selected' : '' }}>Task</option>
                    <option value="Exam" {{ $session->type === 'Exam' ? 'selected' : '' }}>Exam</option>
                </select>
            </div>

            {{-- Video --}}
            <div class="mb-3" id="videoDiv" style="{{ $session->type === 'video' ? '' : 'display:none;' }}">
                <label class="form-label fw-bold">Video URL or File</label>

                @if ($session->video)
                    @php
                        $youtube_id = null;
                        if (str_contains($session->video, 'youtu.be')) {
                            $parts = explode('/', $session->video);
                            $youtube_id = end($parts);
                            $youtube_id = explode('?', $youtube_id)[0];
                        } elseif (str_contains($session->video, 'youtube.com')) {
                            preg_match('/v=([^\&\?\/]+)/', $session->video, $matches);
                            $youtube_id = $matches[1] ?? null;
                        }
                    @endphp

                    @if ($youtube_id)
                        <iframe width="200" height="113"
                                src="https://www.youtube.com/embed/{{ $youtube_id }}"
                                frameborder="0" allowfullscreen>
                        </iframe>
                    @else
                        <video width="200" controls class="mb-2">
                            <source src="{{ asset('storage/' . $session->video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif

                    <br>
                    <small>Upload new video or paste new YouTube URL to replace existing</small>
                @endif

                <input type="text" name="video" class="form-control mt-2" placeholder="Paste YouTube URL here">
                <input type="file" name="video_file" class="form-control mt-2">
            </div>

            {{-- PDF --}}
            <div class="mb-3" id="pdfDiv" style="{{ $session->type === 'pdf' ? '' : 'display:none;' }}">
                <label class="form-label fw-bold">PDF File</label>
                @if ($session->pdf)
                    <a href="{{ asset('storage/' . $session->pdf) }}" target="_blank" class="d-block mb-2">
                        <i class="fa fa-file-pdf fa-2x text-danger"></i> View Current PDF
                    </a>
                    <small>Upload new PDF to replace existing</small>
                @endif
                <input type="file" name="pdf" class="form-control mt-2">
            </div>

            {{-- Task --}}
            <div class="mb-3" id="TaskDiv" style="{{ $session->type === 'Task' ? '' : 'display:none;' }}">
                <label class="form-label fw-bold">Task File</label>
                @if ($session->task)
                    <a href="{{ asset('storage/' . $session->task) }}" target="_blank" class="d-block mb-2">
                        <i class="fa fa-file fa-2x text-primary"></i> View Current Task
                    </a>
                    <small>Upload new Task to replace existing</small>
                @endif
                <input type="file" name="task" class="form-control mt-2">
            </div>

            {{-- Exam --}}
            <div class="mb-3" id="ExamDiv" style="{{ $session->type === 'Exam' ? '' : 'display:none;' }}">
                <label class="form-label fw-bold">Exam File</label>
                @if ($session->exam)
                    <a href="{{ asset('storage/' . $session->exam) }}" target="_blank" class="d-block mb-2">
                        <i class="fa fa-file-alt fa-2x text-warning"></i> View Current Exam
                    </a>
                    <small>Upload new Exam to replace existing</small>
                @endif
                <input type="file" name="exam" class="form-control mt-2">
            </div>

            {{-- Submit / Cancel --}}
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success px-4">Update</button>
                <a href="{{ route('sessions.index', $section->section_id) }}" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>

{{-- JS for showing/hiding type-specific fields --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('type');
    const videoDiv = document.getElementById('videoDiv');
    const pdfDiv = document.getElementById('pdfDiv');
    const taskDiv = document.getElementById('TaskDiv');
    const examDiv = document.getElementById('ExamDiv');

    typeSelect.addEventListener('change', function () {
        videoDiv.style.display = this.value === 'video' ? 'block' : 'none';
        pdfDiv.style.display = this.value === 'pdf' ? 'block' : 'none';
        taskDiv.style.display = this.value === 'Task' ? 'block' : 'none';
        examDiv.style.display = this.value === 'Exam' ? 'block' : 'none';
    });
});
</script>
@endsection
