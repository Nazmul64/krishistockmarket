@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-briefcase text-success me-2"></i>ক্যারিয়ার আবেদনসমূহ (Career Applications)</h4>
                    <p class="text-muted mb-0">ওয়েবসাইট ও মোবাইল অ্যাপ থেকে জমা হওয়া সকল চাকরি ও ক্যারিয়ার আবেদন</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-warning fs-6 px-3 py-2">
                        <i class="ti-time me-1"></i> {{ $counts['pending'] }} টি অপেক্ষমান
                    </span>
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="ti-check-box me-1"></i> মোট {{ $counts['total'] }} টি আবেদন
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti-check-box me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- Filters & Search Header -->
                        <div class="card-header border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <a href="{{ route('admin.career.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    সকল ({{ $counts['total'] }})
                                </a>
                                <a href="{{ route('admin.career.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                                    অপেক্ষমান ({{ $counts['pending'] }})
                                </a>
                                <a href="{{ route('admin.career.index', ['status' => 'reviewed']) }}" class="btn btn-sm {{ request('status') == 'reviewed' ? 'btn-info' : 'btn-outline-info' }}">
                                    পর্যালোচিত ({{ $counts['reviewed'] }})
                                </a>
                                <a href="{{ route('admin.career.index', ['status' => 'shortlisted']) }}" class="btn btn-sm {{ request('status') == 'shortlisted' ? 'btn-success' : 'btn-outline-success' }}">
                                    বাছাইকৃত ({{ $counts['shortlisted'] }})
                                </a>
                                <a href="{{ route('admin.career.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                                    বাতিল ({{ $counts['rejected'] }})
                                </a>
                            </div>

                            <form action="{{ route('admin.career.index') }}" method="GET" class="d-flex align-items-center" style="max-width: 320px;">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                <div class="input-group">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="নাম, ফোন বা পদবী দিয়ে খুঁজুন...">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="ti-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <!-- Table Body -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>প্রার্থী নাম</th>
                                            <th>যোগাযোগ</th>
                                            <th>ঠিকানা</th>
                                            <th>পদবী / বিষয়</th>
                                            <th>সিভি (PDF)</th>
                                            <th>কভার লেটার</th>
                                            <th>স্ট্যাটাস</th>
                                            <th>তারিখ</th>
                                            <th style="width: 140px;" class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($applications as $key => $item)
                                            <tr>
                                                <td>{{ $applications->firstItem() + $key }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $item->full_name }}</div>
                                                    @if($item->email)
                                                        <small class="text-muted"><i class="fa fa-envelope-o me-1"></i>{{ $item->email }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="tel:{{ $item->phone }}" class="badge bg-light text-dark border">
                                                        <i class="fa fa-phone text-success me-1"></i>{{ $item->phone }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="d-inline-block text-truncate" style="max-width: 160px;" title="{{ $item->address }}">
                                                        {{ $item->address }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $item->position_applied }}</span>
                                                </td>
                                                <td>
                                                    @if($item->resume)
                                                        <a href="{{ asset($item->resume) }}" target="_blank" class="btn btn-xs btn-outline-danger">
                                                            <i class="fa fa-file-pdf-o me-1"></i> PDF ভিউ
                                                        </a>
                                                    @else
                                                        <span class="text-muted fs-7">নাই</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->cover_letter)
                                                        <button type="button" class="btn btn-xs btn-outline-info view-cover-btn" 
                                                                data-name="{{ $item->full_name }}" 
                                                                data-letter="{{ $item->cover_letter }}">
                                                            <i class="fa fa-eye me-1"></i> কভার লেটার
                                                        </button>
                                                    @else
                                                        <span class="text-muted fs-7">দেওয়া হয়নি</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->status == 'pending')
                                                        <span class="badge bg-warning text-dark">অপেক্ষমান</span>
                                                    @elseif($item->status == 'reviewed')
                                                        <span class="badge bg-info">পর্যালোচিত</span>
                                                    @elseif($item->status == 'shortlisted')
                                                        <span class="badge bg-success">বাছাইকৃত</span>
                                                    @elseif($item->status == 'rejected')
                                                        <span class="badge bg-danger">বাতিল</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $item->created_at->format('d M, Y') }}</small>
                                                </td>
                                                <td class="text-end">
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            স্ট্যাটাস
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <form action="{{ route('admin.career.status', $item->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="pending">
                                                                    <button class="dropdown-item" type="submit">অপেক্ষমান (Pending)</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.career.status', $item->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="reviewed">
                                                                    <button class="dropdown-item" type="submit">পর্যালোচিত (Reviewed)</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.career.status', $item->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="shortlisted">
                                                                    <button class="dropdown-item text-success" type="submit">বাছাই করুন (Shortlist)</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.career.status', $item->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="rejected">
                                                                    <button class="dropdown-item text-danger" type="submit">বাতিল করুন (Reject)</button>
                                                                </form>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('admin.career.destroy', $item->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই আবেদনটি ডিলিট করতে চান?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="dropdown-item text-danger" type="submit"><i class="fa fa-trash me-1"></i> ডিলিট করুন</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="fa fa-folder-open-o fa-3x mb-3 text-secondary"></i>
                                                        <p class="mb-0 fs-6">কোনো ক্যারিয়ার আবেদন পাওয়া যায়নি</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination Footer -->
                        @if($applications->hasPages())
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <div>
                                    দেখাচ্ছে {{ $applications->firstItem() }} থেকে {{ $applications->lastItem() }} মোট {{ $applications->total() }} টি
                                </div>
                                <div>
                                    {{ $applications->appends(request()->query())->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Cover Letter Modal -->
<div class="modal fade" id="coverLetterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-file-text-o text-primary me-2"></i><span id="modalApplicantName">কভার লেটার</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 bg-light rounded border" id="modalCoverLetterContent" style="white-space: pre-wrap; font-size: 14px; line-height: 1.6;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var coverBtns = document.querySelectorAll('.view-cover-btn');
    var modal = new bootstrap.Modal(document.getElementById('coverLetterModal'));
    var nameEl = document.getElementById('modalApplicantName');
    var contentEl = document.getElementById('modalCoverLetterContent');

    coverBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var name = this.getAttribute('data-name');
            var letter = this.getAttribute('data-letter');
            nameEl.textContent = name + ' এর কভার লেটার';
            contentEl.textContent = letter;
            modal.show();
        });
    });
});
</script>
@endsection

