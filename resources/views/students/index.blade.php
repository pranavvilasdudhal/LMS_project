@extends('layout.admin.master')
@section('admincontent')
<div class="container" style="padding: 40px">
    <h2>Student List</h2>

    {{-- Add Student Button --}}
    <a href="{{ route('students.create') }}" class="btn btn-primary mb-3 float-end">
        <i class="fa fa-plus"></i> Add Student
    </a>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Student Table --}}
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Photo</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @php
                $count = 1;
            @endphp
            @forelse ($students as $student)
                <tr>
                    <td>{{ $count++}}</td>
                    <td>{{ $student->user->name }}</td>
                    <td>{{ $student->user->email }}</td>

                    <td>
                        @if ($student->user && $student->user->photo)
                            <img src="{{ asset('storage/' . $student->user->photo) }}" 
                                 alt="Student Photo" width="60" height="60" 
                                 class="img-thumbnail">
                        @else
                            <img src="{{ asset('default-avatar.png') }}" 
                                 alt="Default Photo" width="60" height="60" 
                                 class="img-thumbnail">
                        @endif
                    </td>

                    <td>{{ $student->user->role ?? '-' }}</td>

                    <td>
                        @if ($student->user)
                            <form action="{{ route('student.toggle', $student->student_id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm {{ $student->user->is_active ? 'btn-success' : 'btn-danger' }}">
                                    {{ $student->user->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        @else
                            <span class="text-muted">No user linked</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('studentedit', $student->student_id) }}" 
                           class="btn btn-info btn-sm mb-1">
                           <i class="fa fa-edit"></i> Edit
                        </a>

                        <form action="{{ route('students.destroy', $student->student_id) }}" 
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Are you sure?')">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No students found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
