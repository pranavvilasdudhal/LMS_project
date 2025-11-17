@extends('layout.admin.master')
@section('title', 'Course Add')
@section('admincontent')
    <div class="card">
        <div class="card-header">
            <h3>Course Add</h3>
            <div class="card-header-right">
                <a href="{{ route('courselist') }}" class="btn btn-primary btn-round">Course List</a>
            </div>
        </div>

        <div class="shadow-lg p-4">
            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('coursestore') }}" method="POST" enctype="multipart/form-data">
                @csrf


                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="form-label">Course Name</label>
                        <input type="text" name="course_name" class="form-control" required>
                    </div>

                    <div class="col-sm-6">
                        <label for="course_image">Course Image</label>
                        <input type="file" name="course_image" class="form-control-file" id="course_image" required />
                    </div>
                </div>

                <div class="form-group">
                    <label>Course Description</label>
                    <textarea name="course_description" rows="5" class="form-control" required></textarea>
                </div>


                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>MRP</label>
                        <input type="number" step="0.01" name="mrp" class="form-control" placeholder="Enter MRP">
                    </div>
                    <div class="col-sm-6">
                        <label>Selling Price</label>
                        <input type="number" step="0.01" name="sell_price" class="form-control"
                            placeholder="Enter Selling Price">
                    </div>
                </div>

                {{-- ✅ Corrected Subject Select --}}
                <div class="form-group mb-3">
                    <label class="form-label fw-bold d-block mb-2">Select Subjects</label>

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

                    <small class="form-text text-muted mt-2">
                        You can select one or more subjects.
                    </small>
                </div>


                <div class="text-center">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>Submit</button>
                    <a href="{{ route('courselist', $subject->subject_id) }}" class="btn btn-secondary"><i
                            class="fa fa-arrow-left"></i>Cancel</a>
                </div>

            </form>
        </div>
    </div>
@endsection
