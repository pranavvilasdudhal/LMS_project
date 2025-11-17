@extends('layout.admin.master')
@section('admincontent')
<div class="container" style="padding: 40px">
    <h2>Sections for: {{ $subject->subject_name }}</h2>
    <a href="{{ route('sections.create', $subject->subject_id) }}" class="btn btn-primary mb-3"style="margin-left:850px"><i class="fa fa-plus"></i>Add Section</a>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <td>subject name</td>
            {{-- <th>Description</th> --}}
            <th>Session</th>
            <th>Edit/Delete</th>
        </tr>
        @php
            $count = 1;
        @endphp
        @foreach ($subject->sections as $sec)
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $sec->sec_title }}</td>
                <td>{{ $subject->subject_name }}</td>
                {{-- <td>{{ $sec->sec_description }}</td> --}}
                
                <td>
                    <a href="{{ route('sessions.index', $sec->section_id) }}" class="btn btn-sm btn-info">Sessions<i class="fa fa-arrow-right"></i></a>
                </td>
                
                <td>
                    <a href="{{ route('sections.edit', $sec->section_id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i>Edit</a>
                    <form action="{{ route('sections.delete', $sec->section_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i>Delete</button>
                    </form>
                </td>

               
            </tr>
        @endforeach
    </table>
</div>
@endsection
