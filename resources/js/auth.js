document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-pw').forEach((button) => {
        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.target);
            const eyeShow = button.querySelector('.eye-show');
            const eyeHide = button.querySelector('.eye-hide');

            if (! target) {
                return;
            }

            const showPassword = target.type === 'password';
            target.type = showPassword ? 'text' : 'password';

            if (eyeShow && eyeHide) {
                eyeShow.style.display = showPassword ? 'none' : 'block';
                eyeHide.style.display = showPassword ? 'block' : 'none';
            }
        });
    });

    const passwordInput = document.getElementById('password');
    const passwordBar = document.getElementById('pwBar');
    const passwordHint = document.getElementById('pwHint');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const matchHint = document.getElementById('matchHint');
    const usernameInput = document.getElementById('username');
    const usernameStatus = document.getElementById('username-status');
    const loginInput = document.getElementById('login');

    if (passwordInput && passwordBar) {
        passwordInput.addEventListener('input', () => {
            const value = passwordInput.value;
            const score = getPasswordScore(value);
            const colors = ['#dc2626', '#ea580c', '#d97706', '#2f855a', '#276749'];
            const labels = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
            const widths = ['20%', '40%', '60%', '80%', '100%'];

            if (value.length === 0) {
                passwordBar.style.width = '0%';
                passwordHint.textContent = '';
                passwordHint.style.color = '';
            } else {
                passwordBar.style.width = widths[score];
                passwordBar.style.background = colors[score];
                passwordHint.textContent = labels[score];
                passwordHint.style.color = colors[score];
            }

            validatePasswordMatch();
        });
    }

    if (passwordConfirmation) {
        passwordConfirmation.addEventListener('input', validatePasswordMatch);
    }

    if (usernameInput && usernameStatus) {
        usernameInput.addEventListener('input', () => {
            const value = usernameInput.value.trim();
            const valid = /^[a-zA-Z0-9_]{3,20}$/.test(value);

            if (value === '') {
                usernameStatus.textContent = '';
                usernameInput.classList.remove('is-valid', 'is-invalid');
                return;
            }

            usernameStatus.textContent = valid ? 'Username valid' : '3-20 karakter tanpa spasi';
            usernameStatus.style.color = valid ? 'var(--green-fresh)' : 'var(--error)';
            usernameInput.classList.toggle('is-valid', valid);
            usernameInput.classList.toggle('is-invalid', ! valid);
        });
    }

    if (loginInput) {
        loginInput.addEventListener('blur', () => {
            const value = loginInput.value.trim();

            if (value === '') {
                loginInput.classList.remove('is-valid', 'is-invalid');
                return;
            }

            const looksLikeEmail = value.includes('@');
            const valid = looksLikeEmail
                ? /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
                : /^[a-zA-Z0-9_]{3,50}$/.test(value);

            loginInput.classList.toggle('is-valid', valid);
            loginInput.classList.toggle('is-invalid', ! valid);
        });
    }

    document.querySelectorAll('.input-wrap input').forEach((input) => {
        input.addEventListener('focus', () => {
            input.closest('.input-wrap')?.querySelector('.input-icon')?.style.setProperty('color', 'var(--green-fresh)');
        });

        input.addEventListener('blur', () => {
            if (! input.classList.contains('is-valid') && ! input.classList.contains('is-invalid')) {
                input.closest('.input-wrap')?.querySelector('.input-icon')?.style.setProperty('color', 'var(--text-muted)');
            }
        });
    });

    ['loginForm', 'registerForm'].forEach((formId) => {
        const form = document.getElementById(formId);

        if (! form) {
            return;
        }

        form.addEventListener('submit', () => {
            if (! form.checkValidity()) {
                return;
            }

            const button = form.querySelector('#submitBtn');
            const text = button?.querySelector('.btn-text');
            const icon = button?.querySelector('.btn-icon');
            const loader = button?.querySelector('.btn-loader');

            if (! button) {
                return;
            }

            button.disabled = true;

            if (text) {
                text.style.opacity = '0.72';
            }

            if (icon) {
                icon.style.display = 'none';
            }

            if (loader) {
                loader.style.display = 'flex';
            }
        });
    });

    function getPasswordScore(password) {
        let score = 0;

        if (password.length >= 8) {
            score++;
        }

        if (password.length >= 12) {
            score++;
        }

        if (/[A-Z]/.test(password)) {
            score++;
        }

        if (/[0-9]/.test(password)) {
            score++;
        }

        if (/[^A-Za-z0-9]/.test(password)) {
            score++;
        }

        return Math.min(score, 4);
    }

    function validatePasswordMatch() {
        if (! passwordConfirmation || ! matchHint) {
            return;
        }

        if (passwordConfirmation.value === '') {
            matchHint.textContent = '';
            passwordConfirmation.classList.remove('is-valid', 'is-invalid');
            return;
        }

        const matched = passwordInput && passwordConfirmation.value === passwordInput.value;
        matchHint.textContent = matched ? 'Password cocok' : 'Password tidak cocok';
        matchHint.style.color = matched ? 'var(--green-fresh)' : 'var(--error)';
        passwordConfirmation.classList.toggle('is-valid', matched);
        passwordConfirmation.classList.toggle('is-invalid', ! matched);
    }
});
