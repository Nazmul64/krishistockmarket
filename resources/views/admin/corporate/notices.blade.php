@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-bullhorn text-primary me-2"></i> নোটিশ বোর্ড ও প্রাতিষ্ঠানিক ঘোষণা ব্যবস্থাপনা (Notice Board Management)</h4>
                    <p class="text-muted small mb-0">প্রতিষ্ঠানের সাধারণ ও জরুরি নোটিশ, সার্কুলার, বিনিয়োগ বিজ্ঞপ্তি এবং পিডিএফ সংযুক্তি প্রকাশ ও নিয়ন্ত্রণ।</p>
                </div>
                <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addNoticeModal">
                    <i class="fa fa-plus me-1"></i> নতুন নোটিশ প্রকাশ
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
                    <table class="table table-bordered table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>পিন / অবস্থা</th>
                                <th>ধরন</th>
                                <th>শিরোনাম</th>
                                <th>সংযুক্তি (ডকুমেন্ট)</th>
                                <th>প্রকাশের তারিখ</th>
                                <th>ভিউ সংখ্যা</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notices as $n)
                            <tr>
                                <td>
                                    @if($n->is_pinned)
                                        <span class="badge bg-danger text-white"><i class="fa fa-thumbtack me-1"></i> পিন করা</span>
                                    @endif
                                    @if($n->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success">সক্রিয়</span>
                                    @else
                                        <span class="badge bg-secondary">নিষ্ক্রিয়</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $n->notice_type == 'urgent' ? 'bg-danger' : 'bg-primary-light text-primary' }} text-capitalize">
                                        {{ $n->notice_type }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $n->title }}</div>
                                    <small class="text-muted">{{ Str::limit(strip_tags($n->content), 80) }}</small>
                                </td>
                                <td>
                                    @if($n->attachment_file)
                                        <a href="{{ asset($n->attachment_file) }}" target="_blank" class="btn btn-xs btn-outline-info rounded-pill px-2">
                                            <i class="fa fa-file-pdf me-1"></i> ফাইল দেখুন
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $n->publish_date }}</td>
                                <td><span class="badge bg-light text-dark">{{ $n->views_count }} বার</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $n->id }}" title="এডিট">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.corporate.notices.destroy', $n->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে নোটিশটি মুছে ফেলতে চান?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-circle" title="মুছে ফেলুন">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $n->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark"><i class="fa fa-edit text-primary me-2"></i> নোটিশ সম্পাদনা</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.corporate.notices.update', $n->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-8">
                                                        <label class="form-label small fw-semibold">নোটিশের শিরোনাম *</label>
                                                        <input type="text" name="title" class="form-control rounded-3" value="{{ $n->title }}" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-semibold">নোটিশের ধরন *</label>
                                                        <select name="notice_type" class="form-select rounded-3">
                                                            <option value="general" {{ $n->notice_type == 'general' ? 'selected' : '' }}>সাধারণ নোটিশ</option>
                                                            <option value="urgent" {{ $n->notice_type == 'urgent' ? 'selected' : '' }}>জরুরি নোটিশ</option>
                                                            <option value="tender" {{ $n->notice_type == 'tender' ? 'selected' : '' }}>দরপত্র ও বিজ্ঞপ্তি</option>
                                                            <option value="meeting" {{ $n->notice_type == 'meeting' ? 'selected' : '' }}>সভা ও সম্মেলন</option>
                                                            <option value="dividend" {{ $n->notice_type == 'dividend' ? 'selected' : '' }}>লভ্যাংশ ও শেয়ার</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold">প্রকাশের তারিখ</label>
                                                        <input type="date" name="publish_date" class="form-control rounded-3" value="{{ $n->publish_date }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold">নতুন ফাইল সংযুক্তি (পিডিএফ / ইমেজ)</label>
                                                        <input type="file" name="attachment_file" class="form-control rounded-3" accept=".pdf,.doc,.docx,.jpg,.png">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold">বিস্তারিত বিবরণ *</label>
                                                        <textarea name="content" rows="6" class="form-control rounded-3" required>{{ $n->content }}</textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" name="is_pinned" id="pin{{ $n->id }}" value="1" {{ $n->is_pinned ? 'checked' : '' }}>
                                                            <label class="form-check-label small fw-semibold" for="pin{{ $n->id }}">উপরে পিন করে রাখুন</label>
                                                        </div>
                                                        <div class="form-check form-check-inline ms-3">
                                                            <input class="form-check-input" type="checkbox" name="is_active" id="act{{ $n->id }}" value="1" {{ $n->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label small fw-semibold" for="act{{ $n->id }}">পাবলিকলি প্রদর্শন করুন</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">বাতিল</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">আপডেট সংরক্ষণ করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">কোনো নোটিশ পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $notices->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Add Notice Modal -->
<div class="modal fade" id="addNoticeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-bullhorn text-primary me-2"></i> নতুন নোটিশ প্রকাশ করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.corporate.notices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">নোটিশের শিরোনাম *</label>
                            <input type="text" name="title" class="form-control rounded-3" placeholder="উদাঃ বার্ষিক সাধারণ সভার বিজ্ঞপ্তি ২০২৬" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">নোটিশের ধরন *</label>
                            <select name="notice_type" class="form-select rounded-3" required>
                                <option value="general">সাধারণ নোটিশ</option>
                                <option value="urgent">জরুরি নোটিশ</option>
                                <option value="tender">দরপত্র ও বিজ্ঞপ্তি</option>
                                <option value="meeting">সভা ও সম্মেলন</option>
                                <option value="dividend">লভ্যাংশ ও শেয়ার</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">প্রকাশের তারিখ</label>
                            <input type="date" name="publish_date" class="form-control rounded-3" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">ফাইল সংযুক্তি (পিডিএফ / ইমেজ)</label>
                            <input type="file" name="attachment_file" class="form-control rounded-3" accept=".pdf,.doc,.docx,.jpg,.png">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">বিস্তারিত বিবরণ *</label>
                            <textarea name="content" rows="6" class="form-control rounded-3" placeholder="নোটিশের বিস্তারিত বক্তব্য..." required></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_pinned" id="pin_new" value="1">
                                <label class="form-check-label small fw-semibold" for="pin_new">উপরে পিন করে রাখুন</label>
                            </div>
                            <div class="form-check form-check-inline ms-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="act_new" value="1" checked>
                                <label class="form-check-label small fw-semibold" for="act_new">সরাসরি ওয়েবসাইটে প্রকাশ করুন</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">নোটিশ প্রকাশ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

