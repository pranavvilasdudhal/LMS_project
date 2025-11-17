@extends('layout.admin.master')

@section('admincontent')
<div class="container mt-5" id="react-app">
    <h1>Sessions List</h1>

    {{-- Success message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Add New Session Button --}}
    <a href="{{ route('sessions.create', ['section_id' => $section->section_id]) }}"
       class="btn btn-primary mb-3 float-end">
       <i class="fa fa-plus"></i>Add New Session
    </a>

    @if ($section->sessions->isEmpty())
        <p>No sessions found.</p>
    @else
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Video</th>
                    <th>PDF</th>
                    {{-- <th>Task</th>
                    <th>Exam</th> --}}
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $count = 1;
                @endphp
                
                @foreach ($section->sessions as $session)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $session->titel }}</td>
                        <td>{{ ucfirst($session->type) }}</td>

                        {{-- Video Column  --}}
                        <td>
                            @if ($session->type === 'video' && $session->video)
                                @php
                                    $youtube_id = null;

                                    if (str_contains($session->video, 'youtu.be')) {
                                        //short url
                                        $parts = explode('/', $session->video);
                                        $youtube_id = end($parts);
                                        $youtube_id = explode('?', $youtube_id)[0];
                                    } elseif (str_contains($session->video, 'youtube.com')) {
                                        //normal url
                                        preg_match('/v=([^\&\?\/]+)/', $session->video, $matches);
                                        $youtube_id = $matches[1] ?? null;
                                    }
                                @endphp

                                @if($youtube_id)
                                    <iframe width="150" height="100"
                                            src="https://www.youtube.com/embed/{{ $youtube_id }}"
                                            frameborder="0" allowfullscreen>
                                    </iframe>
                                @else
                                    <span class="text-muted">Invalid YouTube URL</span>
                                @endif

                            @else
                                <span class="text-muted">=</span>
                            @endif
                            
                        </td>

                        {{-- PDF Column --}}
                        <td>
                            @if($session->pdf)
                                <a href="{{ asset('storage/' . $session->pdf) }}" target="_blank">
                                    <i class="fa fa-file-pdf fa-2x text-danger"></i>
                                </a>

                            @else
                                <span class="text-muted">=</span>
                            @endif

                        </td>
                        {{-- <td>
                            @if($session->task)
                                <a href="{{ asset('storage/' . $session->task) }}" target="_blank">
                                    <i class="fa fa-file-pdf fa-2x text-grren"></i>
                                </a>
                            @else
                                <span class="text-muted">No Task</span>
                            @endif
                        </td>
                        <td>
                            @if($session->exam)
                                <a href="{{ asset('storage/' . $session->exam) }}" target="_blank">
                                    <i class="fa fa-list fa-2x text-list"></i>
                                </a>
                            @else
                                <span class="text-muted">No Exam</span>
                            @endif
                        </td> --}}
                       
                        
                        
                        {{-- Actions --}}
                        <td>
                            <a href="{{ route('sessions.edit', [$section->section_id, $session->id]) }}"
                               class="btn btn-info btn-sm"><i class="fa fa-edit"></i>Edit</a>

                            <form action="{{ route('sessions.destroy', $session->id) }}" method="POST"
                                  style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this session?')"><i class="fa fa-trash"></i>Delete
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
