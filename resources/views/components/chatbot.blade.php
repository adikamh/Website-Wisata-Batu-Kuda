@vite(['resources/css/chatbot.css', 'resources/js/chatbot.js'])
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