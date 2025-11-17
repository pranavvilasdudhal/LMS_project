@extends('layout.admin.master')
@section('title', 'Edit Course')
@section('admincontent')
    <div class="card">
        <div class="card-header">
            <h3>Edit Course</h3>
            {{-- <div class="card-header-right">
            <a href="{{ route('courselist') }}" class="btn btn-primary btn-round">Course edit</a>
        </div> --}}
        </div>

        <div class="shadow-lg p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('courseupdate', $course->course_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- @method('PUT') --}}

                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>Course Name</label>
                        <input type="text" name="course_name" class="form-control" value="{{ $course->course_name }}"
                            required>
                    </div>

                    <div class="col-sm-6">
                        <label>Course Image</label><br>
                        @if ($course->course_image)
                            <img src="{{ asset('storage/course_images/' . $course->course_image) }}" width="80"
                                class="mb-2">
                        @endif
                        <input type="file" name="course_image" class="form-control-file">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>MRP</label>
                        <input type="number" step="0.01" name="mrp" class="form-control"
                            value="{{ $course->mrp }}">
                    </div>
                    <div class="col-sm-6">
                        <label>Selling Price</label>
                        <input type="number" step="0.01" name="sell_price" class="form-control"
                            value="{{ $course->sell_price }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Course Description</label>
                    <textarea name="course_description" rows="5" class="form-control">{{ $course->course_description }}</textarea>
                </div>


                <div class="row g-2">
                    @foreach ($subjects as $subject)
                        <div class="col-md-4 col-sm-6">
                            <div class="form-check p-3 border rounded shadow-sm hover-effect">
                                <input type="checkbox" name="subject[]" value="{{ $subject->subject_id }}"
                                    class="form-check-input me-2" id="subject{{ $subject->subject_id }}"
                                    {{ isset($course) && $course->subject->contains('subject_id', $subject->subject_id) ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="subject{{ $subject->subject_id }}">
                                    {{ $subject->subject_name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center">
                    <button class="btn btn-success">Update</button>
                    <a href="{{ route('courselist') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
