<!-- Krishi Poribar Live Chat & AI Assistant Widget -->
<div id="krishi-chat-widget-wrapper" style="position: fixed; bottom: 25px; right: 25px; z-index: 99999; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <!-- Floating Trigger Button -->
    <button id="krishi-chat-trigger" onclick="toggleKrishiChat()" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: #ffffff; border: none; box-shadow: 0 10px 25px rgba(234, 88, 12, 0.4); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); outline: none;">
        <i id="krishi-chat-icon" class="fa fa-comments" style="font-size: 26px;"></i>
        <i id="krishi-close-icon" class="fa fa-chevron-down" style="font-size: 24px; display: none;"></i>
    </button>

    <!-- Chat Modal Window -->
    <div id="krishi-chat-modal" style="display: none; position: absolute; bottom: 75px; right: 0; width: 370px; max-width: calc(100vw - 30px); height: 530px; max-height: calc(100vh - 110px); background: #ffffff; border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); overflow: hidden; flex-direction: column; border: 1px solid #fed7aa; transition: all 0.3s ease;">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); padding: 16px 20px; color: #ffffff; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255, 255, 255, 0.15);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button onclick="toggleKrishiChat()" style="background: transparent; border: none; color: #ffffff; font-size: 18px; cursor: pointer; padding: 0;"><i class="fa fa-chevron-left"></i></button>
                <div>
                    <h5 style="margin: 0; font-size: 17px; font-weight: 700; color: #ffffff; letter-spacing: 0.3px;">Customer Support</h5>
                    <small style="font-size: 11px; opacity: 0.9; color: #ffedd5;"><i class="fa fa-circle" style="font-size: 8px; color: #22c55e; margin-right: 4px;"></i> Online | AI Assistant Active</small>
                </div>
            </div>
            <button onclick="toggleKrishiChat()" style="background: rgba(255,255,255,0.2); border: none; color: #ffffff; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;"><i class="fa fa-times" style="font-size: 14px;"></i></button>
        </div>

        <!-- Chat Body / Messages -->
        <div id="krishi-chat-body" style="flex: 1; padding: 16px; overflow-y: auto; background: #fdfbf7; display: flex; flex-direction: column; gap: 12px;">
            
            <!-- Default Welcome Bot Message -->
            <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 8px;">
                <div style="width: 38px; height: 38px; border-radius: 50%; background: #ffedd5; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #fdba74;">
                    <img src="https://cdn-icons-png.flaticon.com/512/4140/4140048.png" alt="Support" style="width: 28px; height: 28px;">
                </div>
                <div>
                    <small style="font-size: 11px; color: #9a3412; font-weight: 600; display: block; margin-bottom: 4px;">Customer Support</small>
                    <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: #ffffff; padding: 12px 16px; border-radius: 4px 18px 18px 18px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 12px rgba(249, 115, 22, 0.25);">
                        👋 Hi! How can we help?
                    </div>
                </div>
            </div>

            <!-- Quick Action Suggestion Chips -->
            <div id="krishi-quick-chips" style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; margin-top: 5px; margin-bottom: 10px;">
                <button onclick="sendQuickQuestion('স্টক মার্কেট রেট কত?')" style="background: #ffffff; color: #ea580c; border: 1.5px solid #ea580c; padding: 8px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.05); transition: all 0.2s ease;">I have a question: স্টক মার্কেট রেট কত?</button>
                <button onclick="sendQuickQuestion('মাসিক বাজার প্যাকেজ বিবরণী')" style="background: #ffffff; color: #ea580c; border: 1.5px solid #ea580c; padding: 8px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.05); transition: all 0.2s ease;">Tell me more: মাসিক বাজার প্যাকেজ</button>
            </div>

            <!-- Dynamic Messages List Container -->
            <div id="krishi-chat-messages-container" style="display: flex; flex-direction: column; gap: 10px;"></div>

        </div>

        <!-- Input Footer Area -->
        <div style="padding: 12px 16px; background: #ffffff; border-top: 1px solid #f1f5f9;">
            <form id="krishiChatForm" onsubmit="submitKrishiChatMessage(event)" style="margin: 0;">
                <div style="display: flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 25px; padding: 4px 14px; gap: 8px;">
                    <input type="text" id="krishiChatMessageInput" placeholder="Type here and press enter.." style="flex: 1; border: none; background: transparent; padding: 8px 0; font-size: 13px; outline: none; color: #1e293b;" required autocomplete="off">
                    <button type="button" onclick="sendQuickQuestion('👍')" style="background: transparent; border: none; color: #64748b; font-size: 16px; cursor: pointer; padding: 0;"><i class="fa fa-thumbs-up"></i></button>
                    <button type="submit" style="background: #ea580c; border: none; color: #ffffff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s ease;"><i class="fa fa-paper-plane" style="font-size: 12px;"></i></button>
                </div>
            </form>
            <div style="text-align: center; margin-top: 8px;">
                <span style="font-size: 10px; color: #94a3b8; background: #f1f5f9; padding: 3px 10px; border-radius: 12px; font-weight: 500;">⚡ Powered by कृषि পরিবার Support</span>
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

