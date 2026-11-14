@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-user-plus text-primary me-2"></i> কর্মচারী সম্পদ বরাদ্দ ও ফেরত ব্যবস্থাপনা (Asset Assignments)</h4>
                    <p class="text-muted small mb-0">কোন কর্মচারীকে কোন ল্যাপটপ, কম্পিউটার, চেয়ার বা সম্পদ দেওয়া হয়েছে তার ডিজিটাল ট্র্যাকিং ও ফেরত নেওয়ার রেকর্ড।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#assignAssetModal">
                        <i class="fa fa-handshake-o me-1"></i> নতুন সম্পদ বরাদ্দ (Assign)
                    </button>
                    <a href="{{ route('admin.erp.assets') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-desktop me-1"></i> সম্পদ রেজিস্টার
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>সম্পদ কোড ও নাম</th>
                                <th>বরাদ্দপ্রাপ্ত কর্মচারী</th>
                                <th>বিভাগ</th>
                                <th>হস্তান্তরের তারিখ</th>
                                <th>হস্তান্তরকালীন অবস্থা</th>
                                <th>হস্তান্তরকারী</th>
                                <th>ফেরতের তারিখ</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assign)
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary">{{ optional($assign->asset)->asset_code }}</span>
                                    <span class="text-dark d-block fw-bold">{{ optional($assign->asset)->asset_name }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <span class="fw-bold text-dark">{{ $assign->employee_name }}</span>
                                    </div>
                                </td>
                                <td>{{ $assign->department_name ?? 'General' }}</td>
                                <td>{{ $assign->assignment_date }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $assign->condition_at_handover }}</span></td>
                                <td>{{ $assign->handover_by ?? 'Admin' }}</td>
                                <td>
                                    @if($assign->return_date)
                                        <span class="text-success fw-bold">{{ $assign->return_date }}</span>
                                    @else
                                        <span class="text-muted">চলমান</span>
                                    @endif
                                </td>
                                <td>
                                    @if($assign->status === 'active')
                                        <span class="badge bg-success bg-opacity-20 text-success"><i class="fa fa-check-circle me-1"></i> সচল (Active)</span>
                                    @else
                                        <span class="badge bg-secondary">ফেরত গৃহীত (Returned)</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($assign->status === 'active')
                                        <button class="btn btn-xs btn-outline-warning rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#returnModal{{ $assign->id }}">
                                            <i class="fa fa-undo me-1"></i> ফেরত গ্রহণ (Return)
                                        </button>

                                        <!-- Return Modal -->
                                        <div class="modal fade text-start" id="returnModal{{ $assign->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 border-0 shadow">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold text-dark"><i class="fa fa-undo text-warning me-2"></i> সম্পদ ফেরত গ্রহণ: {{ optional($assign->asset)->asset_code }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.erp.assets.assignments.return', $assign->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold small">ফেরতের তারিখ *</label>
                                                                <input type="date" name="return_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold small">ফেরতকালীন অবস্থা *</label>
                                                                <select name="condition_at_return" class="form-select" required>
                                                                    <option value="good">ভালো (Good Condition)</option>
                                                                    <option value="needs_repair">মেরামত প্রয়োজন (Needs Repair)</option>
                                                                    <option value="damaged">নষ্ট / ক্ষতিগ্রস্ত (Damaged)</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold small">মন্তব্য / হ্যান্ডওভার নোট</label>
                                                                <textarea name="remarks" class="form-control" rows="2" placeholder="সম্পদের সার্বিক অবস্থা..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                            <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold text-dark">ফেরত নিশ্চিত করুন</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted"><i class="fa fa-lock"></i> কমপ্লিট</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">কোনো সম্পদ বরাদ্দ হিস্ট্রি পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $assignments->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Assign Asset Modal -->
<div class="modal fade" id="assignAssetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-handshake-o text-primary me-2"></i> কর্মচারীকে সম্পদ বরাদ্দ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.assets.assignments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">সম্পদ নির্বাচন করুন *</label>
                        <select name="asset_id" class="form-select" required>
                            <option value="">-- সম্পদ সিলেক্ট করুন --</option>
                            @foreach($assets as $ast)
                                <option value="{{ $ast->id }}">{{ $ast->asset_code }} - {{ $ast->asset_name }} ({{ $ast->status }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">কর্মচারীর নাম *</label>
                        <input type="text" name="employee_name" class="form-control" placeholder="उदा: ইঞ্জিঃ তানভীর আহমেদ" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">বিভাগ (Department)</label>
                        <input type="text" name="department_name" class="form-control" placeholder="उदा: আইটি ডিপার্টমেন্ট / প্রশাসন">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">হস্তান্তরের তারিখ *</label>
                        <input type="date" name="assignment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">হস্তান্তরকালীন অবস্থা</label>
                        <select name="condition_at_handover" class="form-select">
                            <option value="good">ভালো (Good Condition)</option>
                            <option value="excellent">নতুন / চমৎকার (Brand New / Excellent)</option>
                            <option value="fair">মোটামুটি (Fair)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">অতিরিক্ত নোট / চুক্তি</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="চাকরি পরিবর্তন বা ইস্তফার সময় সম্পদ ফেরতযোগ্য..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">বরাদ্দ নিশ্চিত করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

