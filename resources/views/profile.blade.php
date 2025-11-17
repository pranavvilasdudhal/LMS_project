@extends('layout.admin.master')

@section('admincontent')
<div class="container-fluid"style="padding-left:10px;">
    <div class="card shadow-lg p-4">
        <div class="text-center">
            @if($user->photo)
                <img src="{{ asset('storage/' . Auth::user()->photo) }}" 
                     class="rounded-circle mb-3" 
                     width="120" height="120" alt="Profile Image">
            @else
                <img src="https://via.placeholder.com/120" 
                     class="rounded-circle mb-3" 
                     alt="Default Profile">
            @endif

            <h3>{{ $user->name }}</h3>
            <p class="text-muted">{{ $user->email }}</p>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $user->address ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Joined:</strong> {{ $user->created_at->format('d M Y') }}</p>
                <p><strong>Updated:</strong> {{ $user->updated_at->diffForHumans() }}</p>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>
</div>
@endsection

