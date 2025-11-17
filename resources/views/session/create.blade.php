@extends('layout.admin.master')

@section('admincontent')
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h3 class="mb-4 text-center">Add New Session</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sessions.store', ['section_id' => $section_id ?? null]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Section --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Select Section</label>
                        <select name="section_id" class="form-control" required>
                            <option value="">-- Select Section --</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->section_id }}"
                                    {{ isset($section_id) && $section_id == $section->section_id ? 'selected' : '' }}>
                                    {{ $section->sec_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Title --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="titel" class="form-control" placeholder="Enter session title"
                            required>
                    </div>

                    {{-- Type --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            <option value="video">Video</option>
                            <option value="pdf">PDF</option>
                            <option value="Task">Task</option>
                            <option value="Exam">Exam</option>
                        </select>
                    </div>

                    {{-- Video --}}
                    <div class="col-md-6 mb-3" id="videoDiv" style="display:none;">
                        <label class="form-label fw-bold">Video URL</label>
                        <input type="url" name="video" class="form-control">
                    </div>

                    {{-- PDF --}}
                    <div class="col-md-6 mb-3" id="pdfDiv" style="display:none;">
                        <label class="form-label fw-bold">PDF File</label>
                        <input type="file" name="pdf" class="form-control" accept=".pdf">
                    </div>

                    {{-- Task --}}
                    <div class="col-md-6 mb-3" id="TaskDiv" style="display:none;">
                        <label class="form-label fw-bold">Task File</label>
                        <input type="file" name="task" class="form-control">
                    </div>

                    {{-- Exam --}}
                    <div class="col-md-6 mb-3" id="ExamDiv" style="display:none;">
                        <label class="form-label fw-bold">Exam File</label>
                        <input type="file" name="exam" class="form-control">
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success px-4"><i class="fa fa-save"></i> Save</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary px-4"><i class="fa fa-arrow-left"></i>
                        Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const videoDiv = document.getElementById('videoDiv');
            const pdfDiv = document.getElementById('pdfDiv');
            const taskDiv = document.getElementById('TaskDiv');
            const examDiv = document.getElementById('ExamDiv');

            typeSelect.addEventListener('change', function() {
                videoDiv.style.display = pdfDiv.style.display = taskDiv.style.display = examDiv.style
                    .display = 'none';
                if (this.value === 'video') videoDiv.style.display = 'block';
                else if (this.value === 'pdf') pdfDiv.style.display = 'block';
                else if (this.value === 'Task') taskDiv.style.display = 'block';
                else if (this.value === 'Exam') examDiv.style.display = 'block';
            });
        });
    </script>
@endsection