function toggleKrishiChat() {
    const modal = document.getElementById('krishi-chat-modal');
    const chatIcon = document.getElementById('krishi-chat-icon');
    const closeIcon = document.getElementById('krishi-close-icon');

    if (modal.style.display === 'none' || modal.style.display === '') {
        modal.style.display = 'flex';
        chatIcon.style.display = 'none';
        closeIcon.style.display = 'block';
        fetchKrishiChatMessages();
    } else {
        modal.style.display = 'none';
        chatIcon.style.display = 'block';
        closeIcon.style.display = 'none';
    }
}

function fetchKrishiChatMessages() {
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
        if(data.status === 'success') {
            renderChatMessages(data.messages);
        }
    });
}

function renderChatMessages(messages) {
    const container = document.getElementById('krishi-chat-messages-container');
    container.innerHTML = '';

    messages.forEach(msg => {
        const isUser = msg.sender_type === 'user';
        const msgDiv = document.createElement('div');
        msgDiv.style.display = 'flex';
        msgDiv.style.justifyContent = isUser ? 'flex-end' : 'flex-start';
        msgDiv.style.marginBottom = '6px';

        const bubble = document.createElement('div');
        bubble.style.maxWidth = '80%';
        bubble.style.padding = '10px 14px';
        bubble.style.borderRadius = isUser ? '16px 16px 4px 16px' : '16px 16px 16px 4px';
        bubble.style.fontSize = '13px';
        bubble.style.lineHeight = '1.4';
        bubble.style.wordBreak = 'break-word';

        if(isUser) {
            bubble.style.background = '#ea580c';
            bubble.style.color = '#ffffff';
            bubble.style.boxShadow = '0 2px 8px rgba(234, 88, 12, 0.2)';
        } else {
            bubble.style.background = '#ffffff';
            bubble.style.color = '#1e293b';
            bubble.style.border = '1px solid #e2e8f0';
            bubble.style.boxShadow = '0 2px 6px rgba(0,0,0,0.03)';
        }

        bubble.innerHTML = msg.message.replace(/\n/g, '<br>');
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
    const message = input.value.trim();
    if(!message) return;

    input.value = '';

    // Append optimistic user message
    const container = document.getElementById('krishi-chat-messages-container');
    const userDiv = document.createElement('div');
    userDiv.style.display = 'flex';
    userDiv.style.justifyContent = 'flex-end';
    userDiv.style.marginBottom = '6px';
    userDiv.innerHTML = `<div style="max-width: 80%; padding: 10px 14px; border-radius: 16px 16px 4px 16px; font-size: 13px; background: #ea580c; color: #ffffff;">${message}</div>`;
    container.appendChild(userDiv);

    const body = document.getElementById('krishi-chat-body');
    body.scrollTop = body.scrollHeight;

    fetch("{{ route('chat.send') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            session_id: chatSessionId,
            message: message
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            renderChatMessages(data.messages);
        }
    });
}
</script>
