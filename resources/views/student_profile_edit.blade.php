@extends('layout.student.master')

@section('admincontent')
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h3 class="mb-4 text-center fw-bold text-primary">Edit Profile</h3>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{-- Name --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="name"
                           value="{{ old('name', $user->name) }}"
                           class="form-control" required>
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email Address</label>
                    <input type="email" name="email"
                           value="{{ old('email', $user->email) }}"
                           class="form-control" required>
                </div>
            </div>

            <div class="row">
                {{-- Phone --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" name="student_phone"
                           value="{{ old('phone', $user->phone) }}"
                           class="form-control" placeholder="Enter phone number">
                </div>

                {{-- Address --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Address</label>
                    <input type="text" name="student_address"
                           value="{{ old('address', $user->address) }}"
                           class="form-control" placeholder="Enter your address">
                </div>
            </div>

            <div class="row">
                {{-- Password --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter new password">
                    <small class="text-muted">Leave blank if you don't want to change it</small>
                </div>

                {{-- Confirm Password --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                </div>
            </div>

            {{-- Profile Image --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Profile Image</label><br>
                @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}"
                         alt="Profile Photo"
                         class="rounded-circle mb-2 border shadow-sm"
                         width="90" height="90">
                @else
                    <img src="{{ asset('default-avatar.png') }}"
                         alt="Default Photo"
                         class="rounded-circle mb-2 border shadow-sm"
                         width="90" height="90">
                @endif
                <input type="file" name="photo" class="form-control mt-2">
            </div>

            {{-- Buttons --}}
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success px-4 me-2">
                    <i class="fa fa-save"></i> Update Profile
                </button>
                <a href="{{ route('profile') }}" class="btn btn-secondary px-4">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
