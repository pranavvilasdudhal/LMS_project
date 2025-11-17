@extends('layout.admin.master')

@section('admincontent')
<div class="card shadow p-4">
    <h3>Add Enrolment</h3>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('enrolments.store') }}" method="POST">
        @csrf

        {{-- Student Dropdown --}}
        <div class="form-group mb-3">
            <label>Student</label>
            <select name="student_id" class="form-control" required>
                <option value="">Select Student</option>
                @foreach ($students as $stu)
                    <option value="{{ $stu->student_id }}">{{ $stu->user->name ?? 'N/A' }}</option>
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
                        data-sell="{{ $course->sell_price }}">
                        {{ $course->course_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- MRP --}}
        <div class="form-group mb-3">
            <label>MRP</label>
            <input type="number" name="mrp" id="mrp" class="form-control" required readonly>
        </div>

        {{-- Selling Price --}}
        <div class="form-group mb-3">
            <label>Selling Price</label>
            <input type="number" name="sell_price" id="sell_price" class="form-control" required readonly>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('enrolments.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

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
