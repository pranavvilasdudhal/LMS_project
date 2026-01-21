@extends('layout.admin.master')

@php 
    use App\Http\Controllers\Api\ProgressController; 
@endphp

@section('admincontent')

<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Course Progress Dashboard</h1>
            <p class="text-muted mb-0">Track student learning progress in real-time</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary">
                <i class="fas fa-download me-2"></i>Export Report
            </button>
            <button class="btn btn-primary">
                <i class="fas fa-chart-line me-2"></i>Analytics
            </button>
        </div>
    </div>

    <!-- Student Info Card -->
    <div class="card border-0 shadow-lg mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body text-white p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-2">{{ $student->student_name }}</h4>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ $student->student_email }}</p>
                    <p class="mb-0"><i class="fas fa-book me-2"></i>Course: {{ $course->course_name }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="bg-white rounded-3 p-3 d-inline-block">
                        <h3 class="text-primary mb-0" id="overallProgress">0%</h3>
                        <small class="text-muted">Overall Progress</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary border-0 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Subjects
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $course->subject->count() ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success border-0 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Total Sessions
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="totalSessions">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info border-0 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Completed Sessions
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="completedSessions">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning border-0 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Unlocked Sessions
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="unlockedSessions">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-unlock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Progress Section -->
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-chart-bar me-2"></i>Subjects Progress Overview
            </h5>
        </div>
        <div class="card-body p-0">
            @if($course->subject && $course->subject->count() > 0)
                <div class="accordion" id="subjectsAccordion">
                    @php
                        $totalProgress = 0;
                        $subjectCount = 0;
                    @endphp
                    
                    @foreach($course->subject as $subject)
                        @php
                            $subjectProgress = ProgressController::subjectProgress(
                                $student->student_id,
                                $subject->subject_id
                            );
                            
                            $totalSessions = $subject->sections->flatMap->sessions->count();
                            $completedSessions = $subject->sections->flatMap->sessions->filter(function($s) use ($student){
                                return ProgressController::sessionProgress($student->student_id, $s->id) == 100;
                            })->count();
                            
                            $unlocked = $subject->sections->flatMap->sessions->where('unlocked',1)->count();
                            $locked = $totalSessions - $unlocked;
                            
                            $totalProgress += $subjectProgress;
                            $subjectCount++;
                        @endphp

                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                <button class="accordion-button collapsed p-4" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse{{ $loop->index }}" 
                                        aria-expanded="false" 
                                        aria-controls="collapse{{ $loop->index }}">
                                    <div class="d-flex w-100 align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="subject-icon me-3">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                    <i class="fas fa-book text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $subject->subject_name }}</h6>
                                                <small class="text-muted">{{ $totalSessions }} sessions • {{ $subject->sections->count() }} sections</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-4">
                                            <div class="text-end">
                                                <div class="fw-bold fs-5">{{ $subjectProgress }}%</div>
                                                <div class="progress" style="width: 120px; height: 6px;">
                                                    <div class="progress-bar {{ $subjectProgress == 100 ? 'bg-success' : 'bg-primary' }}" 
                                                         style="width: {{ $subjectProgress }}%"></div>
                                                </div>
                                            </div>
                                            <span class="badge rounded-pill {{ $subjectProgress == 100 ? 'bg-success' : ($subjectProgress > 50 ? 'bg-info' : 'bg-warning') }}">
                                                @if($subjectProgress == 100)
                                                    Completed
                                                @elseif($subjectProgress > 0)
                                                    In Progress
                                                @else
                                                    Not Started
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            
                            <div id="collapse{{ $loop->index }}" 
                                 class="accordion-collapse collapse" 
                                 aria-labelledby="heading{{ $loop->index }}" 
                                 data-bs-parent="#subjectsAccordion">
                                <div class="accordion-body p-4 bg-light">
                                    <!-- Session Details Table -->
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Section</th>
                                                    <th>Session Title</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Progress</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($subject->sections as $section)
                                                    @foreach($section->sessions as $sess)
                                                        @php
                                                            $sessPercent = ProgressController::sessionProgress(
                                                                $student->student_id,
                                                                $sess->id
                                                            );
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <span class="badge bg-secondary">{{ $section->sec_title }}</span>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    @if($sess->type == 'video')
                                                                        <i class="fas fa-video text-danger me-2"></i>
                                                                    @elseif($sess->type == 'quiz')
                                                                        <i class="fas fa-question-circle text-warning me-2"></i>
                                                                    @elseif($sess->type == 'assignment')
                                                                        <i class="fas fa-tasks text-success me-2"></i>
                                                                    @else
                                                                        <i class="fas fa-file-alt text-primary me-2"></i>
                                                                    @endif
                                                                    {{ $sess->titel }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light text-dark">
                                                                    {{ ucfirst($sess->type) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @if($sess->unlocked)
                                                                    <span class="badge bg-success">
                                                                        <i class="fas fa-unlock me-1"></i>Unlocked
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-danger">
                                                                        <i class="fas fa-lock me-1"></i>Locked
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                                        <div class="progress-bar {{ $sessPercent == 100 ? 'bg-success' : 'bg-info' }}" 
                                                                             style="width: {{ $sessPercent }}%"></div>
                                                                    </div>
                                                                    <small class="text-muted">{{ $sessPercent }}%</small>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @php
                        $overallProgress = $subjectCount > 0 ? round($totalProgress / $subjectCount, 1) : 0;
                    @endphp
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.getElementById('overallProgress').textContent = '{{ $overallProgress }}%';
                        });
                    </script>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-book fa-3x text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">No Subjects Found</h4>
                    <p class="text-muted mb-0">No subjects have been assigned to this course yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Progress Visualization -->

</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate totals
    let totalSessions = 0;
    let completedSessions = 0;
    let unlockedSessions = 0;
    
    @foreach($course->subject as $subject)
        @php
            $totalSessions = $subject->sections->flatMap->sessions->count();
            $completedSessions = $subject->sections->flatMap->sessions->filter(function($s) use ($student){
                return ProgressController::sessionProgress($student->student_id, $s->id) == 100;
            })->count();
            $unlocked = $subject->sections->flatMap->sessions->where('unlocked',1)->count();
        @endphp
        
        totalSessions += {{ $totalSessions }};
        completedSessions += {{ $completedSessions }};
        unlockedSessions += {{ $unlocked }};
    @endforeach
    
    // Update stats cards
    document.getElementById('totalSessions').textContent = totalSessions;
    document.getElementById('completedSessions').textContent = completedSessions;
    document.getElementById('unlockedSessions').textContent = unlockedSessions;
    
    // Initialize chart if subjects exist
    @if($course->subject && $course->subject->count() > 0)
        const ctx = document.getElementById('progressChart').getContext('2d');
        const progressChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Not Started', 'Locked'],
                datasets: [{
                    data: [
                        completedSessions,
                        unlockedSessions - completedSessions,
                        totalSessions - unlockedSessions - (unlockedSessions - completedSessions),
                        totalSessions - unlockedSessions
                    ],
                    backgroundColor: [
                        '#10b981',
                        '#3b82f6',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw + ' sessions';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    @endif
    
    // Add animation to progress bars
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = width;
        }, 300);
    });
});
</script>

<style>
    .accordion-button:not(.collapsed) {
        background-color: rgba(59, 130, 246, 0.05);
        color: #1f2937;
        box-shadow: none;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
    
    .subject-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table td {
        vertical-align: middle;
    }
</style>

@endsection