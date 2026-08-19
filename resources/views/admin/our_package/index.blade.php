@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Our Packages (আওয়ার প্যাকেজসমূহ)</h4>
                    <p class="text-muted mb-0">হোমপেজের "আওয়ার প্যাকেজসমূহ" স্লাইডারের ছবি আপলোড ও ম্যানেজমেন্ট</p>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- Add New Package Form -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-plus-circle me-2 text-success"></i> নতুন প্যাকেজ ইমেজ যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('admin.our_packages.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">প্যাকেজ ছবি (Package Image) <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control form-control-lg" name="image" required accept="image/*">
                                    <small class="text-muted d-block mt-1">প্যাকেজের ছবি বা ব্যানার সিলেক্ট করুন (JPG, PNG, WEBP)</small>
                                    @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-2 fs-15 fw-bold"><i class="fa fa-upload me-1"></i> আপলোড করুন</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Packages List Table -->
                <div class="col-xl-8 col-lg-7">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-images me-2 text-info"></i> সকল প্যাকেজ ইমেজ ({{ count($packages) }})</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;">#</th>
                                            <th>ছবি Preview</th>
                                            <th style="width: 180px;">আপলোডের তারিখ</th>
                                            <th class="text-center" style="width: 100px;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($packages as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <img src="{{ asset($item->image) }}" alt="Package Image" style="max-width: 160px; max-height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
                                            </td>
                                            <td style="font-size: 13px; color: #64748b;">
                                                {{ $item->created_at ? $item->created_at->format('d M, Y - h:i A') : 'N/A' }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.our_packages.destroy', $item->id) }}" class="btn btn-sm btn-danger px-3" onclick="return confirm('আপনি কি নিশ্চিত যে এই ইমেজটি ডিলিট করতে চান?')" title="Delete"><i class="fa fa-trash me-1"></i> ডিলিট</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">কোনো প্যাকেজ ইমেজ আপলোড করা হয়নি। বামপাশের ফর্ম থেকে ছবি আপলোড করুন।</td>
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
