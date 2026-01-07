@extends('layout.admin.master')

@section('admincontent')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Sessions List</h3>

        <a href="{{ route('sessions.create', ['section_id' => $section->section_id]) }}"
           class="btn btn-primary">
            <i class="fa fa-plus"></i> Add New Session
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($section->sessions->isEmpty())
        <div class="alert alert-warning">No sessions found.</div>
    @else
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Content</th>
                    <th width="160">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($section->sessions as $i => $session)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $session->titel }}</td>
                        <td class="text-capitalize">{{ $session->type }}</td>

                        {{-- CONTENT --}}
                        <td>
                            @if($session->type === 'video' && $session->video)
                                <a href="{{ $session->video }}" target="_blank"
                                   class="btn btn-sm btn-danger">
                                   ▶ Video
                                </a>

                            @elseif($session->type === 'pdf' && $session->pdf)
                                <a href="{{ asset('storage/'.$session->pdf) }}" target="_blank"
                                   class="btn btn-sm btn-secondary">
                                   📄 PDF
                                </a>

                            @elseif($session->type === 'task' && $session->task)
                                <a href="{{ asset('storage/'.$session->task) }}" target="_blank"
                                   class="btn btn-sm btn-info">
                                   📝 Task
                                </a>

                            @elseif($session->type === 'exam' && $session->exam)
                                <a href="{{ asset('storage/'.$session->exam) }}" target="_blank"
                                   class="btn btn-sm btn-warning">
                                   🧪 Exam
                                </a>
                            @else
                                <span class="text-muted">No content</span>
                            @endif
                        </td>

                        {{-- ACTION --}}
                        <td>
                            <a href="{{ route('sessions.edit', [$section->section_id, $session->id]) }}"
                               class="btn btn-sm btn-info">
                               Edit
                            </a>

                            <form action="{{ route('sessions.destroy', $session->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this session?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
