import io
import os

filepath = r'd:\HR System\sms-final\resources\views\home.blade.php'

with open(filepath, 'r', encoding='utf-8') as f:
    lines = f.readlines()

new_html = r"""                </div>
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
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0" 
                                           placeholder="Search by name, designation, NIC..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3">Execute Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-white border-bottom">
                                <tr>
                                    <th class="ps-4">Personnel</th>
                                    <th>Designation</th>
                                    <th>Workplace</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                <tr>
                                    <td class="ps-4">
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
                                        <button class="btn btn-sm btn-outline-primary px-3 rounded-pill" onclick="viewEmployee({{ $employee->id }})">
                                            Profile View
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
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
    </div> <!-- Ensuring container pb-5 is closed properly -->

    <style>
        /* High-End Drawer Theme Variables */
        :root {
            --drawer-bg: #f8fafc;
            --drawer-primary: #1e3a8a; /* Deep Blue */
            --drawer-primary-light: #eff6ff;
            --drawer-accent: #10b981; /* Emerald Green */
            --drawer-text-main: #0f172a;
            --drawer-text-muted: #64748b;
            --drawer-border: #e2e8f0;
        }

        /* Base Drawer Styles */
        .premium-drawer.offcanvas-end {
            width: 650px !important;
            border-left: none;
            box-shadow: -15px 0 40px rgba(0,0,0,0.15);
            background-color: var(--drawer-bg);
        }

        .premium-drawer-header {
            background: linear-gradient(135deg, var(--drawer-primary) 0%, #1e40af 100%);
            position: relative;
            padding: 2.5rem 2rem 3rem;
            color: white;
            border-bottom: 5px solid var(--drawer-accent);
        }

        /* Header Elements */
        .premium-drawer-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: rgba(255,255,255,0.15);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .premium-drawer-close:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }

        .profile-hero {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        /* Photo Container */
        .premium-photo-wrapper {
            position: relative;
            width: 130px;
            height: 130px;
            flex-shrink: 0;
        }

        .premium-profile-img {
            width: 100%;
            height: 100%;
            border-radius: 20px;
            object-fit: cover;
            border: 4px solid rgba(255,255,255,0.2);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            background: white;
            transition: all 0.3s ease;
        }

        .premium-photo-wrapper:hover .premium-profile-img {
            border-color: white;
            transform: translateY(-2px);
        }

        .premium-photo-edit {
            position: absolute;
            bottom: -10px;
            right: -10px;
            background: var(--drawer-accent);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid var(--drawer-primary);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }

        .premium-photo-edit:hover {
            transform: scale(1.1) rotate(15deg);
            background: #059669;
        }

        /* Profile Info */
        .hero-info {
            flex-grow: 1;
        }

        .hero-info h3 {
            font-weight: 800;
            margin: 0 0 0.25rem;
            font-size: 1.8rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 0.75rem;
            font-weight: 500;
        }

        .badge-grade {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            backdrop-filter: blur(4px);
        }

        /* Tabs Styling */
        .premium-tabs {
            padding: 0 2rem;
            background: white;
            border-bottom: 1px solid var(--drawer-border);
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 1.5rem;
            margin-top: -1px;
        }

        .premium-tabs::-webkit-scrollbar {
            display: none;
        }

        .premium-tabs .nav-link {
            border: none;
            color: var(--drawer-text-muted);
            font-weight: 600;
            padding: 1.25rem 0.25rem;
            margin-bottom: -1px;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .premium-tabs .nav-link:hover {
            color: var(--drawer-primary);
        }

        .premium-tabs .nav-link.active {
            color: var(--drawer-primary);
            border-bottom-color: var(--drawer-accent);
            background: transparent;
        }

        .premium-tabs .nav-link i {
            margin-right: 0.5rem;
            font-size: 1.1em;
        }

        /* Content Area */
        .drawer-body-scroll {
            overflow-y: auto;
            padding: 2rem;
            height: calc(100vh - 270px);
        }

        /* Grid & Items */
        .data-section {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            border: 1px solid var(--drawer-border);
        }

        .section-title {
            color: var(--drawer-primary);
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: var(--drawer-accent);
        }

        .data-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        .data-grid.full {
            grid-template-columns: 1fr;
        }

        .data-item {
            background: var(--drawer-bg);
            padding: 1rem;
            border-radius: 10px;
            border-left: 4px solid var(--drawer-primary-light);
            transition: all 0.2s;
        }

        .data-item:hover {
            border-left-color: var(--drawer-primary);
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .data-item.accent {
            border-left-color: var(--drawer-accent);
        }

        .data-item.accent:hover {
            border-left-color: #059669;
        }

        .data-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--drawer-text-muted);
            margin-bottom: 0.25rem;
            letter-spacing: 0.5px;
        }

        .data-value {
            font-weight: 600;
            color: var(--drawer-text-main);
            font-size: 0.95rem;
            line-height: 1.4;
        }

        /* Custom Scrollbar */
        .drawer-body-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .drawer-body-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .drawer-body-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .drawer-body-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <!-- Premium Side Drawer (Offcanvas) for Record View -->
    <div class="offcanvas offcanvas-end premium-drawer" tabindex="-1" id="employeeDrawer" aria-labelledby="employeeDrawerLabel">
        <div class="premium-drawer-header">
            <button type="button" class="premium-drawer-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="profile-hero">
                <div class="premium-photo-wrapper">
                    <img id="d-photo" src="" class="premium-profile-img" alt="Profile Photo">
                    <label for="photoUpload" class="premium-photo-edit" title="Update Photo">
                        <i class="fas fa-camera"></i>
                    </label>
                    <form id="photo-form" enctype="multipart/form-data" class="d-none">
                        @csrf
                        <input type="file" id="photoUpload" name="photo" accept="image/*">
                    </form>
                </div>
                <div class="hero-info">
                    <h3 id="d-name">Syncing...</h3>
                    <div class="hero-subtitle" id="d-designation">Loading designation</div>
                    <div class="d-flex gap-2" id="d-grade-badge">
                        <!-- Grade badge injected here -->
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs premium-tabs" id="drawerTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                    <i class="fas fa-user-circle"></i> Personal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#service" type="button" role="tab">
                    <i class="fas fa-briefcase"></i> Service
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#quals" type="button" role="tab">
                    <i class="fas fa-award"></i> Qualifications
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#leaves" type="button" role="tab">
                    <i class="fas fa-calendar-check"></i> Leaves
                </button>
            </li>
        </ul>

        <div class="drawer-body-scroll bg-light">
            <div class="tab-content" id="drawerTabsContent">
                
                <!-- PERSONAL TAB -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <div class="data-section">
                        <h5 class="section-title"><i class="fas fa-id-card"></i> Core Identity</h5>
                        <div class="data-grid full">
                            <div class="data-item">
                                <div class="data-label">Full Name with Initials</div>
                                <div class="data-value" id="d-fullname-initials">—</div>
                            </div>
                        </div>
                        <div class="data-grid mt-3">
                            <div class="data-item">
                                <div class="data-label">NIC Number</div>
                                <div class="data-value" id="d-nic">—</div>
                            </div>
                            <div class="data-item">
                                <div class="data-label">Marital Status</div>
                                <div class="data-value" id="d-marital">—</div>
                            </div>
                        </div>
                    </div>

                    <div class="data-section">
                        <h5 class="section-title"><i class="fas fa-map-marked-alt"></i> Contact & Location</h5>
                        <div class="data-grid">
                            <div class="data-item accent">
                                <div class="data-label">Mobile Number</div>
                                <div class="data-value"><i class="fas fa-phone-alt text-muted me-2 small"></i><span id="d-mobile">N/A</span></div>
                            </div>
                            <div class="data-item accent">
                                <div class="data-label">WhatsApp Number</div>
                                <div class="data-value"><i class="fab fa-whatsapp text-success me-2 small"></i><span id="d-whatsapp">N/A</span></div>
                            </div>
                        </div>
                        <div class="data-grid full mt-3">
                            <div class="data-item">
                                <div class="data-label">Permanent Address</div>
                                <div class="data-value" id="d-address">—</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SERVICE TAB -->
                <div class="tab-pane fade" id="service" role="tabpanel">
                    <div class="data-section">
                        <h5 class="section-title"><i class="fas fa-building"></i> Current Assignment</h5>
                        <div class="data-grid full">
                            <div class="data-item">
                                <div class="data-label">Current Workplace</div>
                                <div class="data-value fw-bold text-primary" id="d-workplace">—</div>
                            </div>
                        </div>
                        <div class="data-grid mt-3">
                            <div class="data-item">
                                <div class="data-label">W&OP Number</div>
                                <div class="data-value" id="d-wop">—</div>
                            </div>
                            <div class="data-item">
                                <div class="data-label">First Appointment</div>
                                <div class="data-value" id="d-first-appointment">—</div>
                            </div>
                        </div>
                    </div>

                    <div class="data-section">
                        <h5 class="section-title"><i class="fas fa-history"></i> Career Progression</h5>
                        <div class="data-grid full">
                            <div class="data-item">
                                <div class="data-label">Service History</div>
                                <div class="data-value" id="d-history" style="white-space: pre-wrap;">—</div>
                            </div>
                            <div class="data-item mt-3">
                                <div class="data-label">Efficiency Bar (EB) Exams</div>
                                <div class="data-value" id="d-ebexams">—</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QUALIFICATIONS TAB -->
                <div class="tab-pane fade" id="quals" role="tabpanel">
                    <div class="data-section">
                        <h5 class="section-title"><i class="fas fa-university"></i> Academic</h5>
                        <div class="data-grid full">
                            <div class="data-item">
                                <div class="data-label">Educational Qualifications</div>
                                <div class="data-value" id="d-eduquals" style="white-space: pre-wrap;">—</div>
                            </div>
                        </div>
                    </div>

                    <div class="data-section">
                        <h5 class="section-title"><i class="fas fa-certificate"></i> Professional</h5>
                        <div class="data-grid full">
                            <div class="data-item">
                                <div class="data-label">Professional Qualifications</div>
                                <div class="data-value" id="d-profquals" style="white-space: pre-wrap;">—</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LEAVES TAB -->
                <div class="tab-pane fade" id="leaves" role="tabpanel">
                    <div class="data-section">
                        <h5 class="section-title"><i class="fas fa-chart-pie"></i> Annual Leave Balance ({{ date('Y') }})</h5>
                        <div class="row g-3 text-center mt-2">
                            <div class="col-4">
                                <div class="p-3 rounded-3" style="background: #ecfdf5; border: 1px solid #10b981;">
                                    <div class="text-uppercase small fw-bold" style="color: #065f46;">Casual</div>
                                    <div class="fs-4 fw-bold mt-1" style="color: #047857;"><span id="d-casual">0</span><span class="fs-6 text-muted fw-normal">/24</span></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded-3" style="background: #fffbeb; border: 1px solid #f59e0b;">
                                    <div class="text-uppercase small fw-bold" style="color: #b45309;">Sick</div>
                                    <div class="fs-4 fw-bold mt-1" style="color: #92400e;"><span id="d-sick">0</span><span class="fs-6 text-muted fw-normal">/21</span></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded-3" style="background: #eff6ff; border: 1px solid #3b82f6;">
                                    <div class="text-uppercase small fw-bold" style="color: #1d4ed8;">Taken</div>
                                    <div class="fs-4 fw-bold mt-1" style="color: #1e40af;"><span id="d-total">0</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Footer Area -->
        <div class="p-4 border-top" style="background: white; z-index: 10; box-shadow: 0 -4px 10px rgba(0,0,0,0.02)">
            <div class="row g-3">
                <div class="col-6">
                    <a id="d-edit-btn" href="#" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm d-flex justify-content-center align-items-center">
                        <i class="fas fa-user-edit me-2"></i> Update Profile
                    </a>
                </div>
                <div class="col-6">
                    <form id="d-delete-form" method="POST" onsubmit="return confirm('Execute permanent removal?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-bold py-2 d-flex justify-content-center align-items-center">
                            <i class="fas fa-user-times me-2"></i> Terminate
                        </button>
                    </form>
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
                            <code>name, nic, designation, workplace, appointment_date, phone, address, salary</code></p>
                            <hr class="my-2">
                            <a href="{{ route('admin.staff.template') }}" class="small fw-bold text-decoration-none">
                                <i class="fas fa-download me-1"></i> Download Protocol Template
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
</body>
</html>
"""

with open(filepath, 'w', encoding='utf-8') as f:
    f.writelines(lines[:253])
    f.write(new_html)

print("Patch applied successfully.")
