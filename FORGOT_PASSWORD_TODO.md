# Forgot Password Implementation Plan (OTP-based)

## Information Gathered:
- Placeholder route exists, link in login.blade.php.
- Reuse OTP system for reset consistency.
- password_reset_tokens table exists but unused for this.

## Steps:
- [ ] Step 1: Create TODO.md for forgot password (done)
- [x] Step 2: Create resources/views/Auth/forgot-password.blade.php
- [x] Step 3: Create resources/views/Auth/reset-password.blade.php  
- [x] Step 4: Edit app/Http/Controllers/Auth/AuthController.php - add showForgotPassword(), sendResetOtp(), showResetPassword(), resetPassword()
- [x] Step 5: Edit routes/web.php - add routes
- [x] Step 6: Update TODO.md after edits
- [x] Step 7: Complete

## Followup: Test flow after all changes.
