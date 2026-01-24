<?php
$page_title = 'Encrypted Channel // China Watch';
?>
<div class="col-span-1 fade-in">

    <div class="tile p-8">
        <h1 class="text-2xl font-mono uppercase tracking-widest text-red-500 mb-6">// SECURE CHANNEL</h1>

        <p style="color: var(--text-secondary); margin-bottom: 2rem; font-family: var(--font-mono); font-size: var(--text-sm); border-left: 2px solid #DC2626; padding-left: 1rem; padding-top: 0.5rem; padding-bottom: 0.5rem; background: rgba(220, 38, 38, 0.05);">
            Identity protection is active. Your message will be routed through our secure relay.
            Direct communication with Quartermasters FZC is restricted to vetted entities.
        </p>

        <!-- Success/Error Feedback -->
        <?php if (isset($_GET['status']) && $_GET['status'] === 'sent'): ?>
            <div style="background: rgba(5, 150, 105, 0.1); border: 1px solid rgba(5, 150, 105, 0.5); color: #059669; padding: 1rem; margin-bottom: 1.5rem; font-family: var(--font-mono); font-size: var(--text-sm); border-radius: var(--radius-md);">
                [✔] TRANSMISSION COMPLETE. ACKNOWLEDGED.
            </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
            <div style="background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.5); color: #DC2626; padding: 1rem; margin-bottom: 1.5rem; font-family: var(--font-mono); font-size: var(--text-sm); border-radius: var(--radius-md);">
                [!] TRANSMISSION FAILED. RETRY.
            </div>
        <?php endif; ?>

        <form action="/contact" method="POST" class="space-y-6">

            <div>
                <label class="block text-xs font-mono text-gray-500 uppercase mb-2">Identify (Name/Org)</label>
                <input type="text" name="name" required
                    style="width:100%; background:var(--bg-light); border:1px solid var(--border-light); color:var(--text-body); padding:0.75rem; border-radius:var(--radius-md); font-family:var(--font-mono); outline:none;"
                    onfocus="this.style.borderColor='var(--brand-primary)'" onblur="this.style.borderColor='var(--border-light)'">
            </div>

            <div>
                <label class="block text-xs font-mono text-gray-500 uppercase mb-2">Relay Address (Email)</label>
                <input type="email" name="email" required
                    style="width:100%; background:var(--bg-light); border:1px solid var(--border-light); color:var(--text-body); padding:0.75rem; border-radius:var(--radius-md); font-family:var(--font-mono); outline:none;"
                    onfocus="this.style.borderColor='var(--brand-primary)'" onblur="this.style.borderColor='var(--border-light)'">
            </div>

            <div>
                <label class="block text-xs font-mono text-gray-500 uppercase mb-2">Intelligence / Query</label>
                <textarea name="message" rows="5" required
                    style="width:100%; background:var(--bg-light); border:1px solid var(--border-light); color:var(--text-body); padding:0.75rem; border-radius:var(--radius-md); font-family:var(--font-mono); outline:none; resize:vertical;"
                    onfocus="this.style.borderColor='var(--brand-primary)'" onblur="this.style.borderColor='var(--border-light)'"></textarea>
            </div>

            <!-- Honey Pot (Hidden) to trap bots -->
            <div style="display:none;">
                <label>Don't fill this out if you're human: <input name="bot_check" /></label>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    style="background:var(--brand-primary); color:white; border:none; font-family:var(--font-mono); font-size:var(--text-sm); text-transform:uppercase; padding:0.75rem 2rem; border-radius:var(--radius-md); cursor:pointer; letter-spacing:0.05em; transition:all 0.2s;"
                    onmouseover="this.style.background='#B91C1C'" onmouseout="this.style.background='var(--brand-primary)'">
                    Initiate Transmission
                </button>
            </div>

        </form>
    </div>

</div>