

<style>
    /* ── Chatbot Widget ─────────────────────────────────────── */
    #chatbot-toggle {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 1000;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #1a3d2b;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(26, 61, 43, 0.45);
        transition: transform 0.2s ease, background 0.2s ease;
    }

    #chatbot-toggle:hover {
        background: #2d6a4f;
        transform: scale(1.07);
    }

    #chatbot-toggle svg {
        width: 24px;
        height: 24px;
        color: #b7e4c7;
        transition: opacity 0.2s;
    }

    #chatbot-toggle .icon-close {
        display: none;
    }

    #chatbot-toggle.open .icon-chat {
        display: none;
    }

    #chatbot-toggle.open .icon-close {
        display: block;
    }

    /* Unread badge */
    #chat-badge {
        position: absolute;
        top: -3px;
        right: -3px;
        background: #52b788;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
        opacity: 0;
        transition: opacity 0.3s;
    }

    #chat-badge.show {
        opacity: 1;
    }

    /* ── Chatbot Panel ──────────────────────────────────────── */
    #chatbot-panel {
        position: fixed;
        bottom: 6rem;
        right: 2rem;
        z-index: 999;
        width: 360px;
        max-height: 540px;
        background: #0f2218;
        border: 1px solid rgba(82, 183, 136, 0.2);
        border-radius: 18px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform: scale(0.9) translateY(16px);
        transform-origin: bottom right;
        opacity: 0;
        pointer-events: none;
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5);
    }

    #chatbot-panel.open {
        transform: scale(1) translateY(0);
        opacity: 1;
        pointer-events: all;
    }

    /* Header */
    .chat-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        background: #1a3d2b;
        border-bottom: 1px solid rgba(82, 183, 136, 0.15);
        flex-shrink: 0;
    }

    .chat-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(82, 183, 136, 0.15);
        border: 1px solid rgba(82, 183, 136, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .chat-avatar svg {
        width: 18px;
        height: 18px;
        color: #74c69d;
    }

    .chat-avatar .avatar-image {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    display: block;
}

    .chat-header-info {
        flex: 1;
    }

    .chat-header-info strong {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #d8f3dc;
        letter-spacing: 0.01em;
    }

    .chat-header-info span {
        font-size: 11px;
        color: #74c69d;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .chat-header-info span::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #52b788;
        flex-shrink: 0;
        animation: pulse-dot 2s infinite;
    }

    @keyframes pulse-dot {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }
    }

    .chat-close-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: rgba(116, 198, 157, 0.6);
        padding: 4px;
        display: flex;
        border-radius: 6px;
        transition: color 0.15s, background 0.15s;
    }

    .chat-close-btn:hover {
        color: #74c69d;
        background: rgba(82, 183, 136, 0.1);
    }

    /* Messages */
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 14px 14px 8px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        scroll-behavior: smooth;
    }

    .chat-messages::-webkit-scrollbar {
        width: 4px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: rgba(82, 183, 136, 0.2);
        border-radius: 2px;
    }

    /* Bubbles */
    .msg {
        max-width: 82%;
        line-height: 1.55;
        font-size: 13.5px;
        word-break: break-word;
        animation: msg-in 0.2s ease;
    }

    @keyframes msg-in {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .msg.user {
        align-self: flex-end;
    }

    .msg.bot {
        align-self: flex-start;
    }

    .msg-bubble {
        padding: 9px 13px;
        border-radius: 14px;
    }

    .msg.user .msg-bubble {
        background: #2d6a4f;
        color: #d8f3dc;
        border-bottom-right-radius: 4px;
    }

    .msg.bot .msg-bubble {
        background: rgba(255, 255, 255, 0.06);
        color: #cde8d6;
        border: 1px solid rgba(82, 183, 136, 0.12);
        border-bottom-left-radius: 4px;
    }

    .msg-time {
        font-size: 10px;
        color: rgba(116, 198, 157, 0.4);
        margin-top: 3px;
        padding: 0 2px;
    }

    .msg.user .msg-time {
        text-align: right;
    }

    /* Typing indicator */
    .typing-indicator {
        align-self: flex-start;
        display: none;
        gap: 4px;
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(82, 183, 136, 0.12);
        border-radius: 14px;
        border-bottom-left-radius: 4px;
        animation: msg-in 0.2s ease;
    }

    .typing-indicator.visible {
        display: flex;
    }

    .typing-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #52b788;
        animation: bounce 1.2s infinite;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes bounce {

        0%,
        80%,
        100% {
            transform: translateY(0);
            opacity: 0.4;
        }

        40% {
            transform: translateY(-5px);
            opacity: 1;
        }
    }

    /* Quick replies */
    .quick-replies {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding: 4px 14px 10px;
        flex-shrink: 0;
    }

    .quick-btn {
        font-size: 11.5px;
        padding: 5px 11px;
        border-radius: 20px;
        border: 1px solid rgba(82, 183, 136, 0.3);
        background: rgba(82, 183, 136, 0.07);
        color: #74c69d;
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s;
        white-space: nowrap;
    }

    .quick-btn:hover {
        background: rgba(82, 183, 136, 0.18);
        border-color: rgba(82, 183, 136, 0.5);
    }

    /* Input area */
    .chat-input-wrap {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        padding: 10px 12px 12px;
        border-top: 1px solid rgba(82, 183, 136, 0.1);
        background: #0f2218;
        flex-shrink: 0;
    }

    #chat-input {
        flex: 1;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(82, 183, 136, 0.2);
        border-radius: 12px;
        color: #d8f3dc;
        font-size: 13.5px;
        padding: 9px 13px;
        resize: none;
        outline: none;
        min-height: 40px;
        max-height: 96px;
        overflow-y: auto;
        font-family: inherit;
        line-height: 1.5;
        transition: border-color 0.15s;
    }

    #chat-input::placeholder {
        color: rgba(116, 198, 157, 0.4);
    }

    #chat-input:focus {
        border-color: rgba(82, 183, 136, 0.5);
    }

    #chat-send {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #2d6a4f;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background 0.15s, transform 0.15s;
    }

    #chat-send:hover {
        background: #40916c;
    }

    #chat-send:active {
        transform: scale(0.92);
    }

    #chat-send:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    #chat-send svg {
        width: 18px;
        height: 18px;
        color: #d8f3dc;
    }

    /* Mobile */
    @media (max-width: 480px) {
        #chatbot-panel {
            width: calc(100vw - 2rem);
            right: 1rem;
            bottom: 5.5rem;
            max-height: 480px;
        }

        #chatbot-toggle {
            right: 1rem;
            bottom: 1.5rem;
        }
    }
