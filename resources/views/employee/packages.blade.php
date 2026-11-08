@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-cube me-2 text-primary"></i> Our Packages (আওয়ার প্যাকেজসমূহ)</h4>
                    <p class="text-muted mb-0">হোমপেজের "আওয়ার প্যাকেজসমূহ" স্লাইডারের ছবি প্রদর্শন ও আপলোড</p>
                </div>
                <div>
                    <a href="{{ route('employee.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ডে ফিরুন
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- Add New Package Form (Permission Checked) -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark">
                                <i class="fa fa-upload me-2 text-success"></i> নতুন প্যাকেজ ইমেজ যোগ করুন
                            </h4>
                        </div>
                        <div class="box-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            {{-- UI Level Filtering: Upload Image Button is only visible if user has packages.upload_image --}}
                            @if(auth()->user()->hasPermission('packages.upload_image') || auth()->user()->hasPermission('packages.create'))
                                <form method="POST" action="{{ route('employee.packages.store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group mb-4">
                                        <label class="form-label fw-bold">প্যাকেজ ছবি (Package Image) <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control form-control-lg" name="image" required accept="image/*">
                                        <small class="text-muted d-block mt-1">প্যাকেজের ছবি বা ব্যানার সিলেক্ট করুন (JPG, PNG, WEBP, সর্বোচ্চ ৫MB)</small>
                                        @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 py-2 fs-15 fw-bold btn-upload">
                                        <i class="fa fa-upload me-1"></i> Upload Image / আপলোড করুন
                                    </button>
                                </form>
                            @else
                                <div class="p-3 bg-light rounded text-center border">
                                    <i class="fa fa-lock text-warning fa-2x mb-2 d-block"></i>
                                    <p class="fw-bold text-dark mb-1">ছবি আপলোড অনুমতি নেই</p>
                                    <p class="text-muted small mb-0">
                                        আপনার অ্যাকাউন্টে শুধুমাত্র প্যাকেজ দেখার (View-Only) পারমিশন রয়েছে। ছবি আপলোড করতে হলে অ্যাডমিনের নিকট থেকে <span class="badge bg-warning text-dark">packages.upload_image</span> পারমিশন নিতে হবে।
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Packages List Table -->
                <div class="col-xl-8 col-lg-7">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h4 class="box-title text-dark">
                                <i class="fa fa-images me-2 text-info"></i> সকল প্যাকেজ ইমেজ ({{ count($packages) }})
                            </h4>
                            <span class="badge bg-success-light text-success fw-bold">Active Packages</span>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;">#</th>
                                            <th>ছবি Preview</th>
                                            <th>আপলোডের তারিখ</th>
                                            <th class="text-center" style="width: 100px;">স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($packages as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <img src="{{ asset($item->image) }}" alt="Package Image" style="max-width: 140px; max-height: 85px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
                                            </td>
                                            <td style="font-size: 13px; color: #64748b;">
                                                {{ $item->created_at ? $item->created_at->format('d M, Y - h:i A') : 'N/A' }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">Live</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                কোনো প্যাকেজ ইমেজ পাওয়া যায়নি।
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
@endsection

