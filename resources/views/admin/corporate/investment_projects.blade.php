@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-folder-open text-primary me-2"></i> খাতভিত্তিক বিনিয়োগ প্রকল্পসমূহ</h4>
                    <p class="text-muted small mb-0">১০টি বিনিয়োগ খাতের অধীনে চলমান ও উন্মুক্ত প্রকল্পসমূহের তালিকা ও নতুন প্রকল্প তৈরি।</p>
                </div>
                <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                    <i class="fa fa-plus me-1"></i> নতুন বিনিয়োগ প্রকল্প তৈরি
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>আইডি ও নাম</th>
                                <th>বিনিয়োগ খাত</th>
                                <th>বাজেট ও সংগৃহীত</th>
                                <th>বিনিয়োগ সীমা (টাকা)</th>
                                <th>বিনিয়োগকারী</th>
                                <th>অগ্রগতি</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $p)
                            <tr>
                                <td>
                                    <span class="badge bg-dark font-monospace mb-1">{{ $p->project_id_code }}</span>
                                    <div class="fw-bold text-dark font-size-13">{{ $p->project_name }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success">{{ $p->sector->name_bn ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ number_format($p->target_budget) }} ৳</div>
                                    <small class="text-primary">সংগৃহীত: {{ number_format($p->total_raised) }} ৳</small>
                                </td>
                                <td>{{ number_format($p->min_investment) }} - {{ number_format($p->max_investment) }} ৳</td>
                                <td><span class="badge bg-light text-dark">{{ $p->investors_count }} জন</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; min-width: 50px;">
                                            <div class="progress-bar bg-success" style="width: {{ $p->progress_percentage }}%;"></div>
                                        </div>
                                        <span class="fw-bold">{{ $p->progress_percentage }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $p->status === 'running' || $p->status === 'open' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $p->status === 'running' ? 'চলমান' : ($p->status === 'open' ? 'উন্মুক্ত' : $p->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('investment.project.show', $p->id) }}" target="_blank" class="btn btn-outline-info btn-xs rounded-pill px-2">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.corporate.projects.destroy', $p->id) }}" onclick="return confirm('মুছে ফেলতে চান?')" class="btn btn-outline-danger btn-xs rounded-pill px-2">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">কোনো প্রকল্প পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $projects->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">নতুন বিনিয়োগ প্রকল্প তৈরি</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.corporate.projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-muted">প্রকল্পের নাম *</label>
                            <input type="text" name="project_name" class="form-control" placeholder="যেমন: মানিকগঞ্জ সুগন্ধি বাসমতী ধান প্রকল্প" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">বিনিয়োগ খাত নির্বাচন করুন *</label>
                            <select name="sector_id" class="form-select" required>
                                @foreach($sectors as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->sector_no }}. {{ $sec->name_bn }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">প্রকল্পের মোট বাজেট (টাকা) *</label>
                            <input type="number" name="target_budget" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">সর্বনিম্ন বিনিয়োগ (টাকা) *</label>
                            <input type="number" name="min_investment" class="form-control" value="50000" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">সর্বোচ্চ বিনিয়োগ (টাকা) *</label>
                            <input type="number" name="max_investment" class="form-control" value="10000000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">প্রকল্পের মেয়াদ</label>
                            <input type="text" name="duration" class="form-control" placeholder="যেমন: ৬ মাস / ১ বছর" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">প্রাথমিক বাস্তবায়ন হার (০-১০০%)</label>
                            <input type="number" name="progress_percentage" class="form-control" min="0" max="100" value="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">প্রকল্পের বিস্তারিত বিবরণ</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">লাভ-লোকসান বণ্টন নীতি</label>
                            <textarea name="profit_policy" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">প্রকল্পের ছবি আপলোড</label>
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">প্রকল্পের স্ট্যাটাস</label>
                            <select name="status" class="form-select">
                                <option value="open">উন্মুক্ত (Open for Investment)</option>
                                <option value="running">চলমান (Running)</option>
                                <option value="closed">বন্ধ (Closed)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

