@php
    $chatThemeColor = setting('header_bg_color') ?: (setting('header_color') ?: (setting('button_color') ?: '#007e8a'));
@endphp

<!-- Krishi Poribar Live Chat & AI Assistant Widget -->
<div id="krishi-chat-widget-wrapper" style="position: fixed; bottom: 25px; right: 25px; z-index: 99999; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <!-- Floating Trigger Button -->
    <button id="krishi-chat-trigger" onclick="toggleKrishiChat()" style="width: 60px; height: 60px; border-radius: 50%; background: {{ $chatThemeColor }}; color: #ffffff; border: none; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); outline: none;">
        <i id="krishi-chat-icon" class="fa fa-comments" style="font-size: 26px;"></i>
        <i id="krishi-close-icon" class="fa fa-chevron-down" style="font-size: 24px; display: none;"></i>
    </button>

    <!-- Chat Modal Window -->
    <div id="krishi-chat-modal" style="display: none; position: absolute; bottom: 75px; right: 0; width: 370px; max-width: calc(100vw - 30px); height: 530px; max-height: calc(100vh - 110px); background: #ffffff; border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); overflow: hidden; flex-direction: column; border: 1px solid #e2e8f0; transition: all 0.3s ease;">
        
        <!-- Header -->
        <div style="background: {{ $chatThemeColor }}; padding: 16px 20px; color: #ffffff; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255, 255, 255, 0.15);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button onclick="toggleKrishiChat()" style="background: transparent; border: none; color: #ffffff; font-size: 18px; cursor: pointer; padding: 0;"><i class="fa fa-chevron-left"></i></button>
                <div>
                    <h5 style="margin: 0; font-size: 17px; font-weight: 700; color: #ffffff; letter-spacing: 0.3px;">Customer Support</h5>
                    <small id="krishi-chat-substatus" style="font-size: 11px; opacity: 0.95; color: rgba(255,255,255,0.9);"><i class="fa fa-circle" style="font-size: 8px; color: #22c55e; margin-right: 4px;"></i> Online | Live Support</small>
                </div>
            </div>
            <button onclick="toggleKrishiChat()" style="background: rgba(255,255,255,0.2); border: none; color: #ffffff; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;"><i class="fa fa-times" style="font-size: 14px;"></i></button>
        </div>

        <!-- Chat Body / Messages -->
        <div id="krishi-chat-body" style="flex: 1; padding: 16px; overflow-y: auto; background: #fdfbf7; display: flex; flex-direction: column; gap: 12px;">
            <!-- Dynamic Messages List Container -->
            <div id="krishi-chat-messages-container" style="display: flex; flex-direction: column; gap: 10px;"></div>
        </div>

        <!-- Input Footer Area -->
        <div style="padding: 12px 16px; background: #ffffff; border-top: 1px solid #f1f5f9;">
            <form id="krishiChatForm" onsubmit="submitKrishiChatMessage(event)" style="margin: 0;">
                <div style="display: flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 25px; padding: 4px 14px; gap: 8px;">
                    <input type="text" id="krishiChatMessageInput" placeholder="Type here and press enter.." style="flex: 1; border: none; background: transparent; padding: 8px 0; font-size: 13px; outline: none; color: #1e293b;" required autocomplete="off">
                    <button type="button" onclick="sendQuickQuestion('👍')" style="background: transparent; border: none; color: #64748b; font-size: 16px; cursor: pointer; padding: 0;"><i class="fa fa-thumbs-up"></i></button>
                    <button type="submit" style="background: {{ $chatThemeColor }}; border: none; color: #ffffff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s ease;"><i class="fa fa-paper-plane" style="font-size: 12px;"></i></button>
                </div>
            </form>
            <div style="text-align: center; margin-top: 8px;">
                <span style="font-size: 10px; color: #94a3b8; background: #f1f5f9; padding: 3px 10px; border-radius: 12px; font-weight: 500;">⚡ Powered by eKrishiSheba Support</span>
            </div>
        </div>

    </div>
</div>

<script>
let chatSessionId = localStorage.getItem('krishi_chat_session_id');
if(!chatSessionId) {
    chatSessionId = 'sess_' + Math.random().toString(36).substring(2, 11) + Date.now();
    localStorage.setItem('krishi_chat_session_id', chatSessionId);
}

let krishiChatPollingTimer = null;
let lastKnownChatCount = -1;

function toggleKrishiChat() {
    const modal = document.getElementById('krishi-chat-modal');
    const chatIcon = document.getElementById('krishi-chat-icon');
    const closeIcon = document.getElementById('krishi-close-icon');

    if (modal.style.display === 'none' || modal.style.display === '') {
        modal.style.display = 'flex';
        chatIcon.style.display = 'none';
        closeIcon.style.display = 'block';
        fetchKrishiChatMessages(true);
        // Auto-poll every 3 seconds for live admin replies
        if (krishiChatPollingTimer) clearInterval(krishiChatPollingTimer);
        krishiChatPollingTimer = setInterval(() => fetchKrishiChatMessages(false), 3000);
    } else {
        modal.style.display = 'none';
        chatIcon.style.display = 'block';
        closeIcon.style.display = 'none';
        // Clear interval when closed
        if (krishiChatPollingTimer) {
            clearInterval(krishiChatPollingTimer);
            krishiChatPollingTimer = null;
        }
    }
}