</style>

{{-- ── Toggle Button ───────────────────────────────────────── --}}
<button id="chatbot-toggle" aria-label="Buka asisten wisata" title="Tanya asisten Batu Kuda">
    <div id="chat-badge">1</div>
    <svg class="icon-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
    </svg>
    <svg class="icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
        stroke-linecap="round">
        <line x1="18" y1="6" x2="6" y2="18" />
        <line x1="6" y1="6" x2="18" y2="18" />
    </svg>
</button>

{{-- ── Chat Panel ───────────────────────────────────────────── --}}
<div id="chatbot-panel" role="dialog" aria-label="Asisten Batu Kuda" aria-modal="true">

    {{-- Header --}}
    <div class="chat-header">
        <div class="chat-avatar">
            <img src="{{ Vite::asset('resources/images/icons/Baku.png') }}" alt="Baku Avatar" class="avatar-image"
                onerror="this.style.display='none'; this.nextSibling.style.display='flex';">
            <svg viewBox="0 0 32 32" fill="none" title="Monkey icons" style="display: none;">
                <path d="M4 26L12 12L18 20L22 14L28 26H4Z" fill="currentColor" opacity="0.9" />
                <path d="M18 8C18 8 24 10 22 18C20 15 17 14 16 11C15 14 13 16 11 18C9 10 16 6 18 8Z" fill="currentColor"
                    opacity="0.5" />
            </svg>
        </div>
        <div class="chat-header-info">
            <strong>Halo saya Baku</strong>
            <span>Online · Siap membantu</span>
        </div>
        <button class="chat-close-btn" id="chat-close-btn" aria-label="Tutup chat">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>
    </div>

    {{-- Messages --}}
    <div class="chat-messages" id="chat-messages">
        <div class="msg bot">
            <div class="msg-bubble">
                Halo! 👋 Saya asisten wisata Batu Kuda. Ada yang bisa saya bantu? Tanyakan soal lokasi, tiket, jalur
                trekking, atau tips berkunjung!
            </div>
            <div class="msg-time" id="first-msg-time"></div>
        </div>
        <div class="typing-indicator" id="typing-indicator">
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
        </div>
    </div>

    {{-- Quick replies --}}
    <div class="quick-replies" id="quick-replies">
        <button class="quick-btn" data-msg="Berapa harga tiket masuk?">Harga tiket</button>
        <button class="quick-btn" data-msg="Jam buka wisata Batu Kuda?">Jam buka</button>
        <button class="quick-btn" data-msg="Bagaimana cara ke Batu Kuda dari Bandung?">Rute</button>
        <button class="quick-btn" data-msg="Apa saja fasilitas di Batu Kuda?">Fasilitas</button>
        <button class="quick-btn" data-msg="Apakah ada jalur trekking yang direkomendasikan?">Jalur trekking</button>
        <button class="quick-btn" data-msg="apa saja paket kunjungan di wisata batu kuda">jenis paket</button>
    </div>

    {{-- Input --}}
    <div class="chat-input-wrap">
        <textarea id="chat-input" placeholder="Tanyakan sesuatu…" rows="1" aria-label="Pesan Anda"
            maxlength="500"></textarea>
        <button id="chat-send" aria-label="Kirim pesan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13" />
                <polygon points="22 2 15 22 11 13 2 9 22 2" />
            </svg>
        </button>
    </div>
