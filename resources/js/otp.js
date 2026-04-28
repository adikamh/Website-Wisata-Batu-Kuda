document.addEventListener('DOMContentLoaded', () => {
    const otpInput = document.getElementById('otp');
    const form = document.getElementById('otpForm');
    const submitButton = document.getElementById('otpSubmitBtn');
    const resendButton = document.getElementById('resendOtpButton');
    const countdownValue = document.getElementById('countdownValue');
    const resendHint = document.getElementById('resendHint');

    otpInput?.addEventListener('input', () => {
        otpInput.value = otpInput.value.replace(/\D/g, '').slice(0, 6);
        const valid = otpInput.value.length === 6;
        otpInput.classList.toggle('is-valid', valid);
        otpInput.classList.toggle('is-invalid', otpInput.value.length > 0 && ! valid);
    });

    form?.addEventListener('submit', () => {
        if (! form.checkValidity() || ! submitButton) {
            return;
        }

        const text = submitButton.querySelector('.btn-text');
        const icon = submitButton.querySelector('.btn-icon');
        const loader = submitButton.querySelector('.btn-loader');

        submitButton.disabled = true;

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

    if (resendButton && countdownValue && resendHint) {
        const resentAt = Number(resendButton.dataset.resentAt || 0);
        const duration = Number(resendButton.dataset.countdown || 60);
        const availableAt = (resentAt + duration) * 1000;

        const updateCountdown = () => {
            const remainingMs = availableAt - Date.now();
            const remaining = Math.max(0, Math.ceil(remainingMs / 1000));

            if (remaining > 0) {
                resendButton.disabled = true;
                resendButton.classList.add('is-disabled');
                countdownValue.textContent = String(remaining);
                resendHint.hidden = false;
            } else {
                resendButton.disabled = false;
                resendButton.classList.remove('is-disabled');
                resendHint.textContent = 'Bisa kirim ulang OTP sekarang.';
            }
        };

        updateCountdown();
        window.setInterval(updateCountdown, 1000);
    }
});
