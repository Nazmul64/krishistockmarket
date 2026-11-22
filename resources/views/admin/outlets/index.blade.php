@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-map-marker text-warning me-2"></i> আউটলেট ও ব্রাঞ্চ ব্যবস্থাপনা (Our Outlets Manager)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> ড্যাশবোর্ড</a></li>
                                <li class="breadcrumb-item active" aria-current="page">আউটলেট ও ব্রাঞ্চসমূহ</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('business.outlets') }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                        <i class="fa fa-external-link me-1"></i> লাইভ প্রিভিউ দেখুন
                    </a>
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addOutletModal">
                        <i class="fa fa-plus me-1"></i> নতুন আউটলেট যুক্ত করুন
                    </button>
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

            <div class="box rounded-4 shadow-sm border-0">
                <div class="box-header with-border py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <h5 class="box-title fw-bold text-dark mb-0">সকল সক্রিয় আউটলেট তালিকা (মোট: {{ $outlets->total() }} টি)</h5>

                    <form action="{{ route('admin.outlets.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm" style="width: 260px;">
                            <input type="text" name="search" class="form-control" placeholder="ব্রাঞ্চ নাম, কোড বা ঠিকানা..." value="{{ $search }}">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>

                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-14">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 120px;">কোড</th>
                                    <th>আউটলেট / ব্রাঞ্চের নাম</th>
                                    <th>ঠিকানা</th>
                                    <th>মোবাইল নম্বর</th>
                                    <th>বন্ধের দিন</th>
                                    <th>গুগল ম্যাপ লিঙ্ক</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end pe-4">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outlets as $outlet)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light text-dark border px-2 py-1 fw-bold font-size-12">
                                                {{ $outlet->code }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-dark font-size-15">
                                            {{ $outlet->name }}
                                            @if($outlet->area)
                                                <small class="text-muted d-block font-size-11">{{ $outlet->area }}, {{ $outlet->district }}</small>
                                            @endif
                                        </td>
                                        <td style="max-width: 250px;" class="text-truncate" title="{{ $outlet->address }}">
                                            <i class="fa fa-map-marker text-danger me-1"></i>{{ $outlet->address }}
                                        </td>
                                        <td>
                                            <a href="tel:{{ $outlet->phone }}" class="fw-bold text-dark text-decoration-none">
                                                <i class="fa fa-phone text-success me-1"></i>{{ $outlet->phone }}
                                            </a>
                                        </td>
                                        <td>
                                            @if(strtolower($outlet->weekly_off_day) === 'daily open' || str_contains(strtolower($outlet->weekly_off_day), 'open'))
                                                <span class="badge bg-success font-size-11">Open 7 Days</span>
                                            @else
                                                <span class="badge bg-secondary font-size-11">{{ ucfirst($outlet->weekly_off_day) }} Off</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($outlet->google_map_url)
                                                <a href="{{ $outlet->google_map_url }}" target="_blank" class="btn btn-xs btn-outline-warning rounded-pill px-2">
                                                    <i class="fa fa-map-o me-1"></i> ম্যাপ দেখুন
                                                </a>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($outlet->status === 'active')
                                                <span class="badge bg-success">সক্রিয়</span>
                                            @else
                                                <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-xs btn-outline-primary me-1 edit-outlet-btn"
                                                data-bs-toggle="modal" data-bs-target="#editOutletModal"
                                                data-outlet="{{ json_encode($outlet) }}">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <form action="{{ route('admin.outlets.destroy', $outlet->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই আউটলেটটি মুছে ফেলতে চান?');">
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
                                            <i class="fa fa-map-marker fs-2 mb-2"></i>
                                            <p class="mb-0">কোনো আউটলেট পাওয়া যায়নি।</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($outlets->hasPages())
                    <div class="box-footer p-3">
                        {{ $outlets->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add Outlet -->
<div class="modal fade" id="addOutletModal" tabindex="-1" aria-labelledby="addOutletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light py-3 px-4 border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="addOutletModalLabel">
                    <i class="fa fa-plus-circle text-primary me-2"></i>নতুন আউটলেট যুক্ত করুন
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.outlets.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">আউটলেট / ব্রাঞ্চের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="যেমন: ekrishisheba - Mohammadpur (Ring Road Branch)" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">আউটলেট কোড <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="যেমন: OUTLET - 19" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control" placeholder="+880 1XXXXXXXXX" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ইমেইল (ঐচ্ছিক)</label>
                            <input type="email" name="email" class="form-control" placeholder="branch@ekrishisheba.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">পূর্ণ ঠিকানা <span class="text-danger">*</span></label>
                            <textarea name="address" rows="2" class="form-control" placeholder="দোকান নং, ফ্লোর, ভবন, রোড, থানা, জেলা" required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">এলাকা (Area)</label>
                            <input type="text" name="area" class="form-control" placeholder="যেমন: Mohammadpur">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">জেলা (District)</label>
                            <input type="text" name="district" class="form-control" placeholder="যেমন: Dhaka">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">বিভাগ (Division)</label>
                            <input type="text" name="division" class="form-control" placeholder="যেমন: Dhaka">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">সাপ্তাহিক বন্ধের দিন</label>
                            <select name="weekly_off_day" class="form-select">
                                <option value="Daily Open">Daily Open (সপ্তাহে প্রতিদিন খোলা)</option>
                                <option value="Friday">Friday (শুক্রবার বন্ধ)</option>
                                <option value="Thursday">Thursday (বৃহস্পতিবার বন্ধ)</option>
                                <option value="Wednesday">Wednesday (বুধবার বন্ধ)</option>
                                <option value="Tuesday">Tuesday (মঙ্গলবার বন্ধ)</option>
                                <option value="Monday">Monday (সোমবার বন্ধ)</option>
                                <option value="Sunday">Sunday (রবিবার বন্ধ)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">খোলা থাকার স্ট্যাটাস টেক্সট</label>
                            <input type="text" name="open_days_text" class="form-control" value="Open 7 Days" placeholder="যেমন: Open 7 Days বা Open 6 Days">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">গুগল ম্যাপ লোকেশন লিঙ্ক (Google Map URL)</label>
                            <input type="url" name="google_map_url" class="form-control" placeholder="https://maps.google.com/?q=...">
                            <small class="text-muted">গ্রাহক ম্যাপ বাটনে ক্লিক করলে সরাসরি এই লোকেশনে গুগল ম্যাপ ওপেন হবে।</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">স্ট্যাটাস</label>
                            <select name="status" class="form-select">
                                <option value="active">সক্রিয় (Active)</option>
                                <option value="inactive">নিষ্ক্রিয় (Inactive)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ক্রম / সাজানোর সিরিয়াল (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary fw-bold">আউটলেট সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Outlet -->
<div class="modal fade" id="editOutletModal" tabindex="-1" aria-labelledby="editOutletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light py-3 px-4 border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="editOutletModalLabel">
                    <i class="fa fa-pencil text-warning me-2"></i>আউটলেট তথ্য সম্পাদনা
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editOutletForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">আউটলেট / ব্রাঞ্চের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">আউটলেট কোড <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="edit_code" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" id="edit_phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ইমেইল</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">পূর্ণ ঠিকানা <span class="text-danger">*</span></label>
                            <textarea name="address" id="edit_address" rows="2" class="form-control" required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">এলাকা (Area)</label>
                            <input type="text" name="area" id="edit_area" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">জেলা (District)</label>
                            <input type="text" name="district" id="edit_district" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">বিভাগ (Division)</label>
                            <input type="text" name="division" id="edit_division" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">সাপ্তাহিক বন্ধের দিন</label>
                            <select name="weekly_off_day" id="edit_weekly_off_day" class="form-select">
                                <option value="Daily Open">Daily Open (সপ্তাহে প্রতিদিন খোলা)</option>
                                <option value="Friday">Friday (শুক্রবার বন্ধ)</option>
                                <option value="Thursday">Thursday (বৃহস্পতিবার বন্ধ)</option>
                                <option value="Wednesday">Wednesday (বুধবার বন্ধ)</option>
                                <option value="Tuesday">Tuesday (মঙ্গলবার বন্ধ)</option>
                                <option value="Monday">Monday (সোমবার বন্ধ)</option>
                                <option value="Sunday">Sunday (রবিবার বন্ধ)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">খোলা থাকার স্ট্যাটাস টেক্সট</label>
                            <input type="text" name="open_days_text" id="edit_open_days_text" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">গুগল ম্যাপ লোকেশন লিঙ্ক (Google Map URL)</label>
                            <input type="url" name="google_map_url" id="edit_google_map_url" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">স্ট্যাটাস</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="active">সক্রিয় (Active)</option>
                                <option value="inactive">নিষ্ক্রিয় (Inactive)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ক্রম / সাজানোর সিরিয়াল</label>
                            <input type="number" name="sort_order" id="edit_sort_order" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary fw-bold">আপডেট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-outlet-btn');
    const editForm = document.getElementById('editOutletForm');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const o = JSON.parse(this.dataset.outlet);
            editForm.action = `/outlets-manager/update/${o.id}`;

            document.getElementById('edit_name').value = o.name || '';
            document.getElementById('edit_code').value = o.code || '';
            document.getElementById('edit_phone').value = o.phone || '';
            document.getElementById('edit_email').value = o.email || '';
            document.getElementById('edit_address').value = o.address || '';
            document.getElementById('edit_area').value = o.area || '';
            document.getElementById('edit_district').value = o.district || '';
            document.getElementById('edit_division').value = o.division || '';
            document.getElementById('edit_weekly_off_day').value = o.weekly_off_day || 'Daily Open';
            document.getElementById('edit_open_days_text').value = o.open_days_text || 'Open 7 Days';
            document.getElementById('edit_google_map_url').value = o.google_map_url || '';
            document.getElementById('edit_status').value = o.status || 'active';
            document.getElementById('edit_sort_order').value = o.sort_order || 0;
        });
    });
});
</script>
@endsection
