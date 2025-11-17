@extends('layout.admin.master')
@section('title', 'Student Registration')
@section('admincontent')
    <div class="container mt-5" style="padding: 1px;">
        <div class="card shadow-lg p-4">
            <h3 class="mb-4 text-center">Edit Student</h3>

            {{-- Success / Error Messages --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Edit Form --}}
            <form action="{{ route('studentupdate', $student->student_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST') {{-- or use PATCH if you change the route --}}

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="student_name" class="form-control" value="{{ $student->user->name }}"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="student_email" class="form-control" value="{{ $student->user->email }}"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="student_phone" class="form-control"
                            value="{{ $student->student_phone }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <input type="text" name="student_address" class="form-control"
                            value="{{ $student->student_address }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="password" name="student_password" class="form-control"
                            placeholder="Enter new password">
                        <small class="text-muted">Leave blank if you don’t want to change</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Confirm Password</label>
                        <input type="password" name="student_password_confirmation" class="form-control"
                            placeholder="Confirm password">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Photo</label>
                        <input type="file" name="photo" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="student" {{ $student->user->role == 'student' ? 'selected' : '' }}>Student
                            </option>
                            <option value="teacher" {{ $student->user->role == 'teacher' ? 'selected' : '' }}>Teacher
                            </option>
                            <option value="admin" {{ $student->user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success px-4">Update</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>

        </div>
    </div>
@endsection
