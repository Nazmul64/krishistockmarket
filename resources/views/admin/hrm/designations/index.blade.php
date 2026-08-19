@extends('layouts.backend.app')

@section('title', 'পদবীসমূহ (Designations)')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-medall mr-2"></i> পদবীসমূহ (Designation Management)
                    </h3>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের বিভাগভিত্তিক সকল পদবীর তালিকা</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addDesignationModal" data-toggle="modal" data-target="#addDesignationModal">
                    <i class="ti-plus mr-1"></i> নতুন পদবী যোগ করুন
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
                            <th>পদবীর নাম</th>
                            <th>ডিপার্টমেন্ট</th>
                            <th>বিবরণ</th>
                            <th>স্ট্যাটাস</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($designations as $index => $desig)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-weight-bold text-dark">{{ $desig->name }}</td>
                            <td><span class="badge badge-info">{{ $desig->department->name ?? 'N/A' }}</span></td>
                            <td>{{ $desig->description ?? '--' }}</td>
                            <td>
                                @if($desig->status == 'active')
                                    <span class="badge badge-success">সক্রিয়</span>
                                @else
                                    <span class="badge badge-danger">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button type="button" class="btn btn-primary btn-sm rounded-circle px-2" data-bs-toggle="modal" data-bs-target="#editDesignationModal{{ $desig->id }}" data-toggle="modal" data-target="#editDesignationModal{{ $desig->id }}">
                                    <i class="ti-pencil"></i>
                                </button>
                                <form action="{{ route('admin.hrm.designations.destroy', $desig->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই পদবীটি মুছে ফেলতে চান?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle px-2">
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editDesignationModal{{ $desig->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg rounded-lg">
                                    <form action="{{ route('admin.hrm.designations.update', $desig->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-header-title text-white mb-0"><i class="ti-pencil mr-2"></i> পদবী সম্পাদনা</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="font-weight-bold">পদবীর নাম <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ $desig->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">ডিপার্টমেন্ট <span class="text-danger">*</span></label>
                                                <select name="department_id" class="form-control" required>
                                                    @foreach($departments as $dept)
                                                    <option value="{{ $dept->id }}" {{ $desig->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">বিবরণ</label>
                                                <textarea name="description" class="form-control" rows="3">{{ $desig->description }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">স্ট্যাটাস</label>
                                                <select name="status" class="form-control">
                                                    <option value="active" {{ $desig->status == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                                                    <option value="inactive" {{ $desig->status == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
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
                            <td colspan="6" class="text-center py-4 text-muted">কোনো পদবী তথ্য পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addDesignationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.designations.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> নতুন পদবী যোগ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">পদবীর নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: সিনিয়র সফটওয়্যার ইঞ্জিনিয়ার, ম্যানেজার" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">ডিপার্টমেন্ট <span class="text-danger">*</span></label>
                        <select name="department_id" class="form-control" required>
                            <option value="">-- নির্বাচন করুন --</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">বিবরণ</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="পদবীর দায়-দায়িত্বের বিবরণ..."></textarea>
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
