@extends('layout.admin.master')

@section('admincontent')
<div class="card shadow p-4">
    <h3>Enrollment List</h3>

    <a href="{{ route('enrolments.create') }}" class="btn btn-primary mb-3" style="margin-left:850px">
        <i class="fa fa-plus"></i> Add Enrolment
    </a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Course</th>
                <th>MRP</th>
                <th>Sell Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
             @php
                $count = 1;
            @endphp
            @foreach ($enrolments as $enr)
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $enr->student->user->name ?? 'N/A' }}</td>
                    <td>{{ $enr->course->course_name ?? 'N/A' }}</td>
                    <td>{{ $enr->mrp }}</td>
                    <td>{{ $enr->sell_price }}</td>

                    <td>
                        @if($enr->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($enr->status == 'processing')
                            <span class="badge bg-primary">Processing</span>
                        @elseif($enr->status == 'purchased')
                            <span class="badge bg-success">Purchased</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('enrolments.edit', $enr->enrollment_id) }}" 
                            class="btn btn-primary btn-sm">Edit</a>

                        <form action="{{ route('enrolments.destroy', $enr->enrollment_id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this record?')">
                                Delete
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
