@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title fw-bold">
                        @if($selectedRole == 'user')
                            <i class="fa fa-user text-primary me-2"></i> ইউজার চ্যাট হেল্পডেস্ক (User Chat)
                        @elseif($selectedRole == 'employee')
                            <i class="fa fa-id-badge text-info me-2"></i> এমপ্লয়ী চ্যাট হেল্পডেস্ক (Employee Chat)
                        @elseif($selectedRole == 'supplier')
                            <i class="fa fa-truck text-success me-2"></i> সাপ্লায়ার চ্যাট হেল্পডেস্ক (Supplier Chat)
                        @elseif($selectedRole == 'agent')
                            <i class="fa fa-handshake-o text-warning me-2"></i> এজেন্ট চ্যাট হেল্পডেস্ক (Agent Chat)
                        @else
                            <i class="fa fa-comments text-primary me-2"></i> লাইভ চ্যাট সাপোর্ট হাব (All Live Chats)
                        @endif
                    </h4>
                    <p class="text-muted mb-0">ইউজার, এমপ্লয়ী, সাপ্লায়ার ও এজেন্টদের রিয়েল-টাইম লাইভ চ্যাট সাপোর্ট</p>
                </div>

                <!-- Role Filter Buttons -->
                <div class="btn-group flex-wrap shadow-sm" role="group">
                    <a href="{{ route('admin.chat.index', ['role' => 'user']) }}" class="btn {{ $selectedRole == 'user' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fa fa-user me-1"></i> ইউজার চ্যাট
                        @if(isset($counts['user']) && $counts['user'] > 0)
                            <span class="badge bg-danger ms-1">{{ $counts['user'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.chat.index', ['role' => 'employee']) }}" class="btn {{ $selectedRole == 'employee' ? 'btn-info text-white' : 'btn-outline-info' }}">
                        <i class="fa fa-id-badge me-1"></i> এমপ্লয়ী চ্যাট
                        @if(isset($counts['employee']) && $counts['employee'] > 0)
                            <span class="badge bg-danger ms-1">{{ $counts['employee'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.chat.index', ['role' => 'supplier']) }}" class="btn {{ $selectedRole == 'supplier' ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="fa fa-truck me-1"></i> সাপ্লায়ার চ্যাট
                        @if(isset($counts['supplier']) && $counts['supplier'] > 0)
                            <span class="badge bg-danger ms-1">{{ $counts['supplier'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.chat.index', ['role' => 'agent']) }}" class="btn {{ $selectedRole == 'agent' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                        <i class="fa fa-handshake-o me-1"></i> এজেন্ট চ্যাট
                        @if(isset($counts['agent']) && $counts['agent'] > 0)
                            <span class="badge bg-danger ms-1">{{ $counts['agent'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.chat.index') }}" class="btn {{ empty($selectedRole) ? 'btn-dark' : 'btn-outline-dark' }}">
                        <i class="fa fa-list me-1"></i> সকল চ্যাট
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- Session List -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="box shadow-sm rounded-3">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-comments me-2 text-primary"></i> মেসেজ তালিকা ({{ count($sessions) }})</h4>
                        </div>
                        <div class="box-body p-0" style="max-height: 600px; overflow-y: auto;">
                            <div class="list-group list-group-flush" id="chatSessionList">
                                @forelse($sessions as $sess)
                                <a href="javascript:void(0)" onclick="loadAdminSession('{{ $sess->session_id }}', this)" class="list-group-item list-group-item-action p-3 session-item border-bottom" data-session-id="{{ $sess->session_id }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-1">
                                            <i class="fa fa-user-circle text-secondary me-1"></i>
                                            {{ $sess->user_info ? $sess->user_info->sender_name : 'Guest Visitor (#' . substr($sess->session_id, 0, 6) . ')' }}
                                        </h6>
                                        <small class="text-muted" style="font-size: 11px;">
                                            {{ $sess->last_message ? $sess->last_message->created_at->diffForHumans() : '' }}
                                        </small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <p class="mb-0 text-truncate small text-secondary" style="max-width: 70%;">
                                            {{ $sess->last_message ? $sess->last_message->message : 'No messages' }}
                                        </p>
                                        <div>
                                            @if(($sess->detected_role ?? 'user') == 'employee')
                                                <span class="badge bg-info text-white" style="font-size: 10px;">এমপ্লয়ী</span>
                                            @elseif(($sess->detected_role ?? 'user') == 'supplier')
                                                <span class="badge bg-success" style="font-size: 10px;">সাপ্লায়ার</span>
                                            @elseif(($sess->detected_role ?? 'user') == 'agent')
                                                <span class="badge bg-warning text-dark" style="font-size: 10px;">এজেন্ট</span>
                                            @else
                                                <span class="badge bg-primary" style="font-size: 10px;">ইউজার</span>
                                            @endif

                                            @if($sess->unread_count > 0)
                                                <span class="badge bg-danger rounded-pill ms-1" style="font-size: 10px;">{{ $sess->unread_count }} new</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                @empty
                                <div class="p-4 text-center text-muted">
                                    <i class="fa fa-comment-slash fs-30 mb-2 text-secondary"></i>
                                    <p class="mb-0 fs-14">কোনো চ্যাট মেসেজ পাওয়া যায়নি।</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Chat Box -->
                <div class="col-xl-8 col-lg-7">
                    <div class="box shadow-sm" id="chatBoxContainer">
                        <div class="box-header with-border bg-primary text-white d-flex align-items-center justify-content-between">
                            <h4 class="box-title text-white mb-0" id="activeChatTitle"><i class="fa fa-comment-dots me-2"></i> কোনো মেসেজ সিলেক্ট করুন</h4>
                            <span class="badge bg-light text-primary" id="activeChatStatus">ইনঅ্যাক্টিভ</span>
                        </div>
                        
                        <div class="box-body" id="chatMessagesArea" style="height: 440px; overflow-y: auto; background: #f8fafc; padding: 20px;">
                            <div class="text-center py-5 text-muted" id="noSessionSelected">
                                <i class="fa fa-comments text-light-emphasis" style="font-size: 64px;"></i>
                                <h5 class="mt-3 text-secondary">বামপাশের তালিকা থেকে মেসেজ সিলেক্ট করে লাইভ চ্যাট শুরু করুন</h5>
                            </div>
                        </div>

                        <div class="box-footer p-3 bg-white border-top">
                            <form id="adminReplyForm" onsubmit="sendAdminReply(event)">
                                @csrf
                                <input type="hidden" id="activeSessionId" name="session_id" value="">
                                <div class="input-group">
                                    <input type="text" id="adminReplyInput" name="message" class="form-control form-control-lg" placeholder="এখানে উত্তর লিখুন..." disabled required autocomplete="off">
                                    <button type="submit" class="btn btn-primary px-4 fw-bold" id="adminSendBtn" disabled><i class="fa fa-paper-plane me-1"></i> পাঠান</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
let currentActiveSession = null;
let chatPollInterval = null;

function loadAdminSession(sessionId, element) {
    currentActiveSession = sessionId;
    document.getElementById('activeSessionId').value = sessionId;
    
    // Highlight list item
    document.querySelectorAll('.session-item').forEach(el => el.classList.remove('active', 'bg-light'));
    if(element) element.classList.add('bg-light');

    document.getElementById('adminReplyInput').disabled = false;
    document.getElementById('adminSendBtn').disabled = false;

    fetchSessionMessages(sessionId);

    if(chatPollInterval) clearInterval(chatPollInterval);
    chatPollInterval = setInterval(() => fetchSessionMessages(sessionId), 4000);
}

function fetchSessionMessages(sessionId) {
    fetch("{{ url('/admin/chat-support/session') }}/" + sessionId)
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                const area = document.getElementById('chatMessagesArea');
                area.innerHTML = '';

                document.getElementById('activeChatTitle').innerHTML = '<i class="fa fa-user-check me-2"></i> চ্যাট সেশন: ' + sessionId.substring(0, 10) + '...';
                document.getElementById('activeChatStatus').innerText = 'অনলাইন';

                data.messages.forEach(msg => {
                    const isAdmin = msg.sender_type === 'admin';
                    const isBot = msg.sender_type === 'bot';

                    let msgHtml = `
                        <div class="d-flex mb-3 ${isAdmin ? 'justify-content-end' : 'justify-content-start'}">
                            <div class="p-3 rounded-3 shadow-sm" style="max-width: 75%; ${isAdmin ? 'background: #2563eb; color: #ffffff;' : (isBot ? 'background: #e2e8f0; color: #1e293b;' : 'background: #ffffff; color: #0f172a; border: 1px solid #e2e8f0;')}">
                                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 11px; opacity: 0.85;">
                                    <strong>${msg.sender_name || (isAdmin ? 'Admin' : (isBot ? 'AI Bot' : 'User'))}</strong>
                                    <span class="ms-2">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                </div>
                                <div style="font-size: 14px; white-space: pre-wrap; word-break: break-word;">${msg.message}</div>
                            </div>
                        </div>
                    `;
                    area.innerHTML += msgHtml;
                });

                area.scrollTop = area.scrollHeight;
            }
        });
}

function sendAdminReply(e) {
    e.preventDefault();
    const input = document.getElementById('adminReplyInput');
    const msg = input.value.trim();
    if(!msg || !currentActiveSession) return;

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('session_id', currentActiveSession);
    formData.append('message', msg);

    input.value = '';

    fetch("{{ route('admin.chat.reply') }}", {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            fetchSessionMessages(currentActiveSession);
        }
    });
}
</script>
@endsection
