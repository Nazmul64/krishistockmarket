@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-briefcase text-warning me-2"></i> বিজনেস আবেদন ও নিবন্ধনসমূহ (Business Applications)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> ড্যাশবোর্ড</a></li>
                                <li class="breadcrumb-item active" aria-current="page">বিজনেস আবেদনসমূহ</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark px-3 py-2 font-size-14 rounded-pill">
                        অপেক্ষমান আবেদন: {{ $counts['pending'] }} টি
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4 p-3" style="border-left: 4px solid #10b981 !important;">
                    <i class="fa fa-check-circle me-1 text-success"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 5 Program Category Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <a href="{{ route('admin.business_applications.index', ['type' => 'investor']) }}" class="text-decoration-none">
                        <div class="box mb-0 shadow-sm rounded-4 border {{ $type === 'investor' ? 'border-primary' : '' }}" style="border-left: 5px solid #2563eb !important;">
                            <div class="box-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-bold text-uppercase">ইনভেস্টর আবেদন</div>
                                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ $counts['investor'] }}</h3>
                                    </div>
                                    <span class="avatar avatar-lg rounded-circle bg-primary-light text-primary">
                                        <i class="fa fa-line-chart font-size-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <a href="{{ route('admin.business_applications.index', ['type' => 'wholesale']) }}" class="text-decoration-none">
                        <div class="box mb-0 shadow-sm rounded-4 border {{ $type === 'wholesale' ? 'border-warning' : '' }}" style="border-left: 5px solid #ea580c !important;">
                            <div class="box-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-bold text-uppercase">হোলসেল আবেদন</div>
                                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ $counts['wholesale'] }}</h3>
                                    </div>
                                    <span class="avatar avatar-lg rounded-circle bg-warning-light text-warning">
                                        <i class="fa fa-shopping-cart font-size-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <a href="{{ route('admin.business_applications.index', ['type' => 'franchise']) }}" class="text-decoration-none">
                        <div class="box mb-0 shadow-sm rounded-4 border {{ $type === 'franchise' ? 'border-info' : '' }}" style="border-left: 5px solid #b45309 !important;">
                            <div class="box-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-bold text-uppercase">ফ্র্যাঞ্চাইজি আবেদন</div>
                                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ $counts['franchise'] }}</h3>
                                    </div>
                                    <span class="avatar avatar-lg rounded-circle text-white" style="background: #b45309;">
                                        <i class="fa fa-building font-size-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <a href="{{ route('admin.business_applications.index', ['type' => 'umrah_haj']) }}" class="text-decoration-none">
                        <div class="box mb-0 shadow-sm rounded-4 border {{ $type === 'umrah_haj' ? 'border-success' : '' }}" style="border-left: 5px solid #047857 !important;">
                            <div class="box-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-bold text-uppercase">উমরাহ/হজ নিবন্ধন</div>
                                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ $counts['umrah_haj'] }}</h3>
                                    </div>
                                    <span class="avatar avatar-lg rounded-circle bg-success-light text-success">
                                        <i class="fa fa-moon-o font-size-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Table Card with Filter & Search -->
            <div class="box rounded-4 shadow-sm border-0">
                <div class="box-header with-border py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <!-- Category Tabs -->
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link rounded-pill font-size-13 px-3 {{ $type === 'all' ? 'active' : '' }}" href="{{ route('admin.business_applications.index', ['type' => 'all']) }}">
                                সব আবেদন ({{ $counts['all'] }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill font-size-13 px-3 {{ $type === 'investor' ? 'active' : '' }}" href="{{ route('admin.business_applications.index', ['type' => 'investor']) }}">
                                ইনভেস্টর
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill font-size-13 px-3 {{ $type === 'wholesale' ? 'active' : '' }}" href="{{ route('admin.business_applications.index', ['type' => 'wholesale']) }}">
                                হোলসেল
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill font-size-13 px-3 {{ $type === 'franchise' ? 'active' : '' }}" href="{{ route('admin.business_applications.index', ['type' => 'franchise']) }}">
                                ফ্র্যাঞ্চাইজি
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill font-size-13 px-3 {{ $type === 'umrah_haj' ? 'active' : '' }}" href="{{ route('admin.business_applications.index', ['type' => 'umrah_haj']) }}">
                                উমরাহ/হজ
                            </a>
                        </li>
                    </ul>

                    <!-- Search & Status Filter Form -->
                    <form action="{{ route('admin.business_applications.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 140px;">
                            <option value="">সকল স্ট্যাটাস</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>অপেক্ষমান (Pending)</option>
                            <option value="contacted" {{ $status === 'contacted' ? 'selected' : '' }}>যোগাযোগ হয়েছে</option>
                            <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>অনুমোদিত</option>
                            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>বাতিল</option>
                        </select>
                        <div class="input-group input-group-sm" style="width: 220px;">
                            <input type="text" name="search" class="form-control" placeholder="নাম, ফোন বা ঠিকানা..." value="{{ $search }}">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>

                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-14">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">আবেদনের ধরন</th>
                                    <th>আবেদনকারীর নাম</th>
                                    <th>মোবাইল নম্বর</th>
                                    <th>ঠিকানা</th>
                                    <th>বিশেষ বিবরণী</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>তারিখ</th>
                                    <th class="text-end pe-4">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $app)
                                    <tr>
                                        <td class="ps-4">
                                            @if($app->type === 'investor')
                                                <span class="badge bg-primary px-2 py-1"><i class="fa fa-line-chart me-1"></i> ইনভেস্টর</span>
                                            @elseif($app->type === 'wholesale')
                                                <span class="badge bg-warning px-2 py-1"><i class="fa fa-shopping-cart me-1"></i> হোলসেল</span>
                                            @elseif($app->type === 'franchise')
                                                <span class="badge text-white px-2 py-1" style="background: #b45309;"><i class="fa fa-building me-1"></i> ফ্র্যাঞ্চাইজি</span>
                                            @elseif($app->type === 'umrah_haj')
                                                <span class="badge bg-success px-2 py-1"><i class="fa fa-moon-o me-1"></i> উমরাহ/হজ</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-dark">
                                            {{ $app->name }}
                                            @if($app->profession)
                                                <small class="text-muted d-block font-size-11">({{ $app->profession }})</small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="tel:{{ $app->phone }}" class="fw-bold text-decoration-none text-dark">
                                                <i class="fa fa-phone text-success me-1"></i>{{ $app->phone }}
                                            </a>
                                            @if($app->whatsapp_number)
                                                <a href="https://wa.me/88{{ preg_replace('/[^0-9]/', '', $app->whatsapp_number) }}" target="_blank" class="badge bg-success ms-1 font-size-10" title="WhatsApp Message">
                                                    <i class="fa fa-whatsapp"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td style="max-width: 220px;" class="text-truncate" title="{{ $app->address }}">
                                            {{ $app->address }}
                                        </td>
                                        <td>
                                            @if($app->type === 'investor')
                                                <span class="fw-bold text-success">বিনিয়োগ: {{ $app->amount }}</span>
                                            @elseif($app->type === 'wholesale')
                                                <span class="text-muted">পরিমাণ: {{ $app->quantity ?: 'N/A' }}</span>
                                            @elseif($app->type === 'franchise')
                                                <span class="text-muted">সাইজ: {{ $app->showroom_size }} ({{ $app->district }})</span>
                                            @elseif($app->type === 'umrah_haj')
                                                <span class="text-muted">বয়স: {{ $app->age }} | পাসপোর্ট: {{ $app->has_passport }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! $app->status_badge !!}
                                        </td>
                                        <td class="small text-muted">
                                            {{ $app->created_at->format('d M, Y h:i A') }}
                                        </td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-xs btn-outline-info me-1 view-details-btn" 
                                                data-bs-toggle="modal" data-bs-target="#viewAppModal"
                                                data-app="{{ json_encode($app) }}">
                                                <i class="fa fa-eye"></i> বিস্তারিত
                                            </button>

                                            <form action="{{ route('admin.business_applications.destroy', $app->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই আবেদনটি মুছে ফেলতে চান?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-outline-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="fa fa-folder-open-o fs-2 mb-2"></i>
                                            <p class="mb-0">কোনো আবেদন পাওয়া যায়নি।</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($applications->hasPages())
                    <div class="box-footer p-3">
                        {{ $applications->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Modal: View Application Details & Update Status -->
<div class="modal fade" id="viewAppModal" tabindex="-1" aria-labelledby="viewAppModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light py-3 px-4 border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="viewAppModalLabel">
                    <i class="fa fa-file-text-o text-primary me-2"></i>আবেদনের সম্পূর্ণ বিবরণী
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="modalAppContent">
                    <!-- Dynamic Content populated via JS -->
                </div>

                <!-- Update Status Form inside modal -->
                <form id="modalStatusForm" method="POST" action="" class="mt-4 pt-3 border-top">
                    @csrf
                    <h6 class="fw-bold text-dark mb-2">স্ট্যাটাস ও এডমিন নোট আপডেট:</h6>
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <select name="status" id="modalStatusSelect" class="form-select">
                                <option value="pending">অপেক্ষমান (Pending)</option>
                                <option value="contacted">যোগাযোগ হয়েছে (Contacted)</option>
                                <option value="approved">অনুমোদিত (Approved)</option>
                                <option value="rejected">বাতিল (Rejected)</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="admin_notes" id="modalNotesInput" class="form-control" placeholder="এডমিন নোট বা মন্তব্য লিখুন...">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100 fw-bold">সংরক্ষণ করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const viewButtons = document.querySelectorAll('.view-details-btn');
    const modalContent = document.getElementById('modalAppContent');
    const modalStatusForm = document.getElementById('modalStatusForm');
    const modalStatusSelect = document.getElementById('modalStatusSelect');
    const modalNotesInput = document.getElementById('modalNotesInput');

    viewButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const app = JSON.parse(this.dataset.app);
            
            // Set action URL
            modalStatusForm.action = `/business-applications/status/${app.id}`;
            modalStatusSelect.value = app.status;
            modalNotesInput.value = app.admin_notes || '';

            let detailsHtml = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">আবেদনের ধরন:</small>
                        <strong class="text-capitalize text-primary">${app.type}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">আবেদনের তারিখ:</small>
                        <strong>${new Date(app.created_at).toLocaleString()}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">নাম:</small>
                        <strong class="font-size-15">${app.name}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">মোবাইল:</small>
                        <strong class="text-success">${app.phone}</strong>
                    </div>
                    ${app.whatsapp_number ? `
                        <div class="col-md-6">
                            <small class="text-muted d-block">WhatsApp:</small>
                            <a href="https://wa.me/88${app.whatsapp_number.replace(/[^0-9]/g, '')}" target="_blank" class="fw-bold text-success">${app.whatsapp_number}</a>
                        </div>
                    ` : ''}
                    ${app.email ? `
                        <div class="col-md-6">
                            <small class="text-muted d-block">ইমেইল:</small>
                            <strong>${app.email}</strong>
                        </div>
                    ` : ''}
                    <div class="col-12">
                        <small class="text-muted d-block">ঠিকানা:</small>
                        <div class="p-2 bg-light rounded mt-1">${app.address || 'N/A'}</div>
                    </div>
                    ${app.profession ? `
                        <div class="col-md-6">
                            <small class="text-muted d-block">পেশা:</small>
                            <strong>${app.profession}</strong>
                        </div>
                    ` : ''}
                    ${app.amount ? `
                        <div class="col-md-6">
                            <small class="text-muted d-block">বিনিয়োগের পরিমাণ:</small>
                            <span class="badge bg-success font-size-13 px-2 py-1">${app.amount}</span>
                        </div>
                    ` : ''}
                    ${app.quantity ? `
                        <div class="col-md-6">
                            <small class="text-muted d-block">হোলসেল পণ্যের পরিমাণ:</small>
                            <span class="badge bg-warning font-size-13 px-2 py-1">${app.quantity}</span>
                        </div>
                    ` : ''}
                    ${app.showroom_size ? `
                        <div class="col-md-6">
                            <small class="text-muted d-block">শোরুমের সাইজ:</small>
                            <strong>${app.showroom_size}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">শোরুমের অবস্থান:</small>
                            <strong>${app.showroom_location || 'N/A'} (${app.district || ''})</strong>
                        </div>
                    ` : ''}
                    ${app.type === 'umrah_haj' ? `
                        <div class="col-md-4">
                            <small class="text-muted d-block">বয়স:</small>
                            <strong>${app.age}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">বৈবাহিক অবস্থা:</small>
                            <strong>${app.marital_status || 'N/A'}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">লিঙ্গ:</small>
                            <strong>${app.gender || 'N/A'}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">পাসপোর্ট:</small>
                            <strong>${app.has_passport}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">আর্থিক সক্ষমতা:</small>
                            <strong>${app.is_financially_capable}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">নির্বাচনের কারণ:</small>
                            <div class="p-2 bg-light rounded mt-1">${app.selection_reason || 'N/A'}</div>
                        </div>
                    ` : ''}
                    ${app.remarks ? `
                        <div class="col-12">
                            <small class="text-muted d-block">মন্তব্য:</small>
                            <div class="p-2 bg-light rounded mt-1">${app.remarks}</div>
                        </div>
                    ` : ''}
                </div>
            `;
            modalContent.innerHTML = detailsHtml;
        });
    });
});
</script>
@endsection
