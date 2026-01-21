@extends('layout.admin.master')

@php use App\Http\Controllers\Api\ProgressController; @endphp

@section('admincontent')

<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Student Learning Analytics</h1>
            <p class="text-muted mb-0">Comprehensive progress tracking dashboard</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" id="exportBtn">
                <i class="fas fa-file-export me-2"></i>Export Report
            </button>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item" href="#" onclick="filterCourses('all')">All Courses</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterCourses('completed')">Completed</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterCourses('ongoing')">Ongoing</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterCourses('not-started')">Not Started</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Student Profile Card -->
    <div class="card border-0 shadow-lg mb-4" style="background: linear-gradient(135deg, #4f46e5 0%, #7e22ce 100%);">
        <div class="card-body text-white p-4">
            <div class="row align-items-center">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    <div class="avatar-circle" style="width: 80px; height: 80px; background: rgba(255,255,255,0.2);" 
                         data-initials="{{ getInitials($student->student_name) }}">
                        <span class="avatar-text">{{ getInitials($student->student_name) }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="mb-2">{{ $student->student_name }}</h4>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope me-2"></i>
                            <span>{{ $student->student_email }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar me-2"></i>
                            <span>{{ $student->enrollments->count() }} Enrolled Courses</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white rounded-3 p-3 text-center">
                        <div class="d-flex justify-content-center mb-2">
                            <div class="circular-progress" data-progress="{{ calculateOverallProgress($student) }}">
                                <div class="progress-circle">
                                    <span class="progress-value">0%</span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-primary mb-0">Overall Progress</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary border-0 shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Enrollments
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $student->enrollments->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success border-0 shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Completed Courses
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="completedCount">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning border-0 shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Ongoing Courses
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="ongoingCount">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info border-0 shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Average Progress
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="avgProgress">0%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Progress Section -->
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-graduation-cap me-2"></i>Enrolled Courses Progress
            </h5>
        </div>
        <div class="card-body p-0">
            @if($student->enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="coursesTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Course</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalProgress = 0;
                                $completedCount = 0;
                                $ongoingCount = 0;
                            @endphp
                            
                            @foreach($student->enrollments as $enroll)
                                @php
                                    $progress = ProgressController::courseProgress(
                                        $student->student_id,
                                        $enroll->course->course_id
                                    );
                                    
                                    $totalProgress += $progress;
                                    if($progress == 100) {
                                        $completedCount++;
                                    } else {
                                        $ongoingCount++;
                                    }
                                @endphp
                                
                                <tr class="course-row" data-status="{{ $progress == 100 ? 'completed' : 'ongoing' }}">
                                    <td class="align-middle">
                                        <div class="course-number bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                             style="width: 36px; height: 36px;">
                                            {{ $loop->iteration }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="course-icon me-3">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                    <i class="fas fa-book text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $enroll->course->course_name }}</h6>
                                                <small class="text-muted">
                                                    Enrolled: {{ $enroll->created_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress-container">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="progress-percent fw-bold">{{ $progress }}%</span>
                                                <span class="text-muted">{{ getCompletionText($progress) }}</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar {{ getProgressBarClass($progress) }}" 
                                                     style="width: {{ $progress }}%"
                                                     role="progressbar" 
                                                     aria-valuenow="{{ $progress }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge {{ $progress == 100 ? 'bg-success' : 'bg-warning' }} rounded-pill px-3 py-2">
                                            @if($progress == 100)
                                                <i class="fas fa-check-circle me-1"></i>Completed
                                            @elseif($progress > 0)
                                                <i class="fas fa-spinner me-1"></i>Ongoing
                                            @else
                                                <i class="fas fa-clock me-1"></i>Not Started
                                            @endif
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <small class="text-muted">
                                            @if($progress == 100)
                                                Completed on {{ $enroll->updated_at->format('M d, Y') }}
                                            @elseif($progress > 0)
                                                Last activity {{ $enroll->updated_at->diffForHumans() }}
                                            @else
                                                No activity yet
                                            @endif
                                        </small>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('student.course.detail', [$student->student_id,$enroll->course->course_id]) }}"
                                           class="btn btn-sm btn-primary btn-hover-lift">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            
                            @php
                                $avgProgress = $student->enrollments->count() > 0 ? round($totalProgress / $student->enrollments->count(), 1) : 0;
                            @endphp
                            
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    document.getElementById('completedCount').textContent = '{{ $completedCount }}';
                                    document.getElementById('ongoingCount').textContent = '{{ $ongoingCount }}';
                                    document.getElementById('avgProgress').textContent = '{{ $avgProgress }}%';
                                    
                                    // Update circular progress
                                    const overallProgress = {{ calculateOverallProgress($student) }};
                                    const circularProgress = document.querySelector('.circular-progress');
                                    const progressValue = circularProgress.querySelector('.progress-value');
                                    
                                    progressValue.textContent = overallProgress + '%';
                                    circularProgress.style.setProperty('--progress', overallProgress);
                                });
                            </script>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-book fa-3x text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">No Enrollments Found</h4>
                    <p class="text-muted mb-0">This student hasn't enrolled in any courses yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Performance Chart Section -->
    @if($student->enrollments->count() > 0)
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Progress Overview
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="progressChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-trophy me-2"></i>Performance Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Completion Rate</h6>
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 me-3">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($completedCount / $student->enrollments->count()) * 100 }}%"></div>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold">{{ round(($completedCount / $student->enrollments->count()) * 100, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Learning Pace</h6>
                        @if($avgProgress > 70)
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="fas fa-bolt text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Fast Learner</h6>
                                <small class="text-muted">Above average progress rate</small>
                            </div>
                        </div>
                        @elseif($avgProgress > 40)
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="fas fa-walking text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Steady Progress</h6>
                                <small class="text-muted">Consistent learning pace</small>
                            </div>
                        </div>
                        @else
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="fas fa-clock text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Getting Started</h6>
                                <small class="text-muted">Beginning the learning journey</small>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div>
                        <h6 class="text-muted mb-3">Recommendations</h6>
                        @if($avgProgress < 30 && $student->enrollments->count() > 1)
                        <div class="alert alert-warning">
                            <i class="fas fa-lightbulb me-2"></i>
                            <small>Consider focusing on fewer courses to improve completion rates</small>
                        </div>
                        @endif
                        @if($completedCount == $student->enrollments->count())
                        <div class="alert alert-success">
                            <i class="fas fa-star me-2"></i>
                            <small>Excellent! All enrolled courses have been completed</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- PHP Helper Functions (Add to your controller or helper) -->
@php
    function getInitials($name) {
        $words = explode(' ', $name);
        $initials = '';
        foreach($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return substr($initials, 0, 2);
    }
    
    function getProgressBarClass($progress) {
        if($progress == 100) return 'bg-success';
        if($progress >= 70) return 'bg-info';
        if($progress >= 40) return 'bg-warning';
        return 'bg-danger';
    }
    
    function getCompletionText($progress) {
        if($progress == 100) return 'Complete';
        if($progress >= 70) return 'Almost There';
        if($progress >= 40) return 'Halfway';
        if($progress > 0) return 'Started';
        return 'Not Started';
    }
    
    function calculateOverallProgress($student) {
        $totalProgress = 0;
        $courseCount = $student->enrollments->count();
        
        foreach($student->enrollments as $enroll) {
            $progress = ProgressController::courseProgress(
                $student->student_id,
                $enroll->course->course_id
            );
            $totalProgress += $progress;
        }
        
        return $courseCount > 0 ? round($totalProgress / $courseCount, 1) : 0;
    }
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize progress chart if enrollments exist
    @if($student->enrollments->count() > 0)
        const ctx = document.getElementById('progressChart').getContext('2d');
        const progressChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($student->enrollments as $enroll)
                        '{{ Str::limit($enroll->course->course_name, 15) }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Progress (%)',
                    data: [
                        @foreach($student->enrollments as $enroll)
                            {{ ProgressController::courseProgress($student->student_id, $enroll->course->course_id) }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach($student->enrollments as $enroll)
                            @php
                                $progress = ProgressController::courseProgress($student->student_id, $enroll->course->course_id);
                                $color = $progress == 100 ? '#10b981' : 
                                         ($progress >= 70 ? '#3b82f6' : 
                                          ($progress >= 40 ? '#f59e0b' : '#ef4444'));
                            @endphp
                            '{{ $color }}',
                        @endforeach
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Progress: ${context.raw}%`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    @endif
    
    // Filter courses functionality
    window.filterCourses = function(status) {
        const rows = document.querySelectorAll('.course-row');
        rows.forEach(row => {
            if(status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update active filter button
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.classList.remove('active');
        });
        event.target.classList.add('active');
    };
    
    // Export button functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
        // You can implement PDF/Excel export here
        alert('Export feature would be implemented here');
    });
    
    // Animate progress bars
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
    /* Circular Progress */
    .circular-progress {
        --progress: 0;
        width: 80px;
        height: 80px;
        position: relative;
    }
    
    .progress-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: conic-gradient(#4f46e5 calc(var(--progress) * 3.6deg), #e5e7eb 0deg);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .progress-circle::before {
        content: '';
        position: absolute;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: white;
    }
    
    .progress-value {
        position: relative;
        font-weight: bold;
        font-size: 1.2rem;
        color: #4f46e5;
    }
    
    /* Avatar */
    .avatar-circle {
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .avatar-text {
        font-size: 1.5rem;
        font-weight: bold;
        color: white;
    }
    
    /* Hover Effects */
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-hover-lift:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    /* Table Styling */
    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: none;
        padding: 1rem;
    }
    
    .table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .table tbody tr:hover {
        background-color: rgba(79, 70, 229, 0.04);
    }
    
    /* Card Styling */
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    /* Badge Styling */
    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }
    
    /* Progress Bar */
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    /* Course Icon */
    .course-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .avatar-circle {
            width: 60px;
            height: 60px;
        }
        
        .avatar-text {
            font-size: 1.2rem;
        }
        
        .circular-progress {
            width: 60px;
            height: 60px;
        }
        
        .progress-circle::before {
            width: 50px;
            height: 50px;
        }
    }
</style>

@endsection