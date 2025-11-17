@extends('layout.admin.master')

@section('title', 'Add Student')
@section('admincontent')

<div class="container mt-4" style="padding: 60px">
    <h2>Add Student</h2>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Error Message --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Full Name</label>
                <input type="text" name="student_name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="student_email" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Phone</label>
                <input type="text" name="student_phone" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Address</label>
                <input type="text" name="student_address" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="student_password" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Confirm Password</label>
                <input type="password" name="student_password_confirmation" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Photo</label>
                <input type="file" name="photo" class="form-control">
            </div>

            {{-- <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Role</label>
                <select name="role" class="form-control" required>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="admin">Admin</option>
                </select>
            </div> --}}
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success px-4">Save</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary px-4">Cancel</a>
        </div>
    </form>
</div>

@endsection