</div>

<script>
    (function () {
        const toggle = document.getElementById('chatbot-toggle');
        const panel = document.getElementById('chatbot-panel');
        const closeBtn = document.getElementById('chat-close-btn');
        const messages = document.getElementById('chat-messages');
        const input = document.getElementById('chat-input');
        const sendBtn = document.getElementById('chat-send');
        const typing = document.getElementById('typing-indicator');
        const badge = document.getElementById('chat-badge');
        const quickReplies = document.getElementById('quick-replies');

        // Set greeting timestamp
        document.getElementById('first-msg-time').textContent = now();

        function now() {
            return new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }

        function openPanel() {
            panel.classList.add('open');
            toggle.classList.add('open');
            toggle.setAttribute('aria-expanded', 'true');
            badge.classList.remove('show');
            input.focus();
        }

        function closePanel() {
            panel.classList.remove('open');
            toggle.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        }

        toggle.addEventListener('click', () => {
            panel.classList.contains('open') ? closePanel() : openPanel();
        });
        closeBtn.addEventListener('click', closePanel);

        // Auto-expand textarea
        input.addEventListener('input', () => {
            input.style.height = 'auto';
            input.style.height = Math.min(input.scrollHeight, 96) + 'px';
        });

        // Quick reply buttons
        quickReplies.addEventListener('click', e => {
            const btn = e.target.closest('.quick-btn');
            if (btn) sendMessage(btn.dataset.msg);
        });

        // Send on Enter (Shift+Enter = newline)
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        sendBtn.addEventListener('click', () => sendMessage());

        function addMessage(text, role) {
            // Remove typing indicator, add message, re-append typing
            const typingEl = document.getElementById('typing-indicator');
            messages.removeChild(typingEl);

            const wrap = document.createElement('div');
            wrap.className = `msg ${role}`;

            const bubble = document.createElement('div');
            bubble.className = 'msg-bubble';
            bubble.textContent = text;

            const time = document.createElement('div');
            time.className = 'msg-time';
            time.textContent = now();

            wrap.appendChild(bubble);
            wrap.appendChild(time);
            messages.appendChild(wrap);
            messages.appendChild(typingEl);
            messages.scrollTop = messages.scrollHeight;
        }

        function setLoading(loading) {
            sendBtn.disabled = loading;
            input.disabled = loading;
            typing.classList.toggle('visible', loading);
            messages.scrollTop = messages.scrollHeight;
        }

        async function sendMessage(overrideText) {
            const text = (overrideText || input.value).trim();
            if (!text) return;

            addMessage(text, 'user');
            input.value = '';
            input.style.height = 'auto';

            // Hide quick replies after first interaction
            quickReplies.style.display = 'none';

            setLoading(true);

            try {
                const res = await fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ message: text })
                });

                if (!res.ok) throw new Error('Network error');

                const data = await res.json();
                setLoading(false);
                addMessage(data.reply || 'Maaf, tidak ada respons.', 'bot');
            } catch (err) {
                setLoading(false);
                addMessage('Maaf, terjadi kesalahan. Silakan coba lagi sebentar.', 'bot');
            }
        }
        setTimeout(() => {
            if (!panel.classList.contains('open')) {
                badge.classList.add('show');
            }
        }, 3000);
    })();
</script>