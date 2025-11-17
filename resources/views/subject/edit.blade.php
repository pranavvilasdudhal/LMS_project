@extends('layout.admin.master')
@section('title', 'Student Registration')
@section('admincontent')
    <div class="container mt-5" style="padding: 1px">
        <div class="card shadow-lg p-4">
            <h3 class="mb-4 text-center">subject</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('subject.update',$subject->subject_id) }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ $subject->subject_name }}" class="form-control" required >
                </div>

                <div class="mb-3">
                    <label class="form-label">description</label>
                    <input class="form-control" name="description" value="{{ $subject->subject_description }}" class="form-control" required>
                </div>
                
               
                <div class="form-group">
                    <label for="exampleFormControlFile1">Subject Image</label>
                    @if($subject->subject_image)
                    <img src="{{ asset('storage/profiles/'.$subject->subject_image) }}"
                         class="rounded-circle mb-2" width="80" height="80">
                @endif
                    <input type="file" name="image"  class="form-control-file" id="exampleFormControlFile1" />
                </div>

                <div class="text-center">
                    <button class="btn btn-success px-4">Submit</button>
                    <a href="{{ route('subject.index') }}"class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
