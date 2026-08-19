@extends('layouts.backend.app')

@section('title', 'ছুটির দিন (Holidays)')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-calendar mr-2"></i> ছুটির দিন ও ইভেন্ট ক্যালেন্ডার (Holiday Setup)
                    </h3>
                    <p class="text-muted mb-0">সরকারি ছুটি, ধর্মীয় উৎসব ও কোম্পানির নির্ধারিত ছুটির তালিকা</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addHolidayModal" data-toggle="modal" data-target="#addHolidayModal">
                    <i class="ti-plus mr-1"></i> নতুন ছুটির দিন যোগ করুন
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="ti-check-box mr-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" data-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>ছুটির নাম</th>
                            <th>শুরুর তারিখ</th>
                            <th>শেষের তারিখ</th>
                            <th>ছুটির ধরন</th>
                            <th>বিবরণ</th>
                            <th>স্ট্যাটাস</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($holidays as $index => $holiday)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-weight-bold text-dark">{{ $holiday->name }}</td>
                            <td><span class="badge badge-outline-success font-weight-bold">{{ $holiday->start_date }}</span></td>
                            <td><span class="badge badge-outline-primary font-weight-bold">{{ $holiday->end_date }}</span></td>
                            <td>
                                @if($holiday->type == 'public')
                                    <span class="badge badge-info">সরকারি ছুটি</span>
                                @elseif($holiday->type == 'religious')
                                    <span class="badge badge-warning">ধর্মীয় উৎসব</span>
                                @else
                                    <span class="badge badge-dark">কোম্পানি ছুটি</span>
                                @endif
                            </td>
                            <td>{{ $holiday->description ?? '--' }}</td>
                            <td>
                                @if($holiday->status == 'active')
                                    <span class="badge badge-success">সক্রিয়</span>
                                @else
                                    <span class="badge badge-danger">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button type="button" class="btn btn-primary btn-sm rounded-circle px-2" data-bs-toggle="modal" data-bs-target="#editHolidayModal{{ $holiday->id }}" data-toggle="modal" data-target="#editHolidayModal{{ $holiday->id }}">
                                    <i class="ti-pencil"></i>
                                </button>
                                <form action="{{ route('admin.hrm.holidays.destroy', $holiday->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ছুটির দিনটি মুছে ফেলতে চান?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle px-2">
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editHolidayModal{{ $holiday->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg rounded-lg">
                                    <form action="{{ route('admin.hrm.holidays.update', $holiday->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-header-title text-white mb-0"><i class="ti-pencil mr-2"></i> ছুটির দিন সম্পাদনা</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="font-weight-bold">ছুটির নাম <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ $holiday->name }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label class="font-weight-bold">শুরুর তারিখ <span class="text-danger">*</span></label>
                                                    <input type="date" name="start_date" class="form-control" value="{{ $holiday->start_date }}" required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label class="font-weight-bold">শেষের তারিখ <span class="text-danger">*</span></label>
                                                    <input type="date" name="end_date" class="form-control" value="{{ $holiday->end_date }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">ছুটির ধরন <span class="text-danger">*</span></label>
                                                <select name="type" class="form-control" required>
                                                    <option value="public" {{ $holiday->type == 'public' ? 'selected' : '' }}>সরকারি ছুটি (Public Holiday)</option>
                                                    <option value="religious" {{ $holiday->type == 'religious' ? 'selected' : '' }}>ধর্মীয় উৎসব (Religious)</option>
                                                    <option value="company" {{ $holiday->type == 'company' ? 'selected' : '' }}>কোম্পানি নির্ধারিত (Company)</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">বিবরণ</label>
                                                <textarea name="description" class="form-control" rows="2">{{ $holiday->description }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">স্ট্যাটাস</label>
                                                <select name="status" class="form-control">
                                                    <option value="active" {{ $holiday->status == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                                                    <option value="inactive" {{ $holiday->status == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                                            <button type="submit" class="btn btn-primary rounded-pill">আপডেট করুন</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">কোনো ছুটির দিন তথ্য পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addHolidayModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.holidays.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> নতুন ছুটির দিন যোগ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">ছুটির নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: স্বাধীনতা দিবস, ঈদুল ফিতর" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">শুরুর তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">শেষের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">ছুটির ধরন <span class="text-danger">*</span></label>
                        <select name="type" class="form-control" required>
                            <option value="public">সরকারি ছুটি (Public Holiday)</option>
                            <option value="religious">ধর্মীয় উৎসব (Religious)</option>
                            <option value="company">কোম্পানি নির্ধারিত (Company)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">বিবরণ</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="ছুটির সংক্রান্ত অতিরিক্ত তথ্য..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
