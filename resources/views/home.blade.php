<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR System - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1e3a8a;
            --emerald-green: #10b981;
            --emerald-dark: #059669;
            --bg-light: #f8fafc;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1e40af 100%);
            padding: 1rem 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow);
        }

        .stat-card .card-body {
            padding: 1.5rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .btn-primary { background-color: var(--primary-blue); border: none; }
        .btn-primary:hover { background-color: #1e40af; }
        .btn-success { background-color: var(--emerald-green); border: none; }
        .btn-success:hover { background-color: var(--emerald-dark); }

        .btn-directory {
            background: white;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-weight: 600;
            width: 100%;
            padding: 1.25rem;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s;
        }

        .btn-directory:hover {
            background: #f1f5f9;
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        /* Side Drawer Styling */
        .offcanvas-end {
            width: 500px !important;
            border-left: none;
            box-shadow: -10px 0 30px rgba(0,0,0,0.1);
        }

        .offcanvas-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1e40af 100%);
            color: white;
            padding: 2rem 1.5rem;
        }

        .drawer-photo-container {
            margin-top: -50px;
            position: relative;
            display: inline-block;
        }

        .drawer-profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            object-fit: cover;
            background: white;
        }

        .photo-edit-badge {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: var(--emerald-green);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            transition: all 0.2s;
        }

        .photo-edit-badge:hover {
            transform: scale(1.1);
            background: var(--emerald-dark);
        }

        .info-group {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-blue);
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 600;
            color: #1e293b;
        }

        .balance-pill {
            padding: 0.75rem;
            border-radius: 12px;
            text-align: center;
        }

        .balance-pill.casual { background: #ecfdf5; color: #065f46; border: 1px solid #10b981; }
        .balance-pill.sick { background: #fffbeb; color: #92400e; border: 1px solid #f59e0b; }
        .balance-pill.total { background: #eff6ff; color: #1e40af; border: 1px solid #3b82f6; }

        .absence-list {
            max-height: 200px;
            overflow-y: auto;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 1rem 0;
            margin-right: 1.5rem;
            border-bottom: 2px solid transparent;
        }

        .nav-tabs-custom .nav-link.active {
            color: var(--primary-blue);
            border-bottom-color: var(--primary-blue);
            background: transparent;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-university me-2"></i>EMS Dashboard
            </a>
            <div class="d-flex align-items-center">
                <a href="{{ route('leaves.index') }}" class="btn btn-outline-light btn-sm me-3 rounded-pill px-3">
                    <i class="fas fa-calendar-alt me-1"></i> Leaves
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <!-- Zonal Header -->
        <div class="row align-items-center mb-4">
            <div class="col-lg-8">
                <h1 class="fw-extrabold mb-1" style="letter-spacing: -0.025em;">Admin Portal</h1>
                <p class="text-muted mb-0"><i class="fas fa-map-marker-alt text-danger me-1"></i> Zonal Education Office - Dehiovita</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                    <a href="{{ route('leaves.zonal-summary-pdf') }}" class="btn btn-primary px-3">
                        <i class="fas fa-file-pdf me-1"></i> Annual Summary
                    </a>
                    <a href="/add-employee" class="btn btn-success px-3">
                        <i class="fas fa-user-plus me-1"></i> Add Staff
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card stat-card shadow-sm h-100 border-top border-5 border-primary">
                    <div class="card-body">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <h6 class="text-muted fw-bold small text-uppercase mb-1">Total Employees</h6>
                        <h2 class="fw-bold mb-0">{{ $totalEmployees }}</h2>
                        <p class="small text-muted mt-2 mb-0">Active staff members</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card shadow-sm h-100 border-top border-5 border-emerald">
                    <div class="card-body" style="border-top-color: var(--emerald-green);">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h6 class="text-muted fw-bold small text-uppercase mb-1">Pending Leaves</h6>
                        <h2 class="fw-bold mb-0 text-success">{{ $pendingApprovals }}</h2>
                        <a href="{{ route('leaves.index') }}" class="small text-decoration-none mt-2 d-inline-block fw-semibold">Review Pending &rarr;</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card shadow-sm h-100 border-top border-5 border-danger">
                    <div class="card-body">
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <h6 class="text-muted fw-bold small text-uppercase mb-1">On Leave Today</h6>
                        <h2 class="fw-bold mb-0 text-danger">{{ $onLeaveToday->count() }}</h2>
                        @if($onLeaveToday->count() > 0)
                            <button class="btn btn-link btn-sm p-0 text-decoration-none mt-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#todayAbsence">
                                View Personnel <i class="fas fa-chevron-down ms-1"></i>
                            </button>
                            <div class="collapse mt-2" id="todayAbsence">
                                <ul class="list-unstyled absence-list pe-2">
                                    @foreach($onLeaveToday as $lv)
                                        <li class="border-bottom py-1 small">
                                            <span class="fw-bold">{{ $lv->user->name }}</span>
                                            <span class="text-muted d-block" style="font-size: 0.7rem;">{{ $lv->user->designation }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="small text-muted mt-2 mb-0">Full attendance today</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card shadow-sm h-100 border-top border-5 border-info">
                    <div class="card-body">
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h6 class="text-muted fw-bold small text-uppercase mb-1">On Leave Tomorrow</h6>
                        <h2 class="fw-bold mb-0 text-info">{{ $onLeaveTomorrow->count() }}</h2>
                        @if($onLeaveTomorrow->count() > 0)
                            <button class="btn btn-link btn-sm p-0 text-decoration-none mt-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#tomorrowAbsence">
                                View Personnel <i class="fas fa-chevron-down ms-1"></i>
                            </button>
                            <div class="collapse mt-2" id="tomorrowAbsence">
                                <ul class="list-unstyled absence-list pe-2">
                                    @foreach($onLeaveTomorrow as $lv)
                                        <li class="border-bottom py-1 small">
                                            <span class="fw-bold">{{ $lv->user->name }}</span>
                                            <span class="text-muted d-block" style="font-size: 0.7rem;">{{ $lv->user->designation }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="small text-muted mt-2 mb-0">No leaves scheduled</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Birthdays Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card stat-card shadow-sm border-top border-5" style="border-top-color: #f59e0b !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-birthday-cake text-warning me-2"></i>🎂 Upcoming Birthdays (Next 7 Days)
                            </h5>
                            @if($upcomingBirthdays->count() > 0)
                                <span class="badge bg-warning text-dark rounded-pill px-3">{{ $upcomingBirthdays->count() }} Celebrating</span>
                            @endif
                        </div>
                        
                        <div class="row g-3">
                            @forelse($upcomingBirthdays as $ub)
                                <div class="col-md-3">
                                    <div class="p-3 rounded-4 bg-white border border-warning border-opacity-20 h-100 shadow-sm" style="border-left: 4px solid #f59e0b;">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="stat-icon bg-warning bg-opacity-10 text-warning mb-0 me-3 shadow-none overflow-hidden" style="width: 45px; height: 45px; min-width: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                @if($ub->profile_photo_path)
                                                    <img src="{{ asset('storage/' . $ub->profile_photo_path) }}" class="w-100 h-100 object-fit-cover">
                                                @else
                                                    <i class="fas fa-cake-candles"></i>
                                                @endif
                                            </div>
                                            <div class="overflow-hidden">
                                                <h6 class="fw-bold mb-0 text-truncate" title="{{ $ub->full_name ?? $ub->name }}">{{ $ub->full_name ?? $ub->name }}</h6>
                                                <span class="text-warning fw-bold small">
                                                    <i class="far fa-calendar-check me-1"></i>{{ $ub->birthday->format('M d') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-2 pt-2 border-top">
                                            <span class="text-muted small d-block text-truncate">
                                                <i class="fas fa-id-badge me-1 opacity-50"></i>{{ $ub->designation ?? 'Staff Member' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5 bg-light rounded-4 border border-dashed">
                                        <div class="text-muted mb-3 opacity-20">
                                            <i class="fas fa-birthday-cake fa-4x"></i>
                                        </div>
                                        <h5 class="text-muted fw-bold">No birthdays this week</h5>
                                        <p class="text-muted small mb-0 px-4">We don't have any upcoming celebrations in the next 7 days.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Management Section -->
        <div class="mb-5">
            <button class="btn-directory shadow-sm mb-4" type="button" data-bs-toggle="collapse" data-bs-target="#staffDirectory">
                <span><i class="fas fa-address-book me-2 text-primary"></i> Staff Management Directory</span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="collapse" id="staffDirectory">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-muted small text-uppercase"><i class="fas fa-microchip me-1"></i> Utilities</span>
                        <div class="d-flex gap-2">
                            <button id="bulk-delete-btn" class="btn btn-sm btn-danger px-3 rounded-pill d-none" onclick="bulkDelete()">
                                <i class="fas fa-trash-alt me-1"></i> Delete Selected (<span id="selected-count">0</span>)
                            </button>
                            <button class="btn btn-sm btn-outline-success px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="fas fa-upload me-1"></i> Bulk Import
                            </button>
                            <a href="{{ route('admin.staff.export') }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill">
                                <i class="fas fa-download me-1"></i> Export Data
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4 bg-light bg-opacity-50 border-bottom">
                         <form action="{{ route('dashboard') }}" method="GET" class="row g-2">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0" 
                                           placeholder="Name or NIC..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="designation" class="form-select">
                                    <option value="">All Designations</option>
                                    @foreach($designations as $ds)
                                        <option value="{{ $ds }}" {{ request('designation') == $ds ? 'selected' : '' }}>{{ $ds }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="section" class="form-select">
                                    <option value="">All Sections</option>
                                    @foreach($sections as $sec)
                                        <option value="{{ $sec }}" {{ request('section') == $sec ? 'selected' : '' }}>{{ $sec }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3">Filter</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-white border-bottom">
                                <tr>
                                    <th class="ps-4" style="width: 40px;">
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                    </th>
                                    <th>Personnel</th>
                                    <th>Designation</th>
                                    <th>Workplace</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" class="form-check-input user-checkbox" value="{{ $employee->id }}" onchange="updateBulkButton()">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $employee->profile_photo_path ? asset('storage/' . $employee->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($employee->name).'&background=1e3a8a&color=fff' }}" 
                                                 class="rounded-circle me-3 border" width="40" height="40" alt="">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $employee->full_name ?? $employee->name }}</div>
                                                <div class="text-muted small">{{ $employee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-white text-dark border px-2 py-1">{{ $employee->designation }}</span></td>
                                    <td><div class="text-muted small"><i class="fas fa-building me-1"></i> {{ $employee->workplace }}</div></td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('employees.pdf', $employee->id) }}" class="btn btn-sm btn-outline-danger px-2 rounded-pill" title="PDF Service Record">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </a>
                                            <button class="btn btn-sm btn-outline-primary px-3 rounded-pill" onclick="viewEmployee({{ $employee->id }})">
                                                Profile View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-search-minus fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No staff matches found in the registry.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top bg-white">
                        {{ $employees->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Side Drawer (Offcanvas) for Employee View -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="employeeDrawer" aria-labelledby="employeeDrawerLabel">
        <div class="offcanvas-header d-block">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="offcanvas-title fw-bold" id="employeeDrawerLabel">Personnel Record</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="text-center mt-3">
                <div class="drawer-photo-container">
                    <img id="d-photo" src="" class="drawer-profile-img" alt="Profile">
                    <label for="photoUpload" class="photo-edit-badge" title="Change Photo">
                        <i class="fas fa-camera"></i>
                    </label>
                    <form id="photo-form" enctype="multipart/form-data" class="d-none">
                        @csrf
                        <input type="file" id="photoUpload" name="photo" accept="image/*" onchange="uploadNewPhoto()">
                    </form>
                </div>
                <h3 id="d-name" class="fw-bold mt-2 mb-0"></h3>
                <p id="d-designation" class="mb-0 text-white-50"></p>
                <div id="d-grade-badge" class="mt-2"></div>
            </div>
        </div>
        <div class="offcanvas-body p-0">
            <!-- Nav Tabs inside Drawer -->
            <ul class="nav nav-tabs nav-tabs-custom px-4 border-bottom" id="drawerTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">General</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="service-tab" data-bs-toggle="tab" data-bs-target="#service" type="button" role="tab">Career</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="quals-tab" data-bs-toggle="tab" data-bs-target="#quals" type="button" role="tab">Qualifications</button>
                </li>
            </ul>

            <div class="tab-content p-4" id="drawerTabsContent">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="info-group">
                                <div class="info-label">Full Name with Initials</div>
                                <div class="info-value" id="d-fullname-initials">—</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-group">
                                <div class="info-label">NIC Number</div>
                                <div class="info-value" id="d-nic">—</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-group">
                                <div class="info-label">Marital Status</div>
                                <div class="info-value" id="d-marital">—</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-group">
                                <div class="info-label">Current Workplace</div>
                                <div class="info-value" id="d-workplace">—</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-group border-left-success" style="border-left-color: var(--emerald-green);">
                                <div class="info-label">Contact Details (Mob / WhatsApp)</div>
                                <div class="info-value">
                                    <i class="fas fa-phone text-muted me-2 small"></i><span id="d-mobile">N/A</span><br>
                                    <i class="fab fa-whatsapp text-success me-2 small"></i><span id="d-whatsapp">N/A</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-group">
                                <div class="info-label">Permanent Address</div>
                                <div class="info-value small fw-normal" id="d-address">—</div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mt-4 mb-3"><i class="fas fa-chart-pie me-2 text-primary"></i>Annual Leave Balance ({{ date('Y') }})</h6>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="balance-pill casual">
                                <div class="small fw-bold">Casual</div>
                                <div class="h5 mb-0 fw-bold"><span id="d-casual">0</span>/24</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="balance-pill sick">
                                <div class="small fw-bold">Sick</div>
                                <div class="h5 mb-0 fw-bold"><span id="d-sick">0</span>/21</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="balance-pill total">
                                <div class="small fw-bold">Taken</div>
                                <div class="h5 mb-0 fw-bold"><span id="d-total">0</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Career Tab -->
                <div class="tab-pane fade" id="service" role="tabpanel">
                    <div class="info-group">
                        <div class="info-label">W&OP Number</div>
                        <div class="info-value" id="d-wop">—</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Service History</div>
                        <div class="info-value small fw-normal" id="d-history" style="white-space: pre-wrap;">—</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">EB Examinations</div>
                        <div class="info-value small fw-normal" id="d-ebexams">—</div>
                    </div>
                </div>

                <!-- Qualifications Tab -->
                <div class="tab-pane fade" id="quals" role="tabpanel">
                    <div class="info-group">
                        <div class="info-label">Educational Qualifications</div>
                        <div class="info-value small fw-normal" id="d-eduquals">—</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Professional Qualifications</div>
                        <div class="info-value small fw-normal" id="d-profquals">—</div>
                    </div>
                </div>
            </div>

            <div class="p-4 border-top bg-light">
                <div class="row g-2">
                    <div class="col-6">
                        <a id="d-edit-btn" href="#" class="btn btn-primary w-100 rounded-pill">
                            <i class="fas fa-edit me-1"></i> Edit Profile
                        </a>
                    </div>
                    <div class="col-6">
                        <form id="d-delete-form" method="POST" onsubmit="return confirm('Execute permanent removal?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                                <i class="fas fa-trash-alt me-1"></i> Terminate
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header bg-emerald text-white p-4" style="border-radius: 20px 20px 0 0; background-color: var(--emerald-green);">
                    <h5 class="modal-title fw-bold"><i class="fas fa-file-csv me-2"></i>Bulk Import Personnel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.staff.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">Select Registry File (.csv, .xlsx)</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="alert alert-info border-0 rounded-4 p-3 mb-0">
                            <p class="small mb-0"><strong>Standard Header Format:</strong><br>
                            <code>Name, Email, Phone</code></p>
                            <hr class="my-2">
                            <a href="{{ route('admin.staff.template') }}?v=2" class="small fw-bold text-decoration-none">
                                <i class="fas fa-download me-1"></i> Download Protocol Template v2.0
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4">Initialize Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentUserId = null;

        function viewEmployee(id) {
            currentUserId = id;
            const drawer = new bootstrap.Offcanvas(document.getElementById('employeeDrawer'));
            
            // Clear current data
            document.getElementById('d-name').innerText = 'Syncing...';
            
            fetch(`/admin/employee-details/${id}`)
                .then(r => r.json())
                .then(data => {
                    if(data.error) return Swal.fire('Access Denied', data.error, 'error');

                    // Photo & Header
                    document.getElementById('d-photo').src = data.photo || `https://ui-avatars.com/api/?name=${encodeURIComponent(data.full_name)}&background=1e3a8a&color=fff`;
                    document.getElementById('d-name').innerText = data.full_name;
                    document.getElementById('d-designation').innerText = data.designation;
                    
                    const gradeBadge = document.getElementById('d-grade-badge');
                    gradeBadge.innerHTML = data.grade !== 'N/A' ? `<span class="badge bg-white text-primary fw-bold border">${data.grade}</span>` : '';

                    // Overview Fields
                    document.getElementById('d-fullname-initials').innerText = data.full_name_with_initials || '—';
                    document.getElementById('d-nic').innerText = data.nic_number || '—';
                    document.getElementById('d-marital').innerText = data.marital_status || '—';
                    document.getElementById('d-workplace').innerText = data.workplace || '—';
                    document.getElementById('d-mobile').innerText = data.mobile_no || 'N/A';
                    document.getElementById('d-whatsapp').innerText = data.whatsapp_no || 'N/A';
                    document.getElementById('d-address').innerText = data.address || '—';

                    // Leave Balances
                    document.getElementById('d-casual').innerText = data.balances.casual;
                    document.getElementById('d-sick').innerText = data.balances.sick;
                    document.getElementById('d-total').innerText = data.balances.annual;

                    // Career
                    document.getElementById('d-wop').innerText = data.wop_no || '—';
                    document.getElementById('d-history').innerText = data.service_history || 'No service records logged.';
                    document.getElementById('d-ebexams').innerText = data.eb_exams || '—';

                    // Quals
                    document.getElementById('d-eduquals').innerText = data.edu_qualifications || '—';
                    document.getElementById('d-profquals').innerText = data.prof_qualifications || '—';

                    // Actions
                    document.getElementById('d-edit-btn').href = data.edit_url;
                    document.getElementById('d-delete-form').action = data.delete_url;

                    drawer.show();
                })
                .catch(err => Swal.fire('Error', 'Communication with server failed.', 'error'));
        }

        function uploadNewPhoto() {
            const fileInput = document.getElementById('photoUpload');
            if (fileInput.files.length === 0) return;

            const formData = new FormData();
            formData.append('photo', fileInput.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            Swal.fire({
                title: 'Uploading Photo...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // Reusing update route logic - we'll send a partial update
            fetch(`/update-employee/${currentUserId}`, {
                method: 'POST', 
                body: formData,
                // The backend handles this as an update
            })
            .then(r => r.ok ? r.json() : r.json().then(e => { throw e; }))

            .then(data => {
                Swal.fire('Updated', 'Profile image has been synchronized.', 'success');
                // Refresh photo in drawer
                viewEmployee(currentUserId);
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Failed', 'Image optimization or storage failed.', 'error');
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success Operation',
                text: '{{ session("success") }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000
            });
        @endif

        @if(session('error'))
            Swal.fire('Operation Error', '{{ session("error") }}', 'error');
        @endif

        // Bulk Selection Logic
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.getElementsByClassName('user-checkbox');
        const bulkBtn = document.getElementById('bulk-delete-btn');
        const selectedCount = document.getElementById('selected-count');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                Array.from(checkboxes).forEach(cb => cb.checked = selectAll.checked);
                updateBulkButton();
            });
        }

        function updateBulkButton() {
            const checked = Array.from(checkboxes).filter(cb => cb.checked);
            selectedCount.innerText = checked.length;
            if (checked.length > 0) {
                bulkBtn.classList.remove('d-none');
            } else {
                bulkBtn.classList.add('d-none');
            }
        }

        function bulkDelete() {
            const checkedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (checkedIds.length === 0) return;

            Swal.fire({
                title: 'Terminate Selection?',
                text: `You are about to permanently remove ${checkedIds.length} staff records. This action is irreversible.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Purge Records',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Purging...',
                        didOpen: () => { Swal.showLoading(); }
                    });

                    fetch('{{ route("employees.bulk-delete") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ ids: checkedIds })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Completed', data.message, 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message || 'Bulk deletion failed.', 'error');
                        }
                    })
                    .catch(err => Swal.fire('Error', 'Communication with server failed.', 'error'));
                }
            });
        }
    </script>
</body>
</html>
