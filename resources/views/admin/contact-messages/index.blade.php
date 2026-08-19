@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-envelope text-info me-2"></i>যোগাযোগের বার্তা (Contact Messages)</h4>
                    <p class="text-muted mb-0">ওয়েবসাইট কন্টাক্ট ফরম থেকে প্রেরিত সকল কাস্টমার বার্তা ও ইনকোয়ারি</p>
                </div>
                @if($unreadCount > 0)
                    <div>
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            <i class="ti-bell me-1"></i> {{ $unreadCount }} টি নতুন অপঠিত বার্তা
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti-check-box me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- Filters & Search Header -->
                        <div class="card-header border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.contact_messages.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    সকল (All)
                                </a>
                                <a href="{{ route('admin.contact_messages.index', ['status' => 'unread']) }}" class="btn btn-sm {{ request('status') == 'unread' ? 'btn-danger' : 'btn-outline-danger' }}">
                                    নতুন/অপঠিত (Unread)
                                </a>
                                <a href="{{ route('admin.contact_messages.index', ['status' => 'read']) }}" class="btn btn-sm {{ request('status') == 'read' ? 'btn-info' : 'btn-outline-info' }}">
                                    পঠিত (Read)
                                </a>
                                <a href="{{ route('admin.contact_messages.index', ['status' => 'replied']) }}" class="btn btn-sm {{ request('status') == 'replied' ? 'btn-success' : 'btn-outline-success' }}">
                                    উত্তর প্রদানকৃত (Replied)
                                </a>
                            </div>

                            <form action="{{ route('admin.contact_messages.index') }}" method="GET" class="d-flex align-items-center" style="max-width: 320px;">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                <div class="input-group">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="নাম, ফোন বা বার্তা দিয়ে খুঁজুন...">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="ti-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <!-- Table Body -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 60px;">#</th>
                                            <th>প্রেরক (কাস্টমার)</th>
                                            <th>ফোন ও ইমেইল</th>
                                            <th>বিষয় (Subject)</th>
                                            <th>বার্তা সংক্ষেপ</th>
                                            <th>স্ট্যাটাস</th>
                                            <th>তারিখ ও সময়</th>
                                            <th class="text-end" style="min-width: 140px;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($messages as $key => $msg)
                                            <tr class="{{ $msg->status === 'unread' ? 'fw-bold bg-light-info' : '' }}">
                                                <td>{{ $messages->firstItem() + $key }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar rounded-circle bg-secondary-light text-secondary me-2 text-center d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-weight: bold;">
                                                            {{ strtoupper(substr($msg->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="text-dark">{{ $msg->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div><i class="fa fa-phone text-success me-1"></i> <a href="tel:{{ $msg->phone }}">{{ $msg->phone }}</a></div>
                                                    @if($msg->email)
                                                        <div class="text-muted fs-7"><i class="fa fa-envelope text-info me-1"></i> {{ $msg->email }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-dark">{{ $msg->subject ?? 'সাধারণ অনুসন্ধান' }}</span>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 260px;" title="{{ $msg->message }}">
                                                        {{ Str::limit($msg->message, 50) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($msg->status === 'unread')
                                                        <span class="badge bg-danger">অপঠিত (Unread)</span>
                                                    @elseif($msg->status === 'read')
                                                        <span class="badge bg-info">পঠিত (Read)</span>
                                                    @elseif($msg->status === 'replied')
                                                        <span class="badge bg-success">উত্তর প্রদানকৃত (Replied)</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $msg->created_at->format('d M, Y - h:i A') }}</small>
                                                </td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-info btn-xs me-1 view-msg-btn" 
                                                        data-id="{{ $msg->id }}"
                                                        data-name="{{ $msg->name }}"
                                                        data-phone="{{ $msg->phone }}"
                                                        data-email="{{ $msg->email ?? '' }}"
                                                        data-subject="{{ $msg->subject ?? 'N/A' }}"
                                                        data-message="{{ $msg->message }}"
                                                        data-status="{{ $msg->status }}"
                                                        data-date="{{ $msg->created_at->format('d M, Y - h:i A') }}">
                                                        <i class="ti-eye me-1"></i> দেখুন
                                                    </button>

                                                    <button type="button" class="btn btn-warning btn-xs me-1 edit-msg-btn"
                                                        data-id="{{ $msg->id }}"
                                                        data-name="{{ $msg->name }}"
                                                        data-phone="{{ $msg->phone }}"
                                                        data-email="{{ $msg->email ?? '' }}"
                                                        data-subject="{{ $msg->subject ?? '' }}"
                                                        data-message="{{ $msg->message }}"
                                                        data-status="{{ $msg->status }}">
                                                        <i class="ti-pencil me-1"></i> এডিট
                                                    </button>

                                                    <a href="{{ route('admin.contact_messages.destroy', $msg->id) }}" 
                                                        onclick="return confirm('আপনি কি নিশ্চিত যে এই বার্তাটি মুছে ফেলতে চান?');" 
                                                        class="btn btn-danger btn-xs">
                                                        <i class="ti-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5 text-muted">
                                                    <i class="ti-email fs-1 d-block mb-2 text-secondary"></i>
                                                    কোনো কন্টাক্ট বার্তা পাওয়া যায়নি।
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination Footer -->
                        @if($messages->hasPages())
                            <div class="card-footer border-top bg-white d-flex justify-content-end">
                                {{ $messages->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- View Message Modal -->
<div class="modal fade" id="viewMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="ti-email me-2"></i>কন্টাক্ট বার্তার বিস্তারিত</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="text-muted fs-7">প্রেরকের নাম:</label>
                        <h5 class="fw-bold mb-0 text-dark" id="modalViewName"></h5>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted fs-7">তারিখ ও সময়:</label>
                        <div class="fw-semibold text-secondary" id="modalViewDate"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted fs-7">ফোন নম্বর:</label>
                        <div>
                            <a href="#" id="modalViewPhoneLink" class="btn btn-outline-success btn-xs fw-bold"><i class="fa fa-phone me-1"></i> <span id="modalViewPhone"></span></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted fs-7">ইমেইল:</label>
                        <div class="fw-semibold text-dark" id="modalViewEmail"></div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted fs-7">বিষয় (Subject):</label>
                        <div class="fw-bold text-dark border-bottom pb-2" id="modalViewSubject"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted fs-7">বিস্তারিত বার্তা:</label>
                    <div class="p-3 bg-light rounded border text-dark" style="font-size: 15px; line-height: 1.6; white-space: pre-wrap;" id="modalViewMessage"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Message Modal -->
<div class="modal fade" id="editMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="editMessageForm" action="" method="POST">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="ti-pencil me-2"></i>বার্তা তথ্য এডিট করুন</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">নাম</label>
                            <input type="text" name="name" id="modalEditName" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">ফোন নম্বর</label>
                            <input type="text" name="phone" id="modalEditPhone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">ইমেইল</label>
                            <input type="email" name="email" id="modalEditEmail" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">স্ট্যাটাস</label>
                            <select name="status" id="modalEditStatus" class="form-select">
                                <option value="unread">অপঠিত (Unread)</option>
                                <option value="read">পঠিত (Read)</option>
                                <option value="replied">উত্তর প্রদানকৃত (Replied)</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">বিষয়</label>
                            <input type="text" name="subject" id="modalEditSubject" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">বিস্তারিত বার্তা</label>
                            <textarea name="message" id="modalEditMessage" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-warning"><i class="ti-save me-1"></i> আপডেট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Modal Trigger
    const viewButtons = document.querySelectorAll('.view-msg-btn');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const phone = this.getAttribute('data-phone');
            const email = this.getAttribute('data-email') || 'N/A';
            const subject = this.getAttribute('data-subject');
            const message = this.getAttribute('data-message');
            const date = this.getAttribute('data-date');

            document.getElementById('modalViewName').innerText = name;
            document.getElementById('modalViewPhone').innerText = phone;
            document.getElementById('modalViewPhoneLink').setAttribute('href', 'tel:' + phone);
            document.getElementById('modalViewEmail').innerText = email;
            document.getElementById('modalViewSubject').innerText = subject;
            document.getElementById('modalViewMessage').innerText = message;
            document.getElementById('modalViewDate').innerText = date;

            // Trigger backend status update to read
            fetch('/admin/contact-messages/show/' + id)
                .catch(err => console.error(err));

            const modal = new bootstrap.Modal(document.getElementById('viewMessageModal'));
            modal.show();
        });
    });

    // Edit Modal Trigger
    const editButtons = document.querySelectorAll('.edit-msg-btn');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const phone = this.getAttribute('data-phone');
            const email = this.getAttribute('data-email');
            const subject = this.getAttribute('data-subject');
            const message = this.getAttribute('data-message');
            const status = this.getAttribute('data-status');

            document.getElementById('editMessageForm').setAttribute('action', '/admin/contact-messages/update/' + id);
            document.getElementById('modalEditName').value = name;
            document.getElementById('modalEditPhone').value = phone;
            document.getElementById('modalEditEmail').value = email;
            document.getElementById('modalEditSubject').value = subject;
            document.getElementById('modalEditMessage').value = message;
            document.getElementById('modalEditStatus').value = status;

            const modal = new bootstrap.Modal(document.getElementById('editMessageModal'));
            modal.show();
        });
    });
});
</script>
@endsection
