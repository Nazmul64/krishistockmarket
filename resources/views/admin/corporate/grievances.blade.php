@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-comments text-primary me-2"></i> অভিযোগ, মতামত ও প্রতিকার ব্যবস্থাপনা (Grievance Redressal Management)</h4>
                    <p class="text-muted small mb-0">গ্রাহক, সদস্য, বিনিয়োগকারী ও সাধারণের আবেদন, অভিযোগ ও পরামর্শ পর্যালোচনা ও সমাধান প্রদান।</p>
                </div>
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
                                <th>ট্র্যাকিং কোড</th>
                                <th>আবেদনকারী</th>
                                <th>বিষয় ও ক্যাটাগরি</th>
                                <th>সংযুক্ত ফাইল</th>
                                <th>স্ট্যাটাস</th>
                                <th>নিষ্পত্তি / উত্তর</th>
                                <th>তারিখ</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grievances as $g)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary-light text-dark fw-bold font-monospace">{{ $g->tracking_code }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $g->name }}</div>
                                    <small class="text-muted">{{ $g->phone }} {{ $g->email ? ' | ' . $g->email : '' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary text-uppercase font-size-11 mb-1">{{ $g->category }}</span>
                                    <div class="fw-bold text-dark">{{ $g->subject }}</div>
                                    <small class="text-muted">{{ Str::limit($g->details, 80) }}</small>
                                </td>
                                <td>
                                    @if($g->attachment_file)
                                        <a href="{{ asset($g->attachment_file) }}" target="_blank" class="btn btn-xs btn-outline-info rounded-pill">
                                            <i class="fa fa-paperclip me-1"></i> ফাইল
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($g->status == 'resolved')
                                        <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1"><i class="fa fa-check-circle me-1"></i> সমাধানকৃত</span>
                                    @elseif($g->status == 'investigating')
                                        <span class="badge bg-warning bg-opacity-10 text-warning fw-bold px-2 py-1"><i class="fa fa-spinner me-1"></i> তদন্তাধীন</span>
                                    @elseif($g->status == 'rejected')
                                        <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-2 py-1"><i class="fa fa-times-circle me-1"></i> বাতিল</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold px-2 py-1"><i class="fa fa-clock me-1"></i> অপেক্ষমান</span>
                                    @endif
                                </td>
                                <td>
                                    @if($g->admin_response)
                                        <small class="text-dark d-block fw-semibold">{{ Str::limit($g->admin_response, 50) }}</small>
                                        <small class="text-success font-size-11">{{ $g->resolved_at ? date('d M Y, h:i A', strtotime($g->resolved_at)) : '' }}</small>
                                    @else
                                        <span class="text-muted italic">এখনও উত্তর দেওয়া হয়নি</span>
                                    @endif
                                </td>
                                <td>{{ $g->created_at->format('d M, Y') }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#respondModal{{ $g->id }}">
                                        <i class="fa fa-reply me-1"></i> উত্তর দিন
                                    </button>
                                </td>
                            </tr>

                            <!-- Respond Modal -->
                            <div class="modal fade" id="respondModal{{ $g->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark"><i class="fa fa-reply text-primary me-2"></i> অভিযোগের পর্যালোচনা ও সমাধান</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.corporate.grievances.respond', $g->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="p-3 bg-light rounded-3 mb-3">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="small fw-bold text-primary">{{ $g->tracking_code }}</span>
                                                        <span class="small text-muted">{{ $g->created_at->format('d M Y, h:i A') }}</span>
                                                    </div>
                                                    <div class="fw-bold text-dark mb-1">{{ $g->subject }}</div>
                                                    <p class="small text-muted mb-0">{{ $g->details }}</p>
                                                    <hr class="my-2">
                                                    <div class="small text-secondary">
                                                        <i class="fa fa-user me-1"></i> {{ $g->name }} ({{ $g->phone }})
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">স্ট্যাটাস আপডেট *</label>
                                                    <select name="status" class="form-select rounded-3" required>
                                                        <option value="pending" {{ $g->status == 'pending' ? 'selected' : '' }}>অপেক্ষমান (Pending)</option>
                                                        <option value="investigating" {{ $g->status == 'investigating' ? 'selected' : '' }}>তদন্ত চলছে (Investigating)</option>
                                                        <option value="resolved" {{ $g->status == 'resolved' ? 'selected' : '' }}>সমাধানকৃত (Resolved)</option>
                                                        <option value="rejected" {{ $g->status == 'rejected' ? 'selected' : '' }}>বাতিল (Rejected)</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">প্রশাসনিক উত্তর / সমাধান বিবরণ *</label>
                                                    <textarea name="admin_response" rows="4" class="form-control rounded-3" placeholder="আবেদনকারীকে প্রদর্শনের জন্য গৃহীত পদক্ষেপ বা উত্তর লিখুন..." required>{{ $g->admin_response }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">বাতিল</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">উত্তর প্রেরণ করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">কোনো অভিযোগ বা মতামত পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $grievances->links() }}
                </div>
            </div>

        </section>
    </div>
</div>
@endsection

