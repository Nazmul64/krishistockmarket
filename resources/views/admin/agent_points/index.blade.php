@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-map-marker me-2 text-danger"></i> সংগ্রহ এজেন্ট পয়েন্টসমূহ</h4>
                    <p class="subtitle text-muted mb-0">মাসিক বাজার অর্ডার এবং পিকআপের জন্য এজেন্ট পয়েন্টসমূহ পরিচালনা করুন</p>
                </div>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAgentPointModal">
                    <i class="fa fa-plus me-1"></i> নতুন এজেন্ট পয়েন্ট যুক্ত করুন
                </button>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title">সকল সংগ্রহ এজেন্ট পয়েন্ট তালিকা</h4>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>এজেন্ট পয়েন্টের নাম</th>
                                            <th>এলাকা / জেলা</th>
                                            <th>ঠিকানা</th>
                                            <th>যোগাযোগ নাম্বার</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-center">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($agent_points as $key => $point)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td><strong class="text-dark">{{ $point->name }}</strong></td>
                                                <td><span class="badge bg-info">{{ $point->area }}</span></td>
                                                <td>{{ $point->address ?? 'N/A' }}</td>
                                                <td>{{ $point->contact_number ?? 'N/A' }}</td>
                                                <td>
                                                    @if($point->status === 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-warning me-1" 
                                                            onclick="editAgentPoint({{ json_encode($point) }})">
                                                        <i class="fa fa-edit"></i> এডিট
                                                    </button>
                                                    <a href="{{ route('admin.agent_points.destroy', $point->id) }}" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('আপনি কি নিশ্চিত যে এই এজেন্ট পয়েন্টটি মুছে ফেলতে চান?')">
                                                        <i class="fa fa-trash"></i> ডিলিট
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    কোনো এজেন্ট পয়েন্ট পাওয়া যায়নি। নতুন যুক্ত করুন।
                                                </td>
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
    </div>
</div>

<!-- Modal: Add / Edit Agent Point -->
<div class="modal fade" id="addAgentPointModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.agent_points.store') }}" method="POST" id="agentPointForm">
                @csrf
                <input type="hidden" name="id" id="agent_point_id">
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle"><i class="fa fa-map-marker me-2"></i> এজেন্ট পয়েন্ট যুক্ত করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">এজেন্ট পয়েন্টের নাম <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="point_name" required placeholder="যেমন: নওগাঁ সেন্ট্রাল এজেন্ট পয়েন্ট">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">এলাকা / জেলা <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="area" id="point_area" required placeholder="যেমন: নওগাঁ, বগুড়া, গাজীপুর, ঢাকা">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ঠিকানা</label>
                        <textarea class="form-control" name="address" id="point_address" rows="2" placeholder="এজেন্ট পয়েন্টের পূর্ণাঙ্গ ঠিকানা..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">যোগাযোগের নাম্বার</label>
                        <input type="text" class="form-control" name="contact_number" id="point_contact" placeholder="যেমন: 01700000000">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">স্ট্যাটাস <span class="text-danger">*</span></label>
                        <select class="form-control" name="status" id="point_status" required>
                            <option value="active">Active (সক্রিয়)</option>
                            <option value="inactive">Inactive (নিষ্ক্রিয়)</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save me-1"></i> সেভ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editAgentPoint(point) {
    document.getElementById('modalTitle').innerHTML = '<i class="fa fa-edit me-2"></i> এজেন্ট পয়েন্ট এডিট করুন';
    document.getElementById('agent_point_id').value = point.id;
    document.getElementById('point_name').value = point.name;
    document.getElementById('point_area').value = point.area;
    document.getElementById('point_address').value = point.address || '';
    document.getElementById('point_contact').value = point.contact_number || '';
    document.getElementById('point_status').value = point.status;
    
    var myModal = new bootstrap.Modal(document.getElementById('addAgentPointModal'));
    myModal.show();
}

document.getElementById('addAgentPointModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('modalTitle').innerHTML = '<i class="fa fa-map-marker me-2"></i> এজেন্ট পয়েন্ট যুক্ত করুন';
    document.getElementById('agentPointForm').reset();
    document.getElementById('agent_point_id').value = '';
});
</script>
@endsection
