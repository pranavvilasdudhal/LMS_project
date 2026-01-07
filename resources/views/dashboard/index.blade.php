@extends('layout.admin.master')
@section('admincontent')
<div class="container-fluid px-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Dashboard Overview</h1>
            <p class="text-muted mb-0">Welcome back, Admin! Here's what's happening today.</p>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge bg-light text-dark me-3">
                <i class="fas fa-calendar-alt me-2"></i>
                {{ now()->format('F j, Y') }}
            </span>
            <button class="btn btn-outline-primary btn-sm">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
        </div>
    </div>

    {{-- 🔢 STAT CARDS --}}
    <div class="row mb-4">
        {{-- TOTAL USERS --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                            <div class="mt-2">
                                <span class="text-success small font-weight-bold">
                                    <i class="fas fa-arrow-up me-1"></i>12%
                                </span>
                                <span class="text-muted small">Since last month</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    {{-- <a href="{{ route('admin.users.index') }}" class="text-decoration-none small"> --}}
                        View Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- APPROVED PDFs --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved PDFs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approvedPdfCount }}</div>
                            <div class="mt-2">
                                <span class="text-success small font-weight-bold">
                                    <i class="fas fa-check-circle me-1"></i>Approved
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-pdf fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    {{-- <a href="{{ route('admin.pdfs.index') }}" class="text-decoration-none small"> --}}
                        Manage PDFs <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- CART ITEMS --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Cart Items</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cartCount }}</div>
                            <div class="mt-2">
                                <span class="text-warning small font-weight-bold">
                                    <i class="fas fa-shopping-cart me-1"></i>Active
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    {{-- <a href="{{ route('admin.carts.index') }}" class="text-decoration-none small"> --}}
                        View Carts <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- ENROLMENTS --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Enrolments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $enrolmentCount }}</div>
                            <div class="mt-2">
                                <span class="text-info small font-weight-bold">
                                    <i class="fas fa-user-graduate me-1"></i>Active
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    {{-- <a href="{{ route('admin.enrolments.index') }}" class="text-decoration-none small"> --}}
                        View Enrolments <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 📊 CHARTS & ACTIVITY --}}
      <div class="row" data-aos="fade-up" data-aos-delay="500">
        {{-- MAIN CHART --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between border-0">
                    <h6 class="m-0 fw-bold text-dark">Activity Overview</h6>
                    <div class="dropdown">
                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Export Data</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print Chart</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 320px;">
                        <canvas id="dashboardChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- QUICK STATS & ACTIVITY --}}
        <div class="col-xl-4 col-lg-5">
            {{-- QUICK STATS --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="m-0 fw-bold text-dark">Performance Metrics</h6>
                </div>
                <div class="card-body">
                    <div class="metric-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">PDF Approval Rate</span>
                            <span class="fw-bold text-dark">85%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success rounded" role="progressbar" style="width: 85%"></div>
                        </div>
                    </div>
                    
                    <div class="metric-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">User Engagement</span>
                            <span class="fw-bold text-dark">72%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info rounded" role="progressbar" style="width: 72%"></div>
                        </div>
                    </div>
                    
                    <div class="metric-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Cart Conversion</span>
                            <span class="fw-bold text-dark">45%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning rounded" role="progressbar" style="width: 45%"></div>
                        </div>
                    </div>

                    {{-- RECENT ACTIVITY --}}
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3 fw-bold text-dark">Recent Activity</h6>
                        <div class="activity-timeline">
                            <div class="activity-item d-flex mb-3">
                                <div class="activity-icon">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-plus text-primary fs-6"></i>
                                    </div>
                                </div>
                                <div class="activity-content ms-3">
                                    <p class="mb-0 fw-medium text-dark">New user registered</p>
                                    <small class="text-muted"><i class="far fa-clock me-1"></i>5 minutes ago</small>
                                </div>
                            </div>
                            <div class="activity-item d-flex mb-3">
                                <div class="activity-icon">
                                    <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-check text-success fs-6"></i>
                                    </div>
                                </div>
                                <div class="activity-content ms-3">
                                    <p class="mb-0 fw-medium text-dark">PDF approved by Admin</p>
                                    <small class="text-muted"><i class="far fa-clock me-1"></i>1 hour ago</small>
                                </div>
                            </div>
                            <div class="activity-item d-flex">
                                <div class="activity-icon">
                                    <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-shopping-cart text-warning fs-6"></i>
                                    </div>
                                </div>
                                <div class="activity-content ms-3">
                                    <p class="mb-0 fw-medium text-dark">New cart item added</p>
                                    <small class="text-muted"><i class="far fa-clock me-1"></i>2 hours ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('dashboardChart').getContext('2d');
    
    // Gradient for chart area
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(78, 115, 223, 0.3)');
    gradient.addColorStop(1, 'rgba(78, 115, 223, 0.05)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['PDFs', 'Cart Items', 'Enrolments', 'Users'],
            datasets: [{
                label: 'Count',
                data: [
                    {{ $approvedPdfCount }},
                    {{ $cartCount }},
                    {{ $enrolmentCount }},
                    {{ $totalUsers }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(0, 123, 255, 0.8)',
                    'rgba(111, 66, 193, 0.8)'
                ],
                borderColor: [
                    'rgb(40, 167, 69)',
                    'rgb(255, 193, 7)',
                    'rgb(0, 123, 255)',
                    'rgb(111, 66, 193)'
                ],
                borderWidth: 1,
                borderRadius: 6,
                hoverBackgroundColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(111, 66, 193, 1)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#4e73df',
                    borderWidth: 1,
                    cornerRadius: 4,
                    padding: 12
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000) {
                                return (value / 1000).toFixed(1) + 'k';
                            }
                            return value;
                        }
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
});
</script>

<style>
.card {
    border: none;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.progress {
    height: 8px;
    border-radius: 4px;
}

.list-group-item:hover {
    background-color: rgba(78, 115, 223, 0.05);
}

.text-primary {
    color: #4e73df !important;
}

.text-success {
    color: #1cc88a !important;
}

.text-warning {
    color: #f6c23e !important;
}

.text-info {
    color: #36b9cc !important;
}

.bg-primary {
    background-color: #4e73df !important;
}

.bg-success {
    background-color: #1cc88a !important;
}

.bg-warning {
    background-color: #f6c23e !important;
}

.bg-info {
    background-color: #36b9cc !important;
}
</style>
@endsection