@extends('layout.admin.master')
@section('admincontent')

<div class="card shadow-sm p-4">
    <h4 class="mb-4">📄 PDF Review List</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Session</th>
                <th>Status</th>
                <th>Uploaded At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
             @php
                    $count = 1;
                @endphp
            @forelse($pdfs as $pdf)
                <tr>
                    <td>{{ $count++ }}</td>

                   <td>{{ auth()->user()->name }}</td>


                    <td>
                        {{ $pdf->session->titel ?? 'Session '.$pdf->session_id }}
                    </td>

                    <!-- ✅ STATUS COLUMN -->
                    <td>
                        @if($pdf->approved)
                            <span class="badge bg-success">
                                Approved
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>
                        @endif
                    </td>

                    <td>
                        {{ $pdf->created_at->format('d M Y') }}
                    </td>

                    <td>
                        <a href="{{ route('admin.pdf.review', $pdf->id) }}"
                           class="btn btn-sm btn-primary">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        No PDFs found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
