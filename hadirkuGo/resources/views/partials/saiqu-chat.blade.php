{{-- SaiQu AI Floating Chat Widget — HadirkuGO Theme --}}
<style>
    /* ========== FAB Button ========== */
    #saiqu-fab {
        position: fixed;
        bottom: 140px;
        right: 20px;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border: 3px solid rgba(255,255,255,0.25);
        color: white;
        font-size: 22px;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(30, 58, 138, 0.5);
        z-index: 1060;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        animation: saiqu-float 3s ease-in-out infinite;
    }
    #saiqu-fab:hover {
        transform: scale(1.15) rotate(10deg);
        box-shadow: 0 6px 30px rgba(30, 58, 138, 0.7);
    }
    #saiqu-fab.saiqu-hiding {
        opacity: 0;
        transform: scale(0.3) rotate(-90deg);
        pointer-events: none;
    }
    @keyframes saiqu-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }

    /* Notification dot */
    #saiqu-fab::after {
        content: '';
        position: absolute;
        top: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background: #22c55e;
        border-radius: 50%;
        border: 2px solid white;
        animation: saiqu-dot-pulse 2s infinite;
    }
    @keyframes saiqu-dot-pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.3); opacity: 0.7; }
    }

    /* ========== Chat Container ========== */
    #saiqu-chat {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 360px;
        max-height: 520px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 15px 50px rgba(30, 58, 138, 0.25), 0 0 0 1px rgba(30, 58, 138, 0.08);
        z-index: 1061;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
        /* Animation states */
        opacity: 0;
        transform: translateY(30px) scale(0.9);
        pointer-events: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    #saiqu-chat.active {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }

    /* ========== Header ========== */
    .saiqu-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
        color: white;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
    }
    /* Subtle pattern overlay on header */
    .saiqu-header::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23ffffff' opacity='0.06' d='M0 224h192V32H0v192zM64 96h64v64H64V96zm384-64v192H256V32h192zM448 96h-64v64h64V96z'/%3E%3C/svg%3E") repeat;
        background-size: 60px;
        pointer-events: none;
    }
    .saiqu-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }
</style>
<style>
    .saiqu-avatar-wrap {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        border: 2px solid rgba(255,255,255,0.3);
        animation: saiqu-avatar-glow 3s ease-in-out infinite;
    }
    @keyframes saiqu-avatar-glow {
        0%, 100% { box-shadow: 0 0 8px rgba(255,255,255,0.2); }
        50% { box-shadow: 0 0 16px rgba(255,255,255,0.4); }
    }
    .saiqu-header-title {
        font-weight: 700;
        font-size: 15px;
        letter-spacing: 0.3px;
    }
    .saiqu-header-status {
        font-size: 11px;
        opacity: 0.85;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .saiqu-header-status .saiqu-online-dot {
        width: 7px;
        height: 7px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
        animation: saiqu-dot-pulse 2s infinite;
    }
    .saiqu-header-actions {
        display: flex;
        gap: 4px;
        position: relative;
        z-index: 1;
    }
    .saiqu-header-actions button {
        background: rgba(255,255,255,0.12);
        border: none;
        color: white;
        font-size: 13px;
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s;
    }
    .saiqu-header-actions button:hover {
        background: rgba(255,255,255,0.25);
        transform: scale(1.1);
    }

    /* ========== Messages ========== */
    .saiqu-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: linear-gradient(180deg, #f0f4ff 0%, #f8fafc 100%);
        min-height: 220px;
        max-height: 340px;
    }
    .saiqu-msg {
        max-width: 82%;
        padding: 11px 16px;
        border-radius: 18px;
        font-size: 13px;
        line-height: 1.6;
        word-wrap: break-word;
        /* Slide-in animation */
        animation: saiqu-msg-in 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
    }
    @keyframes saiqu-msg-in {
        from { opacity: 0; transform: translateY(12px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .saiqu-msg.user {
        align-self: flex-end;
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: white;
        border-bottom-right-radius: 6px;
        box-shadow: 0 2px 8px rgba(30, 58, 138, 0.2);
    }
    .saiqu-msg.bot {
        align-self: flex-start;
        background: white;
        color: #1e293b;
        border-bottom-left-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid rgba(30, 58, 138, 0.06);
    }

    /* ========== Typing Indicator ========== */
    .saiqu-typing {
        align-self: flex-start;
        padding: 12px 18px;
        background: white;
        border-radius: 18px;
        border-bottom-left-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        display: none;
        gap: 4px;
        align-items: center;
        margin: 0 16px 8px;
        animation: saiqu-msg-in 0.3s ease both;
    }
    .saiqu-typing.active { display: flex; }
    .saiqu-typing span {
        width: 7px;
        height: 7px;
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        border-radius: 50%;
        animation: saiqu-bounce 1.4s infinite;
    }
    .saiqu-typing span:nth-child(2) { animation-delay: 0.15s; }
    .saiqu-typing span:nth-child(3) { animation-delay: 0.3s; }
    @keyframes saiqu-bounce {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-10px); }
    }

    /* ========== Input Area ========== */
    .saiqu-input-area {
        padding: 12px 14px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 10px;
        background: white;
        flex-shrink: 0;
        align-items: center;
    }
    .saiqu-input-area input {
        flex: 1;
        border: 2px solid #e2e8f0;
        border-radius: 24px;
        padding: 10px 16px;
        font-size: 13px;
        outline: none;
        font-family: 'Poppins', sans-serif;
        transition: border-color 0.3s, box-shadow 0.3s;
        background: #f8fafc;
    }
    .saiqu-input-area input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        background: white;
    }
    .saiqu-input-area input::placeholder {
        color: #94a3b8;
    }
    .saiqu-send-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 2px 10px rgba(30, 58, 138, 0.3);
    }
    .saiqu-send-btn:hover:not(:disabled) {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.4);
    }
    .saiqu-send-btn:active:not(:disabled) {
        transform: scale(0.95);
    }
    .saiqu-send-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none;
    }

    /* ========== Quick Actions ========== */
    .saiqu-quick-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        padding: 0 16px 10px;
        background: linear-gradient(180deg, transparent, #f8fafc);
    }
    .saiqu-quick-btn {
        padding: 5px 12px;
        border-radius: 16px;
        border: 1.5px solid #dbeafe;
        background: white;
        color: #1e3a8a;
        font-size: 11px;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.25s;
        white-space: nowrap;
    }
    .saiqu-quick-btn:hover {
        background: #1e3a8a;
        color: white;
        border-color: #1e3a8a;
        transform: translateY(-1px);
    }

    /* ========== Scrollbar ========== */
    .saiqu-messages::-webkit-scrollbar { width: 4px; }
    .saiqu-messages::-webkit-scrollbar-track { background: transparent; }
    .saiqu-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .saiqu-messages::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* ========== Responsive ========== */
    @media (max-width: 420px) {
        #saiqu-chat {
            width: calc(100vw - 20px);
            right: 10px;
            bottom: 70px;
            max-height: 75vh;
            border-radius: 16px;
        }
        #saiqu-fab {
            bottom: 135px;
            right: 15px;
            width: 52px;
            height: 52px;
        }
    }
</style>

{{-- Floating Action Button --}}
<button id="saiqu-fab" onclick="saiquToggle()" title="SaiQu AI Assistant" aria-label="Buka SaiQu AI Assistant">
    <i class="fas fa-robot"></i>
</button>

{{-- Chat Widget --}}
<div id="saiqu-chat" role="dialog" aria-label="SaiQu AI Chat">
    {{-- Header --}}
    <div class="saiqu-header">
        <div class="saiqu-header-left">
            <div class="saiqu-avatar-wrap"><i class="fas fa-robot"></i></div>
            <div>
                <div class="saiqu-header-title">SaiQu AI</div>
                <div class="saiqu-header-status">
                    <span class="saiqu-online-dot"></span> Online — HadirkuGO
                </div>
            </div>
        </div>
        <div class="saiqu-header-actions">
            <button onclick="saiquClearHistory()" title="Hapus riwayat"><i class="fas fa-trash-alt"></i></button>
            <button onclick="saiquToggle()" title="Tutup"><i class="fas fa-times"></i></button>
        </div>
    </div>

    {{-- Messages --}}
    <div class="saiqu-messages" id="saiqu-messages">
        <div class="saiqu-msg bot">
            Halo {{ Auth::user()->display_name ?? 'Kamu' }}! 👋<br>
            Saya <strong>SaiQu</strong>, asisten AI HadirkuGO.<br>
            Tanya apa saja tentang poin, absensi, level, tim, dan fitur lainnya!
        </div>
    </div>

    {{-- Typing Indicator --}}
    <div class="saiqu-typing" id="saiqu-typing">
        <span></span><span></span><span></span>
    </div>

    {{-- Quick Actions (loaded dynamically) --}}
    <div class="saiqu-quick-actions" id="saiqu-quick-actions">
        <div class="text-center w-100" style="padding: 4px 0;">
            <span class="small text-muted" style="font-size: 11px;">Memuat saran...</span>
        </div>
    </div>

    {{-- Input --}}
    <div class="saiqu-input-area">
        <input type="text" id="saiqu-input" placeholder="Tanya SaiQu..." maxlength="500"
               onkeydown="if(event.key==='Enter') saiquSend()" autocomplete="off">
        <button onclick="saiquSend()" id="saiqu-send-btn" class="saiqu-send-btn" aria-label="Kirim pesan">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
