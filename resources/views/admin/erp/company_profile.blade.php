@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-building text-primary me-2"></i> কোম্পানি প্রোফাইল ও লিগ্যাল ডকুমেন্ট ভল্ট</h4>
                    <p class="text-muted small mb-0">প্রতিষ্ঠানের পরিচিতি, ট্রেড লাইসেন্স, টিন, বিন, অফিসিয়াল সিল এবং গুরুত্বপূর্ণ ডকুমেন্টস পরিচালনা।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#uploadDocModal">
                        <i class="fa fa-upload me-1"></i> নতুন ডকুমেন্ট আপলোড
                    </button>
                    <a href="{{ route('admin.erp.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-3">
                <!-- Left Column: Company Profile Form -->
                <div class="col-xl-7 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                            <i class="fa fa-id-card text-primary me-2"></i> প্রাতিষ্ঠানিক পরিচিতি ও তথ্যাবলী
                        </h5>
                        <form action="{{ route('admin.erp.company_profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">প্রতিষ্ঠানের নাম (ইংরেজি) *</label>
                                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $company->company_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">প্রতিষ্ঠানের নাম (বাংলা)</label>
                                    <input type="text" name="company_name_bn" class="form-control" value="{{ old('company_name_bn', $company->company_name_bn) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">যোগাযোগ নম্বর *</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $company->phone) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">ইমেইল ঠিকানা</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $company->email) }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">ওয়েবসাইট</label>
                                    <input type="text" name="website" class="form-control" value="{{ old('website', $company->website) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">ট্রেড লাইসেন্স নম্বর</label>
                                    <input type="text" name="trade_license" class="form-control" value="{{ old('trade_license', $company->trade_license) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">TIN নম্বর</label>
                                    <input type="text" name="tin_no" class="form-control" value="{{ old('tin_no', $company->tin_no) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">BIN নম্বর</label>
                                    <input type="text" name="bin_no" class="form-control" value="{{ old('bin_no', $company->bin_no) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">রেজিস্ট্রেশন নম্বর</label>
                                    <input type="text" name="registration_no" class="form-control" value="{{ old('registration_no', $company->registration_no) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">বর্তমান অর্থবছর</label>
                                    <input type="text" name="fiscal_year" class="form-control" value="{{ old('fiscal_year', $company->fiscal_year ?? '2026-2027') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">কারেন্সি প্রতীক</label>
                                    <input type="text" name="currency" class="form-control" value="{{ old('currency', $company->currency ?? 'BDT (৳)') }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">কর্পোরেট প্রধান কার্যালয় ঠিকানা</label>
                                    <textarea name="corporate_address" class="form-control" rows="2">{{ old('corporate_address', $company->corporate_address) }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">রেজিস্টার্ড অফিসের ঠিকানা</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address', $company->address) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">অনুমোদিত স্বাক্ষরকারী / পদবী</label>
                                    <input type="text" name="authorized_person" class="form-control" value="{{ old('authorized_person', $company->authorized_person) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">কোম্পানি লোগো (PNG/JPG)</label>
                                    <input type="file" name="logo" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">কোম্পানির সংক্ষিপ্ত পরিচিতি</label>
                                    <textarea name="description" class="form-control" rows="2">{{ old('description', $company->description) }}</textarea>
                                </div>
                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                                        <i class="fa fa-save me-1"></i> প্রোফাইল সংরক্ষণ করুন
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Documents Library -->
                <div class="col-xl-5 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="fa fa-files-o text-success me-2"></i> ডকুমেন্ট ভল্ট ও লাইসেন্স
                            </h5>
                            <span class="badge bg-primary">{{ count($documents) }} টি ফাইল</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ডকুমেন্ট নাম</th>
                                        <th>ক্যাটাগরি</th>
                                        <th>মেয়াদ শেষ</th>
                                        <th>অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($documents as $doc)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $doc->title }}</span>
                                            <small class="text-muted">নং: {{ $doc->document_number ?? 'N/A' }}</small>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $doc->category }}</span></td>
                                        <td>
                                            @if($doc->expiry_date)
                                                @if($doc->isExpired())
                                                    <span class="badge bg-danger">{{ $doc->expiry_date }} (Expired)</span>
                                                @elseif($doc->isExpiringSoon())
                                                    <span class="badge bg-warning text-dark">{{ $doc->expiry_date }} (Expiring)</span>
                                                @else
                                                    <span class="badge bg-success">{{ $doc->expiry_date }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">আজীবন</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ asset($doc->file_path) }}" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill px-2">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">কোনো আপলোডকৃত ডকুমেন্ট পাওয়া যায়নি।</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-upload text-primary me-2"></i> নতুন ডকুমেন্ট সংরক্ষণ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">ডকুমেন্টের শিরোনাম *</label>
                        <input type="text" name="title" class="form-control" placeholder="उदा: ট্রেড লাইসেন্স ২০২৬-২৭" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">ডকুমেন্ট নম্বর</label>
                        <input type="text" name="document_number" class="form-control" placeholder="TRAD/DNCC/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">ক্যাটাগরি *</label>
                        <select name="category" class="form-select" required>
                            <option value="license">লাইসেন্স ও সরকারি অনুমোদন (License)</option>
                            <option value="agreement">চুক্তিপত্র (Agreement)</option>
                            <option value="tax">ট্যাক্স ও ভ্যাট পেপার (Tax & VAT)</option>
                            <option value="legal">আইনি ও আদালতের নথি (Legal)</option>
                            <option value="general">সাধারণ ডকুমেন্ট (General)</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small">ইস্যুর তারিখ</label>
                            <input type="date" name="issue_date" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">মেয়াদ উত্তীর্ণের তারিখ</label>
                            <input type="date" name="expiry_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">ফাইল সংযুক্ত করুন (PDF/Image) *</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">অতিরিক্ত নোট</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">আপলোড করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

