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