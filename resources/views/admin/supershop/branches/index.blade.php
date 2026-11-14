@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-home text-success me-2"></i>ব্রাঞ্চ ও রিটেল আউটলেট ম্যানেজমেন্ট (Multi-Branch Management)
                    </h3>
                    <p class="text-muted mb-0">সেন্ট্রাল অ্যাডমিন থেকে সুপারশপ চেইনের সকল শাখা, আউটলেট ও ওয়্যারহাউজ পরিচালনা</p>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#createBranchModal">
                        <i class="ti-plus me-1"></i> নতুন ব্রাঞ্চ যোগ করুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title fw-bold text-dark mb-0">সকল শাখা তালিকা ({{ $branches->count() }} টি ব্রাঞ্চ)</h4>
                            <span class="badge bg-success-light text-success">সেন্ট্রাল ক্যাটালগ ইন্টিগ্রেটেড</span>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ব্রাঞ্চ কোড ও নাম</th>
                                            <th>ধরন (Type)</th>
                                            <th>ব্রাঞ্চ ম্যানেজার</th>
                                            <th class="text-center">POS কাউন্টার</th>
                                            <th class="text-center">ইনভেন্টরি আইটেম</th>
                                            <th>ঠিকানা ও যোগাযোগ</th>
                                            <th class="text-center">অপারেটিং সময়</th>
                                            <th class="text-center">স্ট্যাটাস</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($branches as $branch)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-dark fw-bold me-1">{{ $branch->code }}</span>
                                                    <strong class="text-dark">{{ $branch->name }}</strong>
                                                    <div class="small text-muted">{{ $branch->area ? $branch->area . ', ' : '' }}{{ $branch->district }}</div>
                                                </td>
                                                <td>
                                                    @if($branch->type == 'super_shop')
                                                        <span class="badge bg-primary">সুপারশপ (Super Shop)</span>
                                                    @elseif($branch->type == 'warehouse')
                                                        <span class="badge bg-warning text-dark">ওয়্যারহাউজ (Hub)</span>
                                                    @else
                                                        <span class="badge bg-info">এক্সপ্রেস আউটলেট</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($branch->manager)
                                                        <div class="fw-bold text-dark small">{{ $branch->manager->name }}</div>
                                                        <div class="text-muted smaller">{{ $branch->manager->phone ?? $branch->manager->email }}</div>
                                                    @else
                                                        <span class="text-muted small italic">অ্যাসাইন করা হয়নি</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark border">
                                                        {{ $branch->posCounters->count() }} টি
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.supershop.inventory', ['branch_id' => $branch->id]) }}" class="badge bg-info-light text-info border-0">
                                                        {{ $branch->inventories_count }} টি পণ্য
                                                    </a>
                                                </td>
                                                <td class="small">
                                                    <div><i class="ti-location-pin text-danger me-1"></i> {{ Str::limit($branch->address, 35) }}</div>
                                                    @if($branch->phone)
                                                        <div><i class="ti-mobile text-success me-1"></i> {{ $branch->phone }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-center small text-muted">
                                                    {{ $branch->opening_time ? substr($branch->opening_time, 0, 5) : '08:00' }} - {{ $branch->closing_time ? substr($branch->closing_time, 0, 5) : '22:30' }}
                                                </td>
                                                <td class="text-center">
                                                    @if($branch->status == 'active')
                                                        <span class="badge bg-success">সক্রিয়</span>
                                                    @else
                                                        <span class="badge bg-secondary">নিষ্ক্রিয়</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.supershop.pos', ['branch_id' => $branch->id]) }}" class="btn btn-xs btn-success" title="POS টার্মিনাল খুলুন">
                                                        <i class="ti-shopping-cart"></i>
                                                    </a>
                                                    <a href="{{ route('admin.supershop.inventory', ['branch_id' => $branch->id]) }}" class="btn btn-xs btn-outline-info" title="ইনভেন্টরি">
                                                        <i class="ti-archive"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-xs btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBranchModal{{ $branch->id }}" title="এডিট">
                                                        <i class="ti-pencil"></i>
                                                    </button>
                                                    @if($branch->pos_sales_count == 0)
                                                        <a href="{{ route('admin.supershop.branches.destroy', $branch->id) }}" class="btn btn-xs btn-outline-danger" onclick="return confirm('আপনি কি এই ব্রাঞ্চ মুছে ফেলতে চান?')" title="মুছুন">
                                                            <i class="ti-trash"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>

                                            <!-- Edit Branch Modal -->
                                            <div class="modal fade" id="editBranchModal{{ $branch->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <form method="POST" action="{{ route('admin.supershop.branches.update', $branch->id) }}">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title fw-bold">ব্রাঞ্চ তথ্য পরিবর্তন – {{ $branch->name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body row g-3">
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">ব্রাঞ্চের নাম <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name" class="form-control" value="{{ $branch->name }}" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label fw-bold">ব্রাঞ্চ কোড <span class="text-danger">*</span></label>
                                                                    <input type="text" name="code" class="form-control" value="{{ $branch->code }}" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label fw-bold">ধরন (Type)</label>
                                                                    <select name="type" class="form-select">
                                                                        <option value="super_shop" {{ $branch->type == 'super_shop' ? 'selected' : '' }}>সুপারশপ (Super Shop)</option>
                                                                        <option value="express_outlet" {{ $branch->type == 'express_outlet' ? 'selected' : '' }}>এক্সপ্রেস আউটলেট</option>
                                                                        <option value="warehouse" {{ $branch->type == 'warehouse' ? 'selected' : '' }}>ওয়্যারহাউজ</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">ব্রাঞ্চ ম্যানেজার</label>
                                                                    <select name="manager_id" class="form-select">
                                                                        <option value="">-- ম্যানেজার নির্বাচন করুন --</option>
                                                                        @foreach($users as $user)
                                                                            <option value="{{ $user->id }}" {{ $branch->manager_id == $user->id ? 'selected' : '' }}>
                                                                                {{ $user->name }} ({{ $user->email }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">ফোন নম্বর</label>
                                                                    <input type="text" name="phone" class="form-control" value="{{ $branch->phone }}">
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <label class="form-label fw-bold">পূর্ণ ঠিকানা <span class="text-danger">*</span></label>
                                                                    <textarea name="address" class="form-control" rows="2" required>{{ $branch->address }}</textarea>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label class="form-label">বিভাগ</label>
                                                                    <input type="text" name="division" class="form-control" value="{{ $branch->division }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">জেলা</label>
                                                                    <input type="text" name="district" class="form-control" value="{{ $branch->district }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">এরিয়া/থানা</label>
                                                                    <input type="text" name="area" class="form-control" value="{{ $branch->area }}">
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label class="form-label">খোলার সময়</label>
                                                                    <input type="time" name="opening_time" class="form-control" value="{{ $branch->opening_time }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">বন্ধের সময়</label>
                                                                    <input type="time" name="closing_time" class="form-control" value="{{ $branch->closing_time }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label fw-bold">স্ট্যাটাস</label>
                                                                    <select name="status" class="form-select">
                                                                        <option value="active" {{ $branch->status == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                                                                        <option value="inactive" {{ $branch->status == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                                <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">কোনো ব্রাঞ্চ তৈরি করা হয়নি।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Create Branch Modal -->
        <div class="modal fade" id="createBranchModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('admin.supershop.branches.store') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title fw-bold text-white"><i class="ti-plus me-1"></i> নতুন সুপারশপ ব্রাঞ্চ যুক্ত করুন</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ব্রাঞ্চের নাম <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="যেমন: ধানমন্ডি শাখা (Dhanmondi Outlet)" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">ব্রাঞ্চ কোড <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control" placeholder="যেমন: BR-07, DHANMONDI-01" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">ধরন (Type)</label>
                                <select name="type" class="form-select">
                                    <option value="super_shop">সুপারশপ (Super Shop)</option>
                                    <option value="express_outlet">এক্সপ্রেস আউটলেট</option>
                                    <option value="warehouse">ওয়্যারহাউজ</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">ব্রাঞ্চ ম্যানেজার</label>
                                <select name="manager_id" class="form-select">
                                    <option value="">-- ম্যানেজার নির্বাচন করুন --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ফোন নম্বর</label>
                                <input type="text" name="phone" class="form-control" placeholder="017xxxxxxxx">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">পূর্ণ ঠিকানা <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control" rows="2" placeholder="বাড়ি #, রোড #, এরিয়া, জেলা..." required></textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">বিভাগ</label>
                                <input type="text" name="division" class="form-control" value="Dhaka">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">জেলা</label>
                                <input type="text" name="district" class="form-control" value="Dhaka">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">এরিয়া/থানা</label>
                                <input type="text" name="area" class="form-control" placeholder="যেমন: ধানমন্ডি ২৭">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">খোলার সময়</label>
                                <input type="time" name="opening_time" class="form-control" value="08:00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">বন্ধের সময়</label>
                                <input type="time" name="closing_time" class="form-control" value="22:30">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">স্ট্যাটাস</label>
                                <select name="status" class="form-select">
                                    <option value="active" selected>সক্রিয় (Active)</option>
                                    <option value="inactive">নিষ্ক্রিয় (Inactive)</option>
                                </select>
                            </div>

                            <div class="col-12 mt-3 pt-2 border-top">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="assign_all_products" id="assignAllProducts" value="1" checked>
                                    <label class="form-check-label fw-bold text-dark" for="assignAllProducts">
                                        সেন্ট্রাল ক্যাটালগের বিদ্যমান সকল পণ্য এই ব্রাঞ্চে স্বয়ংক্রিয়ভাবে ম্যাপ করুন (Default Stock: 50 units)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                            <button type="submit" class="btn btn-success fw-bold"><i class="ti-check me-1"></i> ব্রাঞ্চ তৈরি করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