function fetchKrishiChatMessages(forceScroll = false) {
    fetch("{{ route('chat.fetch') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ session_id: chatSessionId })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success' && Array.isArray(data.messages)) {
            if (forceScroll || data.messages.length !== lastKnownChatCount) {
                lastKnownChatCount = data.messages.length;
                renderChatMessages(data.messages);
            }
        }
    })
    .catch(err => {
        // Silently catch background network glitches
    });
}

function formatChatText(str) {
    if(!str) return '';
    let formatted = str.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    return formatted.replace(/\n/g, '<br>');
}

function renderChatMessages(messages) {
    const container = document.getElementById('krishi-chat-messages-container');
    container.innerHTML = '';

    const hasAdmin = messages.some(m => m.sender_type === 'admin');
    const subStatus = document.getElementById('krishi-chat-substatus');

    if (hasAdmin) {
        if (subStatus) subStatus.innerHTML = '<i class="fa fa-circle" style="font-size: 8px; color: #22c55e; margin-right: 4px;"></i> Online | সাপোর্ট প্রতিনিধি যুক্ত আছেন';
    } else {
        if (subStatus) subStatus.innerHTML = '<i class="fa fa-circle" style="font-size: 8px; color: #22c55e; margin-right: 4px;"></i> Online | লাইভ সাপোর্ট সক্রিয়';
    }

    if (messages.length === 0) {
        container.innerHTML = '<div style="text-align: center; color: #94a3b8; font-size: 13px; padding: 30px 10px;">আপনাকে কীভাবে সাহায্য করতে পারি? আপনার বার্তা লিখুন।</div>';
        return;
    }

    messages.forEach(msg => {
        const isUser = msg.sender_type === 'user';
        const isAdmin = msg.sender_type === 'admin';
        const isBot = msg.sender_type === 'bot';

        const msgDiv = document.createElement('div');
        msgDiv.style.display = 'flex';
        msgDiv.style.justifyContent = isUser ? 'flex-end' : 'flex-start';
        msgDiv.style.marginBottom = '8px';

        const bubble = document.createElement('div');
        bubble.style.maxWidth = '85%';
        bubble.style.padding = '10px 14px';
        bubble.style.borderRadius = isUser ? '16px 16px 4px 16px' : '16px 16px 16px 4px';
        bubble.style.fontSize = '13px';
        bubble.style.lineHeight = '1.45';
        bubble.style.wordBreak = 'break-word';

        let senderBadge = '';
        if (isAdmin) {
            senderBadge = `<div style="font-size: 11px; font-weight: 700; color: #2563eb; margin-bottom: 4px; display: flex; align-items: center; gap: 4px;"><i class="fa fa-user-circle"></i> ${msg.sender_name || 'এডমিন প্রতিনিধি (Admin Support)'}</div>`;
        } else if (isBot) {
            senderBadge = `<div style="font-size: 11px; font-weight: 600; color: {{ $chatThemeColor }}; margin-bottom: 4px; display: flex; align-items: center; gap: 4px;"><i class="fa fa-robot"></i> সাপোর্ট সহকারী</div>`;
        }

        let timeStr = '';
        if (msg.created_at) {
            try {
                const d = new Date(msg.created_at);
                timeStr = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            } catch(e) {}
        }

        if(isUser) {
            bubble.style.background = '{{ $chatThemeColor }}';
            bubble.style.color = '#ffffff';
            bubble.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.15)';
        } else if (isAdmin) {
            bubble.style.background = '#eff6ff';
            bubble.style.color = '#1e3a8a';
            bubble.style.border = '1px solid #bfdbfe';
            bubble.style.boxShadow = '0 2px 6px rgba(37, 99, 235, 0.08)';
        } else {
            bubble.style.background = '#ffffff';
            bubble.style.color = '#1e293b';
            bubble.style.border = '1px solid #e2e8f0';
            bubble.style.boxShadow = '0 2px 6px rgba(0,0,0,0.03)';
        }

        bubble.innerHTML = `${senderBadge}<div>${formatChatText(msg.message)}</div>${timeStr ? `<div style="font-size: 10px; opacity: 0.75; text-align: right; margin-top: 4px;">${timeStr}</div>` : ''}`;
        msgDiv.appendChild(bubble);
        container.appendChild(msgDiv);
    });

    const body = document.getElementById('krishi-chat-body');
    body.scrollTop = body.scrollHeight;
}

function sendQuickQuestion(text) {
    document.getElementById('krishiChatMessageInput').value = text;
    submitKrishiChatMessage(new Event('submit'));
}

function submitKrishiChatMessage(e) {
    e.preventDefault();
    const input = document.getElementById('krishiChatMessageInput');
    const msg = input.value.trim();
    if(!msg) return;

    input.value = '';

    fetch("{{ route('chat.send') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            session_id: chatSessionId,
            message: msg
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            fetchKrishiChatMessages(true);
        }
    })
    .catch(err => {
        console.error('Chat error:', err);
    });
}
</script>
