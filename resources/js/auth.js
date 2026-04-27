// ─── auth.js ───────────────────────────────────────────────
// Password toggle, strength meter, match check, form loader

document.addEventListener('DOMContentLoaded', () => {

    // ── 1. Toggle show/hide password ──────────────────────
    document.querySelectorAll('.toggle-pw').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.dataset.target;
            const input    = document.getElementById(targetId);
            const eyeShow  = btn.querySelector('.eye-show');
            const eyeHide  = btn.querySelector('.eye-hide');

            if (input.type === 'password') {
                input.type   = 'text';
                eyeShow.style.display = 'none';
                eyeHide.style.display = 'block';
            } else {
                input.type   = 'password';
                eyeShow.style.display = 'block';
                eyeHide.style.display = 'none';
            }
        });
    });

    // ── 2. Password strength meter (register only) ─────────
    const pwInput = document.getElementById('password');
    const pwBar   = document.getElementById('pwBar');
    const pwHint  = document.getElementById('pwHint');

    if (pwInput && pwBar) {
        pwInput.addEventListener('input', () => {
            const val      = pwInput.value;
            const strength = calcStrength(val);
            const colors   = ['#e05252', '#e88c3a', '#c8a44a', '#40916c', '#2d6a4f'];
            const labels   = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
            const widths   = ['20%', '40%', '60%', '80%', '100%'];

            if (val.length === 0) {
                pwBar.style.width      = '0%';
                pwHint.textContent     = '';
                pwHint.style.color     = '';
            } else {
                pwBar.style.width      = widths[strength];
                pwBar.style.background = colors[strength];
                pwHint.textContent     = labels[strength];
                pwHint.style.color     = colors[strength];
            }

            // trigger match check too
            checkMatch();
        });
    }

    function calcStrength(pw) {
        let score = 0;
        if (pw.length >= 8)   score++;
        if (pw.length >= 12)  score++;
        if (/[A-Z]/.test(pw)) score++;
        if (/[0-9]/.test(pw)) score++;
        if (/[^A-Za-z0-9]/.test(pw)) score++;
        return Math.min(score, 4);
    }

    // ── 3. Password match check ────────────────────────────
    const pwConfirm  = document.getElementById('password_confirmation');
    const matchHint  = document.getElementById('matchHint');

    function checkMatch() {
        if (!pwConfirm || !matchHint) return;
        if (pwConfirm.value === '') {
            matchHint.textContent = '';
            pwConfirm.classList.remove('is-valid', 'is-invalid');
            return;
        }
        if (pwInput && pwConfirm.value === pwInput.value) {
            matchHint.textContent  = '✓ Password cocok';
            matchHint.style.color  = 'var(--green-fresh)';
            pwConfirm.classList.add('is-valid');
            pwConfirm.classList.remove('is-invalid');
        } else {
            matchHint.textContent  = '✗ Password tidak cocok';
            matchHint.style.color  = 'var(--error)';
            pwConfirm.classList.add('is-invalid');
            pwConfirm.classList.remove('is-valid');
        }
    }

    pwConfirm?.addEventListener('input', checkMatch);

    // ── 4. Username validation feedback ────────────────────
    const usernameInput  = document.getElementById('username');
    const usernameStatus = document.getElementById('username-status');

    if (usernameInput && usernameStatus) {
        usernameInput.addEventListener('input', () => {
            const val = usernameInput.value.trim();
            if (val === '') {
                usernameStatus.textContent = '';
                usernameInput.classList.remove('is-valid', 'is-invalid');
                return;
            }
            if (/^[a-zA-Z0-9_]{3,20}$/.test(val)) {
                usernameStatus.textContent  = '✓ Valid';
                usernameStatus.style.color  = 'var(--green-fresh)';
                usernameInput.classList.add('is-valid');
                usernameInput.classList.remove('is-invalid');
            } else {
                usernameStatus.textContent  = '✗ 3-20 karakter, tanpa spasi';
                usernameStatus.style.color  = 'var(--error)';
                usernameInput.classList.add('is-invalid');
                usernameInput.classList.remove('is-valid');
            }
        });
    }

    // ── 5. Email live validation ────────────────────────────
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', () => {
            const val   = emailInput.value.trim();
            const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
            emailInput.classList.toggle('is-valid',   val !== '' && valid);
            emailInput.classList.toggle('is-invalid', val !== '' && !valid);
        });
    }

    // ── 6. Submit loader ────────────────────────────────────
    ['loginForm', 'registerForm'].forEach(id => {
        const form = document.getElementById(id);
        if (!form) return;

        form.addEventListener('submit', (e) => {
            const btn    = form.querySelector('#submitBtn');
            const text   = btn?.querySelector('.btn-text');
            const icon   = btn?.querySelector('.btn-icon');
            const loader = btn?.querySelector('.btn-loader');

            if (!btn) return;

            // Basic validation before showing loader
            if (!form.checkValidity()) return;

            btn.disabled            = true;
            if (text)   text.style.opacity   = '0.7';
            if (icon)   icon.style.display   = 'none';
            if (loader) loader.style.display = 'flex';
        });
    });

    // ── 7. Input focus animation ────────────────────────────
    document.querySelectorAll('.input-wrap input').forEach(input => {
        input.addEventListener('focus', () => {
            input.closest('.input-wrap')?.querySelector('.input-icon')
                ?.style.setProperty('color', 'var(--green-fresh)');
        });
        input.addEventListener('blur', () => {
            if (!input.classList.contains('is-valid') && !input.classList.contains('is-invalid')) {
                input.closest('.input-wrap')?.querySelector('.input-icon')
                    ?.style.setProperty('color', 'var(--text-muted)');
            }
        });
    });
});