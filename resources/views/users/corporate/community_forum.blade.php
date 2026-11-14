@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <!-- Title & Top Actions -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-comments text-success me-2"></i> কমিউনিটি ফোরাম ও আলোচনা বোর্ড</h4>
                    <p class="text-muted small mb-0">সদস্য, বিনিয়োগকারী ও অংশীদারদের মধ্যকার উন্মুক্ত অভিজ্ঞতা বিনিময়, আলোচনা ও নোটিশ বোর্ড।</p>
                </div>
                <button class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#newPostModal">
                    <i class="fa fa-plus me-1"></i> নতুন আলোচনা শুরু করুন
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Posts Feed -->
                <div class="col-lg-8">
                    @forelse($posts as $post)
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ !empty($post->user->avatar) && file_exists(public_path($post->user->avatar)) ? asset($post->user->avatar) : asset('frontend/assets/images/team/member2.jpg') }}" 
                                     alt="{{ $post->user->name ?? 'User' }}" 
                                     class="rounded-circle border" 
                                     style="width: 48px; height: 48px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">{{ $post->user->name ?? 'সদস্য' }}</h6>
                                    <span class="badge bg-light text-muted font-size-11">{{ $post->user->membership_card_type ? strtoupper($post->user->membership_card_type) . ' MEMBER' : 'MEMBER' }}</span>
                                    <span class="text-muted font-size-11 ms-2"><i class="fa fa-clock-o me-1"></i> {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                            <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill small">
                                {{ ucfirst(str_replace('_', ' ', $post->category)) }}
                            </span>
                        </div>

                        <h5 class="fw-bold text-dark mb-2">{{ $post->title }}</h5>
                        <p class="text-secondary small leading-relaxed mb-3" style="line-height: 1.7;">
                            {{ $post->content }}
                        </p>

                        @if($post->image)
                        <div class="mb-3">
                            <img src="{{ asset($post->image) }}" alt="Post image" class="img-fluid rounded-3" style="max-height: 320px; width: 100%; object-fit: cover;">
                        </div>
                        @endif

                        <!-- Comments Section -->
                        <div class="pt-3 border-top mt-2">
                            <span class="text-muted small fw-bold d-block mb-2">
                                <i class="fa fa-comment-o me-1"></i> মন্তব্যসমূহ ({{ $post->comments->count() }}):
                            </span>

                            <div class="comments-list mb-3">
                                @foreach($post->comments->take(4) as $comm)
                                <div class="p-2 px-3 bg-light rounded-3 mb-2 small">
                                    <strong class="text-dark">{{ $comm->user->name ?? 'সদস্য' }}:</strong>
                                    <span class="text-secondary">{{ $comm->comment }}</span>
                                </div>
                                @endforeach
                            </div>

                            <!-- Comment Input Form -->
                            <form action="{{ route('user.corporate.community.comment', $post->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="text" name="comment" class="form-control form-control-sm rounded-pill bg-light border-0" placeholder="আপনার গঠনমূলক মতামত লিখুন..." required>
                                <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="card border-0 shadow-sm rounded-4 p-5 bg-white text-center">
                        <i class="fa fa-comments-o text-muted fa-3x mb-3"></i>
                        <h5 class="text-muted">ফোরামে এখনো কোনো পোস্ট তৈরি করা হয়নি।</h5>
                        <p class="text-muted small mb-3">প্রথম আলোচনাটি শুরু করুন এবং অন্যান্য সদস্যদের সাথে যুক্ত হন।</p>
                        <button class="btn btn-success btn-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#newPostModal">পোস্ট করুন</button>
                    </div>
                    @endforelse

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $posts->links() }}
                    </div>
                </div>

                <!-- Sidebar Guidelines -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa fa-info-circle text-primary me-2"></i> ফোরামের নীতিমালা</h6>
                        <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8;">
                            <li>মার্জিত ভাষা ও পারস্পরিক শ্রদ্ধাবোধ বজায় রাখুন।</li>
                            <li>প্রকল্পের অভিজ্ঞতা ও গঠনমূলক পরামর্শ শেয়ার করুন।</li>
                            <li>কোনো বিভ্রান্তিকর তথ্য বা গুজবে কান দেবেন না।</li>
                            <li>যেকোনো অভিযোগের ক্ষেত্রে সরাসরি 'অভিযোগ ও পরামর্শ' মেনু ব্যবহার করুন।</li>
                        </ul>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #064e3b, #047857);">
                        <h6 class="fw-bold text-white mb-2"><i class="fa fa-bell me-2"></i> অফিসিয়াল নোটিশ</h6>
                        <p class="small text-light opacity-90 mb-3">প্রশাসনিক সিদ্ধান্ত ও লভ্যাংশ ঘোষণার জন্য অফিসিয়াল নোটিশ বোর্ড দেখুন।</p>
                        <a href="{{ route('corporate.notices') }}" class="btn btn-warning text-dark btn-sm rounded-pill fw-bold w-100">
                            নোটিশ বোর্ড দেখুন <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- New Post Modal -->
<div class="modal fade" id="newPostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">নতুন আলোচনা পোস্ট করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.corporate.community.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">ক্যাটাগরি *</label>
                        <select name="category" class="form-select" required>
                            <option value="general">সাধারণ আলোচনা (General)</option>
                            <option value="investment_discussion">বিনিয়োগ ও প্রকল্প আলোচনা</option>
                            <option value="project_update">মাঠপর্যায়ের আপডেট</option>
                            <option value="suggestion">পরামর্শ ও আইডিয়া</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">শিরোনাম (Title) *</label>
                        <input type="text" name="title" class="form-control" placeholder="আলোচনার মূল বিষয়" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">বিস্তারিত বক্তব্য *</label>
                        <textarea name="content" class="form-control" rows="4" placeholder="আপনার বক্তব্য বা প্রশ্ন লিখুন..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">ছবি সংযুক্ত করুন (ঐচ্ছিক)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">প্রকাশ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

