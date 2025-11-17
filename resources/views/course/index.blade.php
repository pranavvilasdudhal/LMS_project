@extends('layout.admin.master')
@section('admincontent')
    <div class="container" style="padding: 40px">
        <h2>Courses List</h2>
        <a href="{{ route('courseadd') }}" class="btn btn-primary mb-3" style="margin-left:850px">
            <i class="fa fa-plus"></i>Add Course
        </a>

        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Course Image</th>
                <th>Course Name</th>
                <th>MRP</th>
                <th>Sell Price</th>
                <th>Subjects</th>
                <th>Edit/Delete</th>
            </tr>

            @foreach ($courses as $course)
                <tr>
                    <td>{{ $course->course_id }}</td>
                    <td>
                        @if ($course->course_image)
                            <img src="{{ asset('storage/course_images/' . $course->course_image) }}" alt="Course Image"
                                width="80" height="80">
                        @else
                            <img src="{{ asset('default-avatar.png') }}" alt="Default Image" width="80" height="80">
                        @endif
                    </td>
                    <td>{{ $course->course_name }}</td>
                    <td>{{ $course->mrp ?? '-' }}</td>
                    <td>{{ $course->sell_price ?? '-' }}</td>
                    <td>
                        @foreach ($course->subject as $subject)
                            <span class="badge bg-info text-dark">{{ $subject->subject_name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('courseedit', $course->course_id) }}" class="btn btn-sm btn-info"><i
                                class="fa fa-edit"></i> Edit</a>
                        <form action="{{ route('coursedelete', $course->course_id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i
                                    class="fa fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection
