@extends('layout.admin.master')
@section('admincontent')
<div class="container-fluid">
    <div class="row g-4">

        <!-- 👤 STUDENT PROFILE -->
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4">

                {{-- Student Photo --}}
                @if ($pdf->user && $pdf->user->photo)
                    <img src="{{ asset('storage/' . $pdf->user->photo) }}"
                        class="rounded-circle mb-3 img-thumbnail" width="110" height="110">
                @else
                    <img src="{{ asset('default-avatar.png') }}"
                        class="rounded-circle mb-3 img-thumbnail" width="110" height="110">
                @endif

                <h5 class="mb-0">{{ $pdf->user->name }}</h5>
                <small class="text-muted">{{ $pdf->user->email }}</small>

                <span class="badge bg-info mt-3">
                    PDF Uploaded Student
                </span>

            </div>
        </div>

        <!-- 📚 COURSE + PDF DETAILS -->
        <div class="col-md-8">
            <div class="card shadow-sm p-3">

                <!-- TABS -->
                <ul class="nav nav-pills mb-3 align-items-center gap-2">
                    <li class="nav-item">
                        <span class="nav-link active">Courses</span>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">Orders</span>
                    </li>

                    <a href="{{ asset('storage/' . $pdf->pdf) }}" target="_blank"
                        class="btn btn-outline-primary btn-sm ms-auto">
                        View PDF
                    </a>
                </ul>

                <!-- COURSE INFO -->
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $pdf->course->course_name ?? 'N/A' }}</td>
                            <td><span class="badge bg-success">Paid</span></td>
                        </tr>
                    </tbody>
                </table>

                <p><b>Subject:</b> {{ $pdf->subject->subject_name ?? 'N/A' }}</p>
                <p><b>Section:</b> {{ $pdf->section->sec_title ?? 'N/A' }}</p>
                <p><b>Session:</b> {{ $pdf->session->titel ?? 'N/A' }}</p>

                <hr>

                <!-- PDF VIEW -->
                <h6>📄 Uploaded PDF</h6>
                <a href="{{ asset('storage/' . $pdf->pdf) }}" target="_blank"
                    class="btn btn-outline-primary btn-sm">
                    View / Download PDF
                </a>

                <!-- STATUS -->
                <p class="mt-3">
                    <b>Status:</b>

                    @if ($pdf->approved)
                        <span class="badge bg-success">Approved</span>
                    @elseif ($pdf->rejected)
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </p>

                {{-- Rejection Reason --}}
                @if ($pdf->rejected)
                    <div class="alert alert-danger">
                        <b>Rejection Reason:</b><br>
                        {{ $pdf->reject_reason }}
                    </div>
                @endif

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- ACTION BUTTONS -->
                @if (!$pdf->approved && !$pdf->rejected)

                    {{-- APPROVE --}}
                    <form method="POST" action="{{ route('admin.pdf.approve', $pdf->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-success mt-2">
                            ✅ Approve PDF
                        </button>
                    </form>

                    {{-- REJECT BUTTON --}}
                    <button class="btn btn-danger mt-2" data-bs-toggle="collapse" data-bs-target="#rejectBox">
                        ❌ Reject PDF
                    </button>

                    {{-- REJECT FORM --}}
                    <div class="collapse mt-3" id="rejectBox">
                        <form method="POST" action="{{ route('admin.pdf.reject', $pdf->id) }}">
                            @csrf

                            <div class="mb-2">
                                <textarea name="reject_reason" class="form-control" rows="3"
                                    placeholder="Enter reason for rejection..." required></textarea>
                            </div>

                            <button class="btn btn-danger">
                                Submit Rejection
                            </button>
                        </form>
                    </div>

                @endif

            </div>
        </div>

    </div>
</div>
@endsection
