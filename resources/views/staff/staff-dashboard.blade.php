@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-info"><i class="fa fa-tasks me-2"></i> Staff Dashboard (অপারেশনাল স্টাফ কন্ট্রোল)</h3>
                    <p class="text-muted mb-0">দৈনন্দিন অপারেশনাল কিউ, অর্ডার প্রসেসিং ও কার্য নির্বাহ (Scope: Assigned Tasks)</p>
                </div>
                <div>
                    <span class="badge badge-lg bg-info-light text-info fw-bold px-3 py-2">
                        <i class="fa fa-check-square-o me-1"></i> Operations Queue Active
                    </span>
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
                <div class="col-md-4 col-12">
                    <div class="box box-body p-3 bg-warning-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">অপেক্ষারত অপারেশনাল টাস্ক</span>
                                <h3 class="mb-0 fw-bold text-warning">{{ $pendingTasksCount }} টি</h3>
                            </div>
                            <i class="fa fa-clock-o text-warning font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="box box-body p-3 bg-success-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">সম্পন্ন অপারেশন (Today)</span>
                                <h3 class="mb-0 fw-bold text-success">{{ $completedTasksCount }} টি</h3>
                            </div>
                            <i class="fa fa-check-circle text-success font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="box box-body p-3 bg-primary-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">অ্যাসাইন্ড কাস্টমার কেস</span>
                                <h3 class="mb-0 fw-bold text-primary">{{ $assignedTickets->count() }} টি</h3>
                            </div>
                            <i class="fa fa-headphones text-primary font-size-28"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Operational Queue & Tickets -->
            <div class="row">
                <!-- Customer Inquiry & Escalation -->
                <div class="col-xl-7 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title text-primary"><i class="fa fa-inbox me-2"></i> অ্যাসাইন্ড কাস্টমার ইনকোয়ারি ও কেস</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 font-size-13">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>কেস / টিকিট নং</th>
                                            <th>কাস্টমার নাম</th>
                                            <th>বিষয়</th>
                                            <th>অগ্রাধিকার</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($assignedTickets as $ticket)
                                        <tr>
                                            <td><span class="fw-bold text-dark">#{{ $ticket->ticket_number }}</span></td>
                                            <td>
                                                <div class="fw-bold">{{ $ticket->customer_name }}</div>
                                                <small class="text-muted">{{ Str::mask($ticket->customer_phone ?? '', '*', 3, 5) }}</small>
                                            </td>
                                            <td>{{ Str::limit($ticket->subject, 30) }}</td>
                                            <td>
                                                <span class="badge {{ $ticket->priority === 'urgent' ? 'bg-danger' : ($ticket->priority === 'high' ? 'bg-warning text-dark' : 'bg-info') }}">
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('staff.task.escalate', $ticket->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-outline btn-warning" title="সুপারভাইজরের কাছে এস্কেলেট করুন">
                                                        <i class="fa fa-level-up me-1"></i> Escalate
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">বর্তমানে আপনার কোনো পেন্ডিং কাস্টমার কেস নেই।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Orders Queue -->
                <div class="col-xl-5 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title text-warning"><i class="fa fa-shopping-basket me-2"></i> প্রসেসিং কিউ (Order Queue)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 font-size-13">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>অর্ডার আইডি</th>
                                            <th>পণ্য</th>
                                            <th>স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendingOrders as $order)
                                        <tr>
                                            <td><span class="fw-bold">#ORD-{{ $order->id }}</span></td>
                                            <td>{{ optional($order->item)->title ?? 'বাজার প্যাকেজ' }}</td>
                                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">কিউতে কোনো অপেক্ষারত অর্ডার নেই।</td>
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
