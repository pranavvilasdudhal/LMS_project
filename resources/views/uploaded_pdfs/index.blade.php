@extends('layout.admin.master')
@section('admincontent')

<div class="container mt-5">
    <h3 class="mb-4">📄 Uploaded PDFs (Admin Panel)</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Course</th>
                <th>Section</th>
                <th>Session</th>
                <th>PDF</th>
                <th>Status</th>
                <th width="220">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pdfs as $pdf)
                <tr>
                    <td>{{ $pdf->id }}</td>
                    <td>{{ $pdf->user->name ?? 'N/A' }}</td>
                    <td>{{ $pdf->course_id }}</td>
                    <td>{{ $pdf->section_id }}</td>
                    <td>{{ $pdf->session_id }}</td>

                    <td>
                        <a href="{{ asset('storage/'.$pdf->pdf) }}" target="_blank">
                            View PDF
                        </a>
                    </td>

                    <td>
                        @if($pdf->approved)
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>

                    <td>
                        @if(!$pdf->approved)
                            <form action="{{ route('admin.uploaded_pdfs.approve',$pdf->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm">
                                    Approve
                                </button>
                            </form>

                            <form action="{{ route('admin.uploaded_pdfs.reject',$pdf->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Reject this PDF?')">
                                @csrf
                                <button class="btn btn-danger btn-sm">
                                    Reject
                                </button>
                            </form>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No PDFs Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection