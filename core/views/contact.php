<?php
$page_title = 'Contact - China Watch';
?>
<main class="page-container" style="max-width: 600px; margin: 0 auto; padding: 2rem;">

    <h1 class="font-headline text-4xl mb-4" style="color: var(--text-primary);">Contact Us</h1>

    <p style="color: var(--text-secondary); margin-bottom: 2rem; line-height: 1.6;">
        Have questions about our research, want to subscribe to our newsletter, or interested in collaboration?
        We'd love to hear from you.
    </p>

    <!-- Success/Error Feedback -->
    <?php if (isset($_GET['status']) && $_GET['status'] === 'sent'): ?>
        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #059669; padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px;">
            Thank you for your message. We'll get back to you soon.
        </div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #DC2626; padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px;">
            There was an error sending your message. Please try again.
        </div>
    <?php endif; ?>

    <form action="/contact" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">

        <div>
            <label style="display: block; color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;">Name</label>
            <input type="text" name="name" required
                style="width: 100%; background: var(--bg-white); border: 1px solid var(--border-light); color: var(--text-body); padding: 0.75rem; border-radius: 6px; outline: none; font-size: 1rem;"
                onfocus="this.style.borderColor='var(--brand-primary)'" onblur="this.style.borderColor='var(--border-light)'">
        </div>

        <div>
            <label style="display: block; color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;">Email</label>
            <input type="email" name="email" required
                style="width: 100%; background: var(--bg-white); border: 1px solid var(--border-light); color: var(--text-body); padding: 0.75rem; border-radius: 6px; outline: none; font-size: 1rem;"
                onfocus="this.style.borderColor='var(--brand-primary)'" onblur="this.style.borderColor='var(--border-light)'">
        </div>

        <div>
            <label style="display: block; color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem;">Message</label>
            <textarea name="message" rows="5" required
                style="width: 100%; background: var(--bg-white); border: 1px solid var(--border-light); color: var(--text-body); padding: 0.75rem; border-radius: 6px; outline: none; font-size: 1rem; resize: vertical;"
                onfocus="this.style.borderColor='var(--brand-primary)'" onblur="this.style.borderColor='var(--border-light)'"
                placeholder="How can we help you?"></textarea>
        </div>

        <!-- Honeypot (Hidden) to trap bots -->
        <div style="display: none;">
            <label>Leave blank: <input name="bot_check" /></label>
        </div>

        <div>
            <button type="submit"
                style="background: var(--brand-primary); color: white; border: none; font-size: 1rem; padding: 0.875rem 2rem; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.2s;"
                onmouseover="this.style.background='#B91C1C'" onmouseout="this.style.background='var(--brand-primary)'">
                Send Message
            </button>
        </div>

    </form>

    <section style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
        <h2 style="color: var(--text-primary); font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Other Ways to Connect</h2>
        <p style="color: var(--text-secondary); line-height: 1.6;">
            For press inquiries, partnership opportunities, or general questions,
            you can also reach us at <strong>contact@chinawatch.blog</strong>
        </p>
    </section>

</main>
