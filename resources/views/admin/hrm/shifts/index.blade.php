@extends('layouts.backend.app')

@section('title', 'শিফট ম্যানেজমেন্ট')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-time mr-2"></i> শিফট শিডিউল (Shift Management)
                    </h3>
                    <p class="text-muted mb-0">অফিস কাজের সময়সূচি, বিরতি ও গ্রেস টাইম নির্ধারণ</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addShiftModal" data-toggle="modal" data-target="#addShiftModal">
                    <i class="ti-plus mr-1"></i> নতুন শিফট যোগ করুন
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
                            <th>শিফট নাম</th>
                            <th>শুরুর সময়</th>
                            <th>শেষের সময়</th>
                            <th>বিরতি (মিনিট)</th>
                            <th>গ্রেস টাইম (মিনিট)</th>
                            <th>ওভারটাইম</th>
                            <th>কর্মী সংখ্যা</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $index => $shift)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-weight-bold text-dark">{{ $shift->name }}</td>
                            <td><span class="badge badge-success">{{ date('h:i A', strtotime($shift->start_time)) }}</span></td>
                            <td><span class="badge badge-primary">{{ date('h:i A', strtotime($shift->end_time)) }}</span></td>
                            <td>{{ $shift->break_time_minutes }} মিনিট</td>
                            <td>{{ $shift->grace_time_minutes }} মিনিট</td>
                            <td>
                                @if($shift->overtime_enabled)
                                    <span class="badge badge-success">অনুমোদিত</span>
                                @else
                                    <span class="badge badge-secondary">বন্ধ</span>
                                @endif
                            </td>
                            <td><span class="badge badge-info">{{ $shift->employee_profiles_count }} জন</span></td>
                            <td class="text-right">
                                <button type="button" class="btn btn-primary btn-sm rounded-circle px-2" data-bs-toggle="modal" data-bs-target="#editShiftModal{{ $shift->id }}" data-toggle="modal" data-target="#editShiftModal{{ $shift->id }}">
                                    <i class="ti-pencil"></i>
                                </button>
                                <form action="{{ route('admin.hrm.shifts.destroy', $shift->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই শিফটটি মুছে ফেলতে চান?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle px-2">
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editShiftModal{{ $shift->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg rounded-lg">
                                    <form action="{{ route('admin.hrm.shifts.update', $shift->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-header-title text-white mb-0"><i class="ti-pencil mr-2"></i> শিফট সম্পাদনা</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="font-weight-bold">শিফট নাম <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ $shift->name }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label class="font-weight-bold">শুরুর সময় <span class="text-danger">*</span></label>
                                                    <input type="time" name="start_time" class="form-control" value="{{ $shift->start_time }}" required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label class="font-weight-bold">শেষের সময় <span class="text-danger">*</span></label>
                                                    <input type="time" name="end_time" class="form-control" value="{{ $shift->end_time }}" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label class="font-weight-bold">বিরতির সময় (মিনিট)</label>
                                                    <input type="number" name="break_time_minutes" class="form-control" value="{{ $shift->break_time_minutes }}">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label class="font-weight-bold">গ্রেস টাইম (মিনিট)</label>
                                                    <input type="number" name="grace_time_minutes" class="form-control" value="{{ $shift->grace_time_minutes }}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check form-check-flat form-check-primary">
                                                    <label class="form-check-label font-weight-bold">
                                                        <input type="checkbox" name="overtime_enabled" class="form-check-input" {{ $shift->overtime_enabled ? 'checked' : '' }}>
                                                        ওভারটাইম গণনা সক্রিয় রাখুন
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">স্ট্যাটাস</label>
                                                <select name="status" class="form-control">
                                                    <option value="active" {{ $shift->status == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                                                    <option value="inactive" {{ $shift->status == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
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
                            <td colspan="9" class="text-center py-4 text-muted">কোনো শিফট তথ্য পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addShiftModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.shifts.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> নতুন শিফট যোগ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">শিফট নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: ডে শিফট, নাইট শিফট, জেনারেল শিফট" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">শুরুর সময় <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control" value="09:00" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">শেষের সময় <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control" value="17:00" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">বিরতির সময় (মিনিট)</label>
                            <input type="number" name="break_time_minutes" class="form-control" value="60">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">গ্রেস টাইম (মিনিট)</label>
                            <input type="number" name="grace_time_minutes" class="form-control" value="15">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check form-check-flat form-check-primary">
                            <label class="form-check-label font-weight-bold">
                                <input type="checkbox" name="overtime_enabled" class="form-check-input" checked>
                                ওভারটাইম গণনা সক্রিয় রাখুন
                            </label>
                        </div>
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
