@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-primary"><i class="fa fa-users me-2"></i> User Management (ব্যবহারকারী ব্যবস্থাপনা)</h3>
                    <p class="text-muted mb-0">আন্তর্জাতিক মানের RBAC, User Type, Role Assignment ও Account Lifecycle</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fa fa-user-plus me-1"></i> নতুন ব্যবহারকারী যোগ করুন (Add New User)
                    </button>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Flash Message Alerts -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Summary KPI Badges -->
            <div class="row g-3 mb-3">
                <div class="col-md-3 col-6">
                    <div class="box box-body p-3 bg-primary-light mb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">মোট ইউজার</span>
                                <h3 class="mb-0 fw-bold text-primary">{{ $totalUsers }}</h3>
                            </div>
                            <i class="fa fa-users text-primary font-size-24"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="box box-body p-3 bg-success-light mb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">সক্রিয় অ্যাকাউন্ট</span>
                                <h3 class="mb-0 fw-bold text-success">{{ $activeUsers }}</h3>
                            </div>
                            <i class="fa fa-check-circle text-success font-size-24"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="box box-body p-3 bg-warning-light mb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">অভ্যন্তরীণ কর্মী (Internal)</span>
                                <h3 class="mb-0 fw-bold text-warning">{{ $internalUsers }}</h3>
                            </div>
                            <i class="fa fa-briefcase text-warning font-size-24"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="box box-body p-3 bg-danger-light mb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">নিষ্ক্রিয়/স্থগিত (Locked)</span>
                                <h3 class="mb-0 fw-bold text-danger">{{ $inactiveUsers }}</h3>
                            </div>
                            <i class="fa fa-ban text-danger font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Box -->
            <div class="box mb-3">
                <div class="box-body py-3">
                    <form action="{{ route('admin.rbac.users') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-lg-4 col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="নাম, ইউজারনেম, ফোন বা ইমেইল লিখুন..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="user_type_id" class="form-select">
                                <option value="">-- ইউজার টাইপ --</option>
                                @foreach($userTypes as $type)
                                <option value="{{ $type->id }}" {{ request('user_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="role" class="form-select">
                                <option value="">-- রোল (Role) --</option>
                                @foreach($roles as $r)
                                <option value="{{ $r->code }}" {{ request('role') == $r->code ? 'selected' : '' }}>{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="status" class="form-select">
                                <option value="">-- স্ট্যাটাস --</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>স্থগিত (Suspended)</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                            <a href="{{ route('admin.rbac.users') }}" class="btn btn-outline btn-secondary" title="রিসেট"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table (Section 8.1) -->
            <div class="box">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px;">SL</th>
                                    <th>প্রোফাইল ও নাম</th>
                                    <th>ইউজারনেম</th>
                                    <th>যোগাযোগ (ফোন/ইমেইল)</th>
                                    <th>User Type</th>
                                    <th>Role (রোল)</th>
                                    <th>ডাটা স্কোপ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>রেজিস্ট্রেশন</th>
                                    <th class="text-end" style="min-width: 140px;">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $u)
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ !empty($u->avatar) && file_exists(public_path('upload/images/user/' . $u->avatar)) ? asset('upload/images/user/' . $u->avatar) : asset('assets/images/avatar/avatar-1.png') }}" class="avatar avatar-sm rounded-circle me-2 border" alt="">
                                            <div>
                                                <span class="fw-bold text-dark d-block">{{ $u->name }}</span>
                                                <small class="text-muted">ID: #{{ $u->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><code>{{ $u->username }}</code></td>
                                    <td>
                                        <div class="fw-bold font-size-13"><i class="fa fa-phone text-muted me-1"></i> {{ $u->phone ?? 'N/A' }}</div>
                                        <small class="text-muted"><i class="fa fa-envelope text-muted me-1"></i> {{ $u->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ optional($u->userType)->is_internal ? 'bg-primary-light text-primary' : 'bg-secondary-light text-dark' }}">
                                            {{ optional($u->userType)->name ?? 'External' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-light text-info fw-bold py-1 px-2 font-size-12">
                                            <i class="fa fa-shield me-1"></i> {{ optional($u->roleRelation)->name ?? strtoupper($u->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="fa fa-eye me-1"></i> {{ $u->data_scope ?? 'own' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($u->status === 'active')
                                            <span class="badge bg-success"><i class="fa fa-check me-1"></i> Active</span>
                                        @elseif($u->status === 'suspended')
                                            <span class="badge bg-danger"><i class="fa fa-ban me-1"></i> Suspended</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fa fa-pause me-1"></i> Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-muted font-size-12">
                                        {{ $u->created_at ? $u->created_at->format('d M, Y') : '-' }}
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <!-- Permissions Management Button -->
                                            <a href="{{ route('admin.rbac.users.permissions', $u->id) }}" class="btn btn-xs btn-info text-white" title="ডাইনামিক পারমিশন কনফিগারেশন">
                                                <i class="fa fa-key"></i>
                                            </a>

                                            <!-- Edit User Button -->
                                            <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $u->id }}" title="তথ্য ও রোল সম্পাদনা">
                                                <i class="fa fa-pencil"></i>
                                            </button>

                                            <!-- Toggle Status Dropdown -->
                                            <button type="button" class="btn btn-xs btn-outline btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                                <li><h6 class="dropdown-header">অ্যাকাউন্ট কন্ট্রোল</h6></li>
                                                @if($u->status !== 'active')
                                                <li>
                                                    <form action="{{ route('admin.rbac.users.toggle_status', $u->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="active">
                                                        <button type="submit" class="dropdown-item text-success"><i class="fa fa-check-circle me-2"></i> সক্রিয় করুন (Activate)</button>
                                                    </form>
                                                </li>
                                                @endif

                                                @if($u->status !== 'inactive')
                                                <li>
                                                    <form action="{{ route('admin.rbac.users.toggle_status', $u->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="inactive">
                                                        <button type="submit" class="dropdown-item text-warning"><i class="fa fa-pause-circle me-2"></i> নিষ্ক্রিয় করুন (Deactivate)</button>
                                                    </form>
                                                </li>
                                                @endif

                                                @if($u->status !== 'suspended')
                                                <li>
                                                    <form action="{{ route('admin.rbac.users.toggle_status', $u->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="suspended">
                                                        <button type="submit" class="dropdown-item text-danger"><i class="fa fa-ban me-2"></i> স্থগিত করুন (Suspend)</button>
                                                    </form>
                                                </li>
                                                @endif

                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.rbac.users.force_logout', $u->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('আপনি কি নিশ্চিত যে এই ইউজারের সেশন প্রত্যাহার করতে চান?')">
                                                            <i class="fa fa-sign-out me-2"></i> ফোর্স লগআউট (Revoke Sessions)
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit User Modal -->
                                <div class="modal fade" id="editUserModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.rbac.users.update', $u->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title"><i class="fa fa-user-edit me-2"></i> ইউজার ও রোল তথ্য সম্পাদনা (#{{ $u->username }})</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">পুরো নাম (Full Name) <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                                            <input type="text" name="phone" class="form-control" value="{{ $u->phone }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">ইমেইল ঠিকানা</label>
                                                            <input type="email" name="email" class="form-control" value="{{ $u->email }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">নতুন পাসওয়ার্ড (পরিবর্তন করতে চাইলে লিখুন)</label>
                                                            <input type="password" name="password" class="form-control" placeholder="অপরিবর্তিত রাখতে খালি রাখুন">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">User Type <span class="text-danger">*</span></label>
                                                            <select name="user_type_id" class="form-select" required>
                                                                @foreach($userTypes as $type)
                                                                <option value="{{ $type->id }}" {{ $u->user_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Assign Role (রোল অর্পণ) <span class="text-danger">*</span></label>
                                                            <select name="role_id" class="form-select" required>
                                                                @foreach($roles as $r)
                                                                <option value="{{ $r->id }}" {{ ($u->role_id == $r->id || $u->role == $r->code) ? 'selected' : '' }}>
                                                                    {{ $r->name }} (Scope: {{ $r->data_scope }})
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">ডাটা স্কোপ (Data Visibility Scope)</label>
                                                            <select name="data_scope" class="form-select">
                                                                <option value="all" {{ $u->data_scope === 'all' ? 'selected' : '' }}>All (সিস্টেমের সব রেকর্ড)</option>
                                                                <option value="team" {{ $u->data_scope === 'team' ? 'selected' : '' }}>Team (নিজের টিম)</option>
                                                                <option value="branch" {{ $u->data_scope === 'branch' ? 'selected' : '' }}>Branch (নির্দিষ্ট শাখা)</option>
                                                                <option value="assigned" {{ $u->data_scope === 'assigned' ? 'selected' : '' }}>Assigned (বরাদ্দকৃত কাজ/কেস)</option>
                                                                <option value="own" {{ $u->data_scope === 'own' ? 'selected' : '' }}>Own (শুধু নিজের রেকর্ড)</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">অ্যাকাউন্ট স্ট্যাটাস <span class="text-danger">*</span></label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="active" {{ $u->status === 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                                                                <option value="inactive" {{ $u->status === 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                                                                <option value="suspended" {{ $u->status === 'suspended' ? 'selected' : '' }}>স্থগিত (Suspended)</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label fw-bold">প্রোফাইল ছবি পরিবর্তন</label>
                                                            <input type="file" name="avatar" class="form-control" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> পরিবর্তন সংরক্ষণ করুন</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-5">
                                        <i class="fa fa-search-minus font-size-36 text-muted mb-2 d-block"></i>
                                        কোন ব্যবহারকারী পাওয়া যায়নি। ফিল্টার পরিবর্তন করে পুনরায় চেষ্টা করুন।
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                <div class="box-footer clearfix">
                    {{ $users->links() }}
                </div>
                @endif
            </div>

            <!-- Add New User Modal (Section 8.2) -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('admin.rbac.users.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title"><i class="fa fa-user-plus me-2"></i> নতুন ব্যবহারকারী তৈরি ও রোল অর্পণ (Add New User)</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">পুরো নাম (Full Name) <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="যেমন: মো: রফিকুল ইসলাম" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">ইউজারনেম (Username) <span class="text-danger">*</span></label>
                                        <input type="text" name="username" class="form-control" placeholder="যেমন: rofiq_agent" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" placeholder="যেমন: 01712345678" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">ইমেইল ঠিকানা</label>
                                        <input type="email" name="email" class="form-control" placeholder="name@domain.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">পাসওয়ার্ড <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" placeholder="কমপক্ষে ৬ ক্যারেক্টার" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="পুনরায় পাসওয়ার্ড লিখুন" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">User Type (শ্রেণি) <span class="text-danger">*</span></label>
                                        <select name="user_type_id" class="form-select" required>
                                            @foreach($userTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Assign Role (রোল অর্পণ) <span class="text-danger">*</span></label>
                                        <select name="role_id" class="form-select" required>
                                            @foreach($roles as $r)
                                            <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->category }}) - Scope: {{ $r->data_scope }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">ডাটা স্কোপ (Data Scope)</label>
                                        <select name="data_scope" class="form-select">
                                            <option value="all">All (সিস্টেমের সব রেকর্ড)</option>
                                            <option value="team">Team (টিম সদস্য ও রেকর্ড)</option>
                                            <option value="branch">Branch (শাখাভিত্তিক রেকর্ড)</option>
                                            <option value="assigned">Assigned (বরাদ্দকৃত কাজ)</option>
                                            <option value="own" selected>Own (শুধু নিজস্ব রেকর্ড)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">স্ট্যাটাস <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="active" selected>সক্রিয় (Active)</option>
                                            <option value="inactive">নিষ্ক্রিয় (Inactive)</option>
                                            <option value="suspended">স্থগিত (Suspended)</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">প্রোফাইল ছবি (ঐচ্ছিক)</label>
                                        <input type="file" name="avatar" class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check me-1"></i> ইউজার তৈরি করুন</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
