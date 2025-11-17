@extends('layout.admin.master')
@section('admincontent')
<div class="container" style="padding: 40px">
    <h2>Subject List</h2>

  
    <a href="{{ route('subject.create') }}" class="btn btn-primary mb-3 float-end"><i class="fa fa-plus"></i>Add Subject</a>

    
    
    <form method="GET" action="{{ route('subject.index') }}" class="mb-3">
       
    </form>

   
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

 
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                {{-- <th>Description</th> --}}
                <th>Image</th>
                <th>Section</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
            @endphp
            @forelse ($subject as $sub)
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $sub->subject_name }}</td>
                    {{-- <td>{{ $sub->subject_description }}</td> --}}
                    <td>
                        @if ($sub->subject_image)
                            <img src="{{ asset('storage/profiles/' . $sub->subject_image) }}" alt="subject Photo" width="60" height="60">
                        @else
                            <img src="{{ asset('default-avatar.png') }}" alt="Default Photo" width="60" height="60">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('sections.index', $sub->subject_id) }}" class="btn btn-info btn-sm">Section<i class="fa fa-arrow-right"></i></a>
                    </td>
                    <td>
                       
                        <a href="{{ route('subject.edit', $sub->subject_id) }}" class="btn btn-info btn-sm mb-1"><i class="fa fa-edit"></i>Edit</a>

                       
                        <form action="{{ route('subject.destroy', $sub->subject_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i>Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No subjects found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
