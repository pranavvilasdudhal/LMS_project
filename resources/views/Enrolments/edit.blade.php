@extends('layout.admin.master')

@section('admincontent')
<div class="card shadow p-4">
    <h3>Edit Enrollment</h3>
    <form action="{{ route('enrolments.update', $enrolment->enrollment_id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Student Dropdown --}}
        <div class="form-group mb-3">
            <label>Student</label>
            <select name="student_id" class="form-control" required>
                @foreach ($students as $stu)
                    <option value="{{ $stu->student_id }}" 
                        @if ($stu->student_id == $enrolment->student_id) selected @endif>
                        {{ $stu->user->name ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Course Dropdown --}}
        <div class="form-group mb-3">
            <label>Course</label>
            <select name="course_id" id="courseSelect" class="form-control" required>
                <option value="">Select Course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->course_id }}" 
                        data-mrp="{{ $course->mrp }}" 
                        data-sell="{{ $course->sell_price }}"
                        @if ($course->course_id == $enrolment->course_id) selected @endif>
                        {{ $course->course_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- MRP --}}
        <div class="form-group mb-3">
            <label>MRP</label>
            <input type="number" name="mrp" id="mrp" class="form-control" 
                   value="{{ $enrolment->mrp }}" required readonly>
        </div>

        {{-- Selling Price --}}
        <div class="form-group mb-3">
            <label>Selling Price</label>
            <input type="number" name="sell_price" id="sell_price" class="form-control" 
                   value="{{ $enrolment->sell_price }}" required readonly>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('enrolments.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

{{-- Auto-update script --}}
<script>
    document.getElementById('courseSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const mrp = selectedOption.getAttribute('data-mrp');
        const sell = selectedOption.getAttribute('data-sell');

        document.getElementById('mrp').value = mrp ? mrp : '';
        document.getElementById('sell_price').value = sell ? sell : '';
    });
</script>
@endsection
