@extends('layout.admin.master')

@section('admincontent')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Student Progress Management</h1>
            <p class="text-muted mb-0">Monitor and track student learning progress across all courses</p>
        </div>
        <div class="d-flex gap-2">
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0" 
                       placeholder="Search students..." onkeyup="searchStudents()">
            </div>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="filterDropdown" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item" href="#" onclick="filterStudents('all')">All Students</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterStudents('active')">Active Learners</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterStudents('top')">Top Performers</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterStudents('inactive')">Inactive</a></li>
                </ul>
            </div>
            <button class="btn btn-primary" onclick="exportReport()">
                <i class="fas fa-file-export me-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary border-0 shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Students
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $students->count() }}</div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up me-1"></i>5% from last month
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary">
                                <i class="fas fa-users text-white"></i>
                            </div>
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
                                Active This Week
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="activeCount">0</div>
                            <div class="mt-2">
                                <small class="text-muted">Based on learning activity</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success">
                                <i class="fas fa-bolt text-white"></i>
                            </div>
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
                                Average Progress
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="avgProgress">68%</div>
                            <div class="mt-2">
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar bg-warning" style="width: 68%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
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
                                Courses Completed
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="coursesCompleted">142</div>
                            <div class="mt-2">
                                <small class="text-info">Across all students</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info">
                                <i class="fas fa-graduation-cap text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-user-graduate me-2"></i>Student Progress Directory
            </h5>
        </div>
        <div class="card-body p-0">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="studentsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">#</th>
                                <th>Student</th>
                                <th style="width: 120px;">Enrollments</th>
                                <th style="width: 150px;">Overall Progress</th>
                                <th style="width: 120px;">Status</th>
                                <th style="width: 150px;">Last Activity</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $s)
                                @php
                                    // Mock data for demonstration - replace with actual data
                                    $enrollmentCount = rand(1, 8);
                                    $overallProgress = rand(0, 100);
                                    $lastActive = now()->subDays(rand(0, 30));
                                    $isActive = $lastActive->diffInDays(now()) < 7;
                                @endphp
                                
                                <tr class="student-row" data-active="{{ $isActive ? 'true' : 'false' }}">
                                    <td class="text-center align-middle">
                                        <div class="student-number bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                             style="width: 36px; height: 36px;">
                                            {{ $loop->iteration }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3" 
                                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"
                                                 data-initials="{{ substr($s->student_name, 0, 2) }}">
                                                <span class="avatar-text">{{ substr($s->student_name, 0, 2) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $s->student_name }}</h6>
                                                <small class="text-muted d-block">{{ $s->student_email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-pill px-3 py-1">
                                                <span class="fw-bold text-primary">{{ $enrollmentCount }}</span>
                                                <small class="text-muted ms-1">courses</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress-container">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="progress-percent fw-bold">{{ $overallProgress }}%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar {{ $overallProgress == 100 ? 'bg-success' : ($overallProgress >= 70 ? 'bg-info' : ($overallProgress >= 40 ? 'bg-warning' : 'bg-danger')) }}" 
                                                     style="width: {{ $overallProgress }}%"
                                                     role="progressbar" 
                                                     aria-valuenow="{{ $overallProgress }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge {{ $isActive ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-1">
                                            @if($isActive)
                                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Active
                                            @else
                                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i>Inactive
                                            @endif
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <small class="text-muted">
                                            @if($isActive)
                                                {{ $lastActive->diffForHumans() }}
                                            @else
                                                {{ $lastActive->format('M d, Y') }}
                                            @endif
                                        </small>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('student.progress.show', $s->student_id) }}" 
                                               class="btn btn-sm btn-primary btn-hover-lift" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="View Progress Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-primary btn-hover-lift"
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-placement="top" 
                                                    title="Send Message">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div>
                        <small class="text-muted">
                            Showing <span id="visibleCount">{{ $students->count() }}</span> of {{ $students->count() }} students
                        </small>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary active">1</button>
                        <button class="btn btn-sm btn-outline-primary">2</button>
                        <button class="btn btn-sm btn-outline-primary">3</button>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-user-graduate fa-3x text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">No Students Found</h4>
                    <p class="text-muted mb-0">There are no students enrolled in the system yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="row mt-4">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Progress Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Progress Categories</h6>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="progress-indicator bg-success me-2"></div>
                                    <span>Excellent (80-100%)</span>
                                    <span class="ms-auto fw-bold">24%</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="progress-indicator bg-info me-2"></div>
                                    <span>Good (60-79%)</span>
                                    <span class="ms-auto fw-bold">42%</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="progress-indicator bg-warning me-2"></div>
                                    <span>Average (40-59%)</span>
                                    <span class="ms-auto fw-bold">22%</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="progress-indicator bg-danger me-2"></div>
                                    <span>Needs Improvement (0-39%)</span>
                                    <span class="ms-auto fw-bold">12%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <div class="progress-donut" style="width: 120px; height: 120px;">
                                    <div class="donut-chart"></div>
                                    <div class="donut-center">
                                        <span class="donut-value">68%</span>
                                        <small class="text-muted">Avg</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-trophy me-2"></i>Top Performers
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($students->take(3) as $index => $topStudent)
                        @php
                            $progress = [95, 88, 82][$index] ?? 80;
                            $courses = [5, 4, 6][$index] ?? 4;
                        @endphp
                        <div class="d-flex align-items-center mb-3">
                            <div class="rank-badge me-3">
                                <span class="rank-number">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <h6 class="mb-0">{{ $topStudent->student_name }}</h6>
                                    <span class="fw-bold text-primary">{{ $progress }}%</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">{{ $courses }} courses enrolled</small>
                                    <a href="{{ route('student.progress.show', $topStudent->student_id) }}" 
                                       class="text-decoration-none">
                                        <small>View <i class="fas fa-arrow-right ms-1"></i></small>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if($index < 2)
                            <hr class="my-3">
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Count active students
    const activeStudents = document.querySelectorAll('[data-active="true"]').length;
    document.getElementById('activeCount').textContent = activeStudents;

    // Search functionality
    window.searchStudents = function() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.student-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const name = row.querySelector('h6').textContent.toLowerCase();
            const email = row.querySelector('.text-muted').textContent.toLowerCase();
            
            if (name.includes(input) || email.includes(input)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        document.getElementById('visibleCount').textContent = visibleCount;
    };

    // Filter functionality
    window.filterStudents = function(filter) {
        const rows = document.querySelectorAll('.student-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const isActive = row.dataset.active === 'true';
            const progress = parseInt(row.querySelector('.progress-percent').textContent);
            
            let showRow = false;
            
            switch(filter) {
                case 'all':
                    showRow = true;
                    break;
                case 'active':
                    showRow = isActive;
                    break;
                case 'top':
                    showRow = progress >= 80;
                    break;
                case 'inactive':
                    showRow = !isActive;
                    break;
            }
            
            if (showRow) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        document.getElementById('visibleCount').textContent = visibleCount;
        
        // Update active filter button
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.classList.remove('active');
        });
        event.target.classList.add('active');
    };

    // Export functionality
    window.exportReport = function() {
        // You can implement CSV/PDF export here
        alert('Export feature would generate a report of all students');
    };

    // Animate progress bars on load
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = width;
        }, 300);
    });

    // Calculate average progress
    const progressValues = Array.from(progressBars).map(bar => 
        parseInt(bar.getAttribute('aria-valuenow'))
    );
    const avgProgress = progressValues.length > 0 
        ? Math.round(progressValues.reduce((a, b) => a + b) / progressValues.length)
        : 0;
    document.getElementById('avgProgress').textContent = avgProgress + '%';
});
</script>

<style>
    /* Icon Circles */
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    /* Avatar */
    .avatar-circle {
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-text {
        font-size: 14px;
        font-weight: bold;
        color: white;
        text-transform: uppercase;
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
    
    /* Progress Indicator */
    .progress-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    
    /* Donut Chart */
    .progress-donut {
        position: relative;
    }
    
    .donut-chart {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: conic-gradient(
            #10b981 0% 24%,
            #3b82f6 24% 66%,
            #f59e0b 66% 88%,
            #ef4444 88% 100%
        );
    }
    
    .donut-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .donut-value {
        font-size: 1.25rem;
        font-weight: bold;
        color: #4f46e5;
    }
    
    /* Rank Badge */
    .rank-badge {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .rank-number {
        color: white;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    /* Progress Bar */
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    /* Student Number */
    .student-number {
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .table-responsive {
            border: none;
        }
        
        .table th, .table td {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        
        .avatar-circle {
            width: 32px;
            height: 32px;
        }
        
        .avatar-text {
            font-size: 12px;
        }
    }
</style>
@endsection