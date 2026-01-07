@extends('layout.admin.master')

@section('admincontent')
<div class="container" style="padding:40px;">

    <h2 class="mb-4">All Certificates</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Course</th>
                <th>Preview</th>
                <th>Issued On</th>
                <th>Download</th>
            </tr>
        </thead>

        <tbody>
            @php $i = 1; @endphp

            @foreach($certificates as $cert)
                <tr>
                    <td>{{ $i++ }}</td>

                    <td>{{ $cert->student->student_name }}</td>

                    <td>{{ $cert->course->course_name }}</td>

                    <td>
                        <img src="{{ asset($cert->png_path) }}" style="width:120px; border-radius:6px;">
                    </td>

                    <td>{{ $cert->created_at->format('d M, Y') }}</td>

                    <td>
                        <a href="{{ asset($cert->pdf_path) }}" class="btn btn-danger btn-sm" download>
                            Download PDF
                        </a>
                        <a href="{{ asset($cert->png_path) }}" class="btn btn-primary btn-sm" download>
                            Download PNG
                        </a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

</div>
@endsection
