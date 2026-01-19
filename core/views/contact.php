<?php
$page_title = 'Encrypted Channel // Red Pulse';
?>
<div class="app-container">
    <div class="col-span-12 lg:col-span-6 lg:col-start-4 space-y-8 fade-in">

        <div class="tile p-8">
            <h1 class="text-2xl font-mono uppercase tracking-widest text-red-500 mb-6">// SECURE CHANNEL</h1>

            <p class="text-gray-400 mb-8 font-mono text-sm border-l-2 border-red-900 pl-4 py-2 bg-red-900/10">
                Identity protection is active. Your message will be routed through our secure relay.
                Direct communication with Quartermasters FZC is restricted to vetted entities.
            </p>

            <!-- Success/Error Feedback -->
            <?php if (isset($_GET['status']) && $_GET['status'] === 'sent'): ?>
                <div class="bg-green-900/20 border border-green-500/50 text-green-400 p-4 mb-6 font-mono text-sm">
                    [✔] TRANSMISSION COMPLETE. ACKNOWLEDGED.
                </div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div class="bg-red-900/20 border border-red-500/50 text-red-400 p-4 mb-6 font-mono text-sm">
                    [!] TRANSMISSION FAILED. RETRY.
                </div>
            <?php endif; ?>

            <form action="/contact" method="POST" class="space-y-6">

                <div>
                    <label class="block text-xs font-mono text-gray-500 uppercase mb-2">Identify (Name/Org)</label>
                    <input type="text" name="name" required
                        class="w-full bg-black border border-gray-800 text-white p-3 focus:border-red-500 focus:outline-none transition-colors font-mono">
                </div>

                <div>
                    <label class="block text-xs font-mono text-gray-500 uppercase mb-2">Relay Address (Email)</label>
                    <input type="email" name="email" required
                        class="w-full bg-black border border-gray-800 text-white p-3 focus:border-red-500 focus:outline-none transition-colors font-mono">
                </div>

                <div>
                    <label class="block text-xs font-mono text-gray-500 uppercase mb-2">Intelligence / Query</label>
                    <textarea name="message" rows="5" required
                        class="w-full bg-black border border-gray-800 text-white p-3 focus:border-red-500 focus:outline-none transition-colors font-mono"></textarea>
                </div>

                <!-- Honey Pot (Hidden) to trap bots -->
                <div style="display:none;">
                    <label>Don't fill this out if you're human: <input name="bot_check" /></label>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-red-900/20 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/50 font-mono text-sm uppercase px-8 py-3 transition-all duration-300 tracking-wider">
                        Initiate Transmission
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>