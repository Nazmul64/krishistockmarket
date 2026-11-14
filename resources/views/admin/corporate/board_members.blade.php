@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-users text-primary me-2"></i> পরিচালনা পর্ষদ ব্যবস্থাপনা (Board of Directors)</h4>
                    <p class="text-muted small mb-0">প্রতিষ্ঠানের বোর্ড সদস্য, চেয়ারম্যান, এমডি, সিইও ও ডিরেক্টরদের প্রোফাইল এবং বাণী ব্যবস্থাপনা।</p>
                </div>
                <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                    <i class="fa fa-plus me-1"></i> নতুন সদস্য যুক্ত করুন
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
                                <th>নাম (বাংলা ও ইংরেজি)</th>
                                <th>পদবী ও ভূমিকা</th>
                                <th>ক্যাটাগরি</th>
                                <th>যোগাযোগ</th>
                                <th>ক্রম</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $m)
                            <tr>
                                <td>
                                    <img src="{{ !empty($m->photo) && file_exists(public_path($m->photo)) ? asset($m->photo) : asset('frontend/assets/images/team/member1.jpg') }}" 
                                         alt="{{ $m->name_bn }}" 
                                         class="rounded-circle border" 
                                         style="width: 45px; height: 45px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $m->name_bn ?: $m->name }}</div>
                                    <small class="text-muted">{{ $m->name }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary fw-bold">{{ $m->designation_bn ?: $m->designation }}</span>
                                    <div class="text-muted font-size-11 mt-1">{{ Str::limit($m->administrative_role, 35) }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ match($m->role_type) {
                                        'chairman' => 'bg-warning text-dark',
                                        'md' => 'bg-danger text-white',
                                        'ceo' => 'bg-success text-white',
                                        default => 'bg-light text-dark',
                                    } }} text-uppercase">
                                        {{ $m->role_type }}
                                    </span>
                                </td>
                                <td>
                                    <div>{{ $m->phone ?: '-' }}</div>
                                    <small class="text-muted">{{ $m->email ?: '' }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $m->order_num }}</span></td>
                                <td>
                                    <span class="badge {{ $m->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $m->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-outline-info btn-xs rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $m->id }}">
                                        <i class="fa fa-edit"></i> এডিট
                                    </button>
                                    <a href="{{ route('admin.corporate.board_members.destroy', $m->id) }}" onclick="return confirm('মুছে ফেলতে চান?')" class="btn btn-outline-danger btn-xs rounded-pill px-2">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $m->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark">সদস্যের তথ্য হালনাগাদ</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.corporate.board_members.update', $m->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold text-muted">নাম (বাংলা)</label>
                                                        <input type="text" name="name_bn" class="form-control" value="{{ $m->name_bn }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold text-muted">নাম (ইংরেজি)</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $m->name }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold text-muted">পদবী (বাংলা)</label>
                                                        <input type="text" name="designation_bn" class="form-control" value="{{ $m->designation_bn }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold text-muted">পদবী (ইংরেজি)</label>
                                                        <input type="text" name="designation" class="form-control" value="{{ $m->designation }}" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-semibold text-muted">ভূমিকা ক্যাটাগরি</label>
                                                        <select name="role_type" class="form-select">
                                                            <option value="chairman" {{ $m->role_type == 'chairman' ? 'selected' : '' }}>Chairman</option>
                                                            <option value="md" {{ $m->role_type == 'md' ? 'selected' : '' }}>Managing Director (MD)</option>
                                                            <option value="ceo" {{ $m->role_type == 'ceo' ? 'selected' : '' }}>Chief Executive Officer (CEO)</option>
                                                            <option value="marketing_head" {{ $m->role_type == 'marketing_head' ? 'selected' : '' }}>Head of Marketing</option>
                                                            <option value="finance_head" {{ $m->role_type == 'finance_head' ? 'selected' : '' }}>Head of Finance</option>
                                                            <option value="director" {{ $m->role_type == 'director' ? 'selected' : '' }}>Director</option>
                                                            <option value="advisor" {{ $m->role_type == 'advisor' ? 'selected' : '' }}>Advisor</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-semibold text-muted">মোবাইল</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $m->phone }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-semibold text-muted">ডিসপ্লে ক্রম</label>
                                                        <input type="number" name="order_num" class="form-control" value="{{ $m->order_num }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold text-muted">সংক্ষিপ্ত পরিচিতি (Bio)</label>
                                                        <textarea name="bio" class="form-control" rows="2">{{ $m->bio }}</textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold text-muted">প্রশাসনিক দায়িত্ব ও ভূমিকা</label>
                                                        <textarea name="administrative_role" class="form-control" rows="2">{{ $m->administrative_role }}</textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold text-muted">বাণীর শিরোনাম (Speech Title)</label>
                                                        <input type="text" name="speech_title" class="form-control" value="{{ $m->speech_title }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold text-muted">নেতৃত্বের বক্তব্য / বাণী (Speech Message)</label>
                                                        <textarea name="speech_message" class="form-control" rows="4">{{ $m->speech_message }}</textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold text-muted">ছবি পরিবর্তন</label>
                                                        <input type="file" name="photo" class="form-control" accept="image/*">
                                                    </div>
                                                    <div class="col-md-6 d-flex align-items-center mt-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="is_active" id="active{{ $m->id }}" {{ $m->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="active{{ $m->id }}">ওয়েবসাইটে সক্রিয় প্রদর্শন করুন</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">আপডেট করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">কোনো বোর্ড সদস্যের তথ্য পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">নতুন বোর্ড সদস্য যুক্ত করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.corporate.board_members.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">নাম (বাংলা)</label>
                            <input type="text" name="name_bn" class="form-control" placeholder="যেমন: ড. কামাল হোসেন">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">নাম (ইংরেজি) *</label>
                            <input type="text" name="name" class="form-control" placeholder="যেমন: Dr. Kamal Hossain" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">পদবী (বাংলা)</label>
                            <input type="text" name="designation_bn" class="form-control" placeholder="যেমন: চেয়ারম্যান">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">পদবী (ইংরেজি) *</label>
                            <input type="text" name="designation" class="form-control" placeholder="যেমন: Chairman" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">ভূমিকা ক্যাটাগরি *</label>
                            <select name="role_type" class="form-select" required>
                                <option value="director">Director</option>
                                <option value="chairman">Chairman</option>
                                <option value="md">Managing Director (MD)</option>
                                <option value="ceo">Chief Executive Officer (CEO)</option>
                                <option value="marketing_head">Head of Marketing</option>
                                <option value="finance_head">Head of Finance</option>
                                <option value="advisor">Advisor</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">মোবাইল</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">ক্রম (Order Number)</label>
                            <input type="number" name="order_num" class="form-control" value="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">সংক্ষিপ্ত পরিচিতি (Bio)</label>
                            <textarea name="bio" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">প্রশাসনিক দায়িত্ব ও ভূমিকা</label>
                            <textarea name="administrative_role" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">বাণীর শিরোনাম</label>
                            <input type="text" name="speech_title" class="form-control" placeholder="যেমন: টেকসই উন্নয়ন ও সমৃদ্ধির পথে...">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">বক্তব্য / বাণী</label>
                            <textarea name="speech_message" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">ছবি আপলোড</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 d-flex align-items-center mt-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="activeNew" checked>
                                <label class="form-check-label" for="activeNew">সক্রিয় হিসেবে প্রদর্শন করুন</label>
                            </div>
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

