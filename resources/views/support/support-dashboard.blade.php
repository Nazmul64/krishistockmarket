@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-primary"><i class="fa fa-headphones me-2"></i> Support Hub (গ্রাহক সেবা ও সহায়তা ড্যাশবোর্ড)</h3>
                    <p class="text-muted mb-0">টিকেট কিউ, কাস্টমার আইডেন্টিটি ভেরিফিকেশন ও লাইভ চ্যাট সহায়তা (Scope: Assigned Tickets / Customers)</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.chat.index') }}" class="btn btn-warning text-dark fw-bold">
                        <i class="fa fa-comments me-1"></i> লাইভ চ্যাট হাব (Live Chat Hub)
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-danger-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">জরুরি টিকেট (Urgent)</span>
                                <h3 class="mb-0 fw-bold text-danger">{{ $urgentCount }} টি</h3>
                            </div>
                            <i class="fa fa-fire text-danger font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-warning-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">উন্মুক্ত টিকেট (Open)</span>
                                <h3 class="mb-0 fw-bold text-warning">{{ $openCount }} টি</h3>
                            </div>
                            <i class="fa fa-folder-open text-warning font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-primary-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">চলমান সমাধান (In Progress)</span>
                                <h3 class="mb-0 fw-bold text-primary">{{ $inProgressCount }} টি</h3>
                            </div>
                            <i class="fa fa-refresh text-primary font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-success-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">সমাধানকৃত (Resolved)</span>
                                <h3 class="mb-0 fw-bold text-success">{{ $resolvedCount }} টি</h3>
                            </div>
                            <i class="fa fa-check-circle text-success font-size-28"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Identity Verification Workflow Tool (Section 4.5) -->
            <div class="box border-top border-4 border-info mb-4">
                <div class="box-header with-border bg-light">
                    <h4 class="box-title text-info"><i class="fa fa-id-card me-2"></i> Customer Identity Verification (গ্রাহক পরিচিতি যাচাই টুল)</h4>
                    <small class="text-muted d-block">গ্রাহক সেবা দেওয়ার পূর্বে পরিচয় নিশ্চিতকরণ ও একাউন্ট স্ট্যাটাস যাচাই করুন (সংবেদনশীল ডাটা মাস্কড থাকবে)।</small>
                </div>
                <div class="box-body">
                    <form action="{{ route('support.dashboard') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-9 col-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                                <input type="text" name="verify_customer" class="form-control" placeholder="গ্রাহকের ফোন নম্বর, ইমেইল অথবা ইউজারনেম লিখুন..." value="{{ request('verify_customer') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <button type="submit" class="btn btn-info text-white w-100">
                                <i class="fa fa-search me-1"></i> পরিচয় যাচাই করুন (Verify)
                            </button>
                        </div>
                    </form>

                    @if($verifiedCustomer)
                    <div class="alert alert-light border mt-3 mb-0">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="fw-bold font-size-15 text-dark"><i class="fa fa-user me-1 text-primary"></i> {{ $verifiedCustomer->name }}</div>
                                <div class="text-muted font-size-12">Username: <code>{{ $verifiedCustomer->username }}</code></div>
                            </div>
                            <div class="col-md-4">
                                <div class="font-size-13"><i class="fa fa-phone text-muted me-1"></i> {{ Str::mask($verifiedCustomer->phone ?? '', '*', 3, 5) }}</div>
                                <div class="font-size-13"><i class="fa fa-envelope text-muted me-1"></i> {{ Str::mask($verifiedCustomer->email ?? '', '*', 2, 8) }}</div>
                            </div>
                            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                <span class="badge {{ $verifiedCustomer->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                    স্ট্যাটাস: {{ ucfirst($verifiedCustomer->status) }}
                                </span>
                                <span class="badge bg-primary ms-1">
                                    কার্ড: {{ strtoupper($verifiedCustomer->membership_card_type ?? 'Regular') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @elseif(request('verify_customer'))
                    <div class="alert alert-warning mt-3 mb-0 py-2 font-size-13">
                        <i class="fa fa-exclamation-triangle me-1"></i> '{{ request('verify_customer') }}' নম্বরে কোনো নিবন্ধিত গ্রাহক পাওয়া যায়নি।
                    </div>
                    @endif
                </div>
            </div>

            <!-- Support Tickets Queue -->
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title text-primary"><i class="fa fa-ticket me-2"></i> গ্রাহক সাপোর্ট টিকেট কিউ (Tickets Queue)</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('support.dashboard') }}" class="btn btn-xs btn-outline btn-secondary">সব টিকেট</a>
                        <a href="{{ route('support.dashboard', ['priority' => 'urgent']) }}" class="btn btn-xs btn-outline btn-danger">Urgent টিকেট</a>
                    </div>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="bg-light">
                                <tr>
                                    <th>টিকেট নং</th>
                                    <th>গ্রাহক</th>
                                    <th>ক্যাটাগরি ও বিষয়</th>
                                    <th>অগ্রাধিকার (Priority)</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>সর্বশেষ বার্তা</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $t)
                                <tr>
                                    <td><span class="fw-bold text-dark">#{{ $t->ticket_number }}</span></td>
                                    <td>
                                        <div class="fw-bold">{{ $t->customer_name }}</div>
                                        <small class="text-muted">{{ Str::mask($t->customer_phone ?? '', '*', 3, 5) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border me-1">{{ $t->category }}</span>
                                        <span class="fw-bold text-dark">{{ $t->subject }}</span>
                                    </td>
                                    <td>
                                        @if($t->priority === 'urgent')
                                            <span class="badge bg-danger"><i class="fa fa-bolt me-1"></i> Urgent</span>
                                        @elseif($t->priority === 'high')
                                            <span class="badge bg-warning text-dark">High</span>
                                        @elseif($t->priority === 'medium')
                                            <span class="badge bg-info">Medium</span>
                                        @else
                                            <span class="badge bg-secondary">Low</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($t->status === 'open')
                                            <span class="badge bg-danger-light text-danger">Open</span>
                                        @elseif($t->status === 'in_progress')
                                            <span class="badge bg-primary-light text-primary">In Progress</span>
                                        @elseif($t->status === 'resolved')
                                            <span class="badge bg-success-light text-success">Resolved</span>
                                        @elseif($t->status === 'escalated')
                                            <span class="badge bg-warning-light text-warning">Escalated</span>
                                        @else
                                            <span class="badge bg-secondary">Closed</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $t->last_reply_at ? \Carbon\Carbon::parse($t->last_reply_at)->diffForHumans() : ($t->created_at ? \Carbon\Carbon::parse($t->created_at)->diffForHumans() : '-') }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#ticketReplyModal{{ $t->id }}">
                                            <i class="fa fa-reply me-1"></i> উত্তর দিন (Reply)
                                        </button>

                                        @if(auth()->user()->hasPermission('tickets.assign'))
                                            <button type="button" class="btn btn-xs btn-info text-white ms-1" data-bs-toggle="modal" data-bs-target="#ticketAssignModal{{ $t->id }}" title="টিকেট হস্তান্তর">
                                                <i class="fa fa-user-plus me-1"></i> এসাইন
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                @if(auth()->user()->hasPermission('tickets.assign'))
                                <!-- Ticket Assign Modal -->
                                <div class="modal fade" id="ticketAssignModal{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('support.ticket.assign', $t->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title"><i class="fa fa-user-plus me-2"></i> টিকেট এসাইন করুন: #{{ $t->ticket_number }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-muted small mb-3">বিষয়: <strong>{{ $t->subject }}</strong></p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">সাপোর্ট এজেন্ট নির্বাচন করুন <span class="text-danger">*</span></label>
                                                        <select name="assigned_to" class="form-select" required>
                                                            <option value="">-- এজেন্ট বেছে নিন --</option>
                                                            @foreach($supportAgents ?? [] as $agent)
                                                                <option value="{{ $agent->id }}" {{ $t->assigned_to == $agent->id ? 'selected' : '' }}>
                                                                    {{ $agent->name }} ({{ strtoupper($agent->role) }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                    <button type="submit" class="btn btn-info text-white"><i class="fa fa-check me-1"></i> হস্তান্তর সম্পন্ন করুন</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Ticket Reply Modal -->
                                <div class="modal fade" id="ticketReplyModal{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('support.ticket.reply', $t->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title"><i class="fa fa-headphones me-2"></i> টিকেটে উত্তর প্রদান: #{{ $t->ticket_number }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="bg-light p-3 rounded mb-3">
                                                        <h6 class="fw-bold text-dark mb-1">{{ $t->subject }}</h6>
                                                        <p class="text-muted font-size-13 mb-0">{{ $t->description }}</p>
                                                    </div>

                                                    <!-- Previous Replies -->
                                                    @if($t->replies->count() > 0)
                                                    <div class="mb-3 max-h-200 overflow-auto border rounded p-2">
                                                        @foreach($t->replies as $reply)
                                                        <div class="p-2 mb-2 rounded {{ $reply->is_staff ? 'bg-primary-light text-end ms-4' : 'bg-light me-4' }}">
                                                            <div class="fw-bold font-size-12">{{ $reply->user_name }} ({{ $reply->is_staff ? 'Support Staff' : 'Customer' }})</div>
                                                            <div class="font-size-13">{{ $reply->message }}</div>
                                                            <small class="text-muted font-size-10">{{ $reply->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    @endif

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">উত্তর লিখুন (Support Response) <span class="text-danger">*</span></label>
                                                        <textarea name="message" class="form-control" rows="4" placeholder="গ্রাহককে সহায়তা বার্তা লিখুন..." required></textarea>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">টিকেট স্ট্যাটাস পরিবর্তন</label>
                                                            <select name="status" class="form-select">
                                                                <option value="in_progress" {{ $t->status === 'in_progress' ? 'selected' : '' }}>In Progress (চলমান)</option>
                                                                <option value="resolved" {{ $t->status === 'resolved' ? 'selected' : '' }}>Resolved (সমাধানকৃত)</option>
                                                                <option value="escalated" {{ $t->status === 'escalated' ? 'selected' : '' }}>Escalate (উর্ধ্বতন শাখায়)</option>
                                                                <option value="closed" {{ $t->status === 'closed' ? 'selected' : '' }}>Closed (বন্ধ)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-send me-1"></i> বার্তা প্রেরণ করুন</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fa fa-check-circle-o font-size-36 text-success mb-2 d-block"></i>
                                        কোন সক্রিয় সাপোর্ট টিকেট নেই।
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($tickets->hasPages())
                <div class="box-footer clearfix">
                    {{ $tickets->links() }}
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