(function() {
    const SAIQU_CHAT_URL = '{{ route("saiqu.chat") }}';
    const SAIQU_CLEAR_URL = '{{ route("saiqu.clear") }}';
    const SAIQU_SUGGESTIONS_URL = '{{ route("saiqu.suggestions") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';

    let saiquOpen = false;
    let saiquSending = false;
    let quickActionsVisible = true;
    let suggestionsLoaded = false;

    // Load suggestions on first open
    function loadSuggestions() {
        if (suggestionsLoaded) return;
        suggestionsLoaded = true;

        fetch(SAIQU_SUGGESTIONS_URL, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.suggestions) {
                renderSuggestions(data.suggestions);
            }
        })
        .catch(() => {
            // Fallback to default suggestions
            renderSuggestions([
                { icon: '💰', text: 'Berapa poin saya?' },
                { icon: '⭐', text: 'Apa level saya?' },
                { icon: '📋', text: 'Info absensi saya' },
                { icon: '🔍', text: 'Fitur apa saja yang ada?' },
            ]);
        });
    }

    function renderSuggestions(suggestions) {
        const qa = document.getElementById('saiqu-quick-actions');
        qa.innerHTML = '';
        suggestions.forEach(s => {
            const btn = document.createElement('button');
            btn.className = 'saiqu-quick-btn';
            btn.textContent = s.icon + ' ' + s.text;
            btn.onclick = () => saiquQuick(s.text);
            qa.appendChild(btn);
        });
    }

    window.saiquToggle = function() {
        const chat = document.getElementById('saiqu-chat');
        const fab = document.getElementById('saiqu-fab');
        saiquOpen = !saiquOpen;

        if (saiquOpen) {
            fab.classList.add('saiqu-hiding');
            setTimeout(() => { fab.style.display = 'none'; }, 300);
            chat.classList.add('active');
            setTimeout(() => { document.getElementById('saiqu-input').focus(); }, 400);
            loadSuggestions();
        } else {
            chat.classList.remove('active');
            setTimeout(() => {
                fab.style.display = 'flex';
                requestAnimationFrame(() => { fab.classList.remove('saiqu-hiding'); });
            }, 350);
        }
    };

    window.saiquQuick = function(text) {
        document.getElementById('saiqu-input').value = text;
        hideQuickActions();
        saiquSend();
    };

    function hideQuickActions() {
        const qa = document.getElementById('saiqu-quick-actions');
        qa.style.transition = 'all 0.3s ease';
        qa.style.opacity = '0';
        qa.style.maxHeight = '0';
        qa.style.padding = '0 16px';
        qa.style.overflow = 'hidden';
        quickActionsVisible = false;
    }

    function showQuickActions() {
        const qa = document.getElementById('saiqu-quick-actions');
        qa.style.transition = 'all 0.3s ease';
        qa.style.opacity = '1';
        qa.style.maxHeight = '80px';
        qa.style.padding = '0 16px 10px';
        qa.style.overflow = 'visible';
        quickActionsVisible = true;
    }

    window.saiquSend = function() {
        if (saiquSending) return;

        const input = document.getElementById('saiqu-input');
        const message = input.value.trim();
        if (!message) return;

        if (quickActionsVisible) hideQuickActions();

        appendMessage('user', message);
        input.value = '';

        saiquSending = true;
        document.getElementById('saiqu-send-btn').disabled = true;
        const typing = document.getElementById('saiqu-typing');
        typing.classList.add('active');
        scrollToBottom();

        fetch(SAIQU_CHAT_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: message }),
        })
        .then(res => res.json())
        .then(data => {
            typing.classList.remove('active');
            appendMessage('bot', data.answer || 'Maaf, terjadi kesalahan.');
        })
        .catch(() => {
            typing.classList.remove('active');
            appendMessage('bot', 'Maaf, terjadi kesalahan koneksi. Coba lagi ya! 🔄');
        })
        .finally(() => {
            saiquSending = false;
            document.getElementById('saiqu-send-btn').disabled = false;
            document.getElementById('saiqu-input').focus();
        });
    };

    window.saiquClearHistory = function() {
        if (!confirm('Hapus semua riwayat percakapan?')) return;

        fetch(SAIQU_CLEAR_URL, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
        }).then(() => {
            const container = document.getElementById('saiqu-messages');
            container.innerHTML = '';
            appendMessage('bot', 'Riwayat dihapus ✨ Ada yang bisa SaiQu bantu?');
            // Reload fresh suggestions
            suggestionsLoaded = false;
            loadSuggestions();
            showQuickActions();
        });
    };

    function appendMessage(type, text) {
        const container = document.getElementById('saiqu-messages');
        const div = document.createElement('div');
        div.className = 'saiqu-msg ' + type;
        div.innerHTML = formatMessage(text);
        container.appendChild(div);
        scrollToBottom();
    }

    function formatMessage(text) {
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/\n/g, '<br>');
    }

    function scrollToBottom() {
        const container = document.getElementById('saiqu-messages');
        setTimeout(() => {
            container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
        }, 80);
    }
})();
</script>
