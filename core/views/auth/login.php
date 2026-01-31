<div class="auth-container"
    style="max-width: 400px; margin: 4rem auto; padding: 2rem; background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 12px; text-align: center;">
    <h1 style="margin-bottom: 2rem; font-family: var(--font-headline);">Sign In</h1>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-error"
            style="color: #ef4444; background: rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
            <?php unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <p style="margin-bottom: 2rem; color: var(--text-secondary);">
        Access to detailed intelligence reports is restricted to authorized personnel and registered members.
    </p>

    <a href="/auth/google" class="btn btn-google"
        style="display: flex; align-items: center; justify-content: center; gap: 12px; background: #ffffff; color: #3c4043; border: 1px solid #dadce0; padding: 12px 24px; border-radius: 4px; font-weight: 500; font-family: 'Roboto', sans-serif; text-decoration: none; transition: background 0.2s;">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Logo"
            style="width: 18px; height: 18px;">
        <span>Sign in with Google</span>
    </a>

    <p style="margin-top: 2rem; font-size: 0.8rem; color: var(--text-muted);">
        By signing in, you agree to our <a href="/terms" style="color: var(--brand-primary);">Terms of Service</a> and
        <a href="/privacy" style="color: var(--brand-primary);">Privacy Policy</a>.
    </p>
</div>