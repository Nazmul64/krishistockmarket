@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-tasks text-success me-2"></i> প্রাতিষ্ঠানিক কার্যক্রম ও প্রকল্প বাস্তবায়ন</h4>
                    <p class="text-muted small mb-0">চলমান, সম্পন্ন ও পরিকল্পনাধীন প্রকল্পের অগ্রগতি, মাঠপর্যায়ের রিপোর্ট ও বাজেট ট্র্যাকিং।</p>
                </div>
                <button class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addActModal">
                    <i class="fa fa-plus me-1"></i> নতুন প্রকল্প যোগ করুন
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
                                <th>ছবি</th>
                                <th>প্রকল্প কোড ও নাম</th>
                                <th>ক্যাটাগরি</th>
                                <th>অবস্থান</th>
                                <th>বাজেট</th>
                                <th>বাস্তবায়ন অগ্রগতি</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $act)
                            <tr>
                                <td>
                                    <img src="{{ !empty($act->featured_image) && file_exists(public_path($act->featured_image)) ? asset($act->featured_image) : asset('frontend/assets/images/about/about-img.jpg') }}" 
                                         alt="{{ $act->title }}" 
                                         class="rounded-3 border" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    <span class="badge bg-dark font-monospace mb-1">{{ $act->project_code }}</span>
                                    <div class="fw-bold text-dark">{{ $act->title }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ match($act->category) {
                                        'completed' => 'bg-success',
                                        'ongoing' => 'bg-warning text-dark',
                                        'planned' => 'bg-info text-dark',
                                        default => 'bg-secondary',
                                    } }} text-uppercase">
                                        {{ match($act->category) {
                                            'completed' => 'সম্পন্ন',
                                            'ongoing' => 'চলমান',
                                            'planned' => 'পরিকল্পনাধীন',
                                            default => $act->category,
                                        } }}
                                    </span>
                                </td>
                                <td>{{ $act->location ?: 'ঢাকা ও সংশ্লিষ্ট' }}</td>
                                <td class="fw-bold text-dark">{{ number_format($act->budget) }} ৳</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; min-width: 60px;">
                                            <div class="progress-bar bg-success" style="width: {{ $act->progress_percentage }}%;"></div>
                                        </div>
                                        <span class="fw-bold">{{ $act->progress_percentage }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $act->status === 'active' || $act->status === 'completed' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $act->status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.corporate.activities.destroy', $act->id) }}" onclick="return confirm('মুছে ফেলতে চান?')" class="btn btn-outline-danger btn-xs rounded-pill px-2">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">কোনো প্রকল্পের তথ্য পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $activities->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Add Activity Modal -->
<div class="modal fade" id="addActModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">নতুন প্রকল্প / কার্যক্রম যোগ করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.corporate.activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-muted">প্রকল্পের নাম *</label>
                            <input type="text" name="title" class="form-control" placeholder="যেমন: শ্রীপুর আধুনিক ডেইরি ফার্ম ফেস-১" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">ক্যাটাগরি *</label>
                            <select name="category" class="form-select" required>
                                <option value="ongoing">চলমান প্রকল্প (Ongoing)</option>
                                <option value="completed">সম্পন্ন প্রকল্প (Completed)</option>
                                <option value="planned">পরিকল্পনাধীন (Planned)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">অবস্থান / এলাকা</label>
                            <input type="text" name="location" class="form-control" placeholder="যেমন: ধামরাই, ঢাকা">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">মোট বাজেট (টাকা)</label>
                            <input type="number" name="budget" class="form-control" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">বাস্তবায়ন হার (০-১০০%)</label>
                            <input type="number" name="progress_percentage" class="form-control" min="0" max="100" value="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">প্রকল্পের সংক্ষিপ্ত বিবরণ</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">মাঠপর্যায়ের অগ্রগতি প্রতিবেদন (Field Report)</label>
                            <textarea name="field_report" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">ভিডিও লিংক (ইউটিউব/ভিডিও)</label>
                            <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">ছবি আপলোড</label>
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
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

