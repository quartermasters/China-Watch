<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Core\View;

class ContactController
{
    /**
     * Show the contact form
     */
    public function index(): void
    {
        View::render('contact');
    }

    /**
     * Handle form submission
     */
    public function send(): void
    {
        // 1. Bot Trap
        if (!empty($_POST['bot_check'])) {
            // Silently fail for bots
            header('Location: /contact');
            return;
        }

        // 2. Sanitize & Validate
        $name = strip_tags(trim($_POST['name'] ?? ''));
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $message = strip_tags(trim($_POST['message'] ?? ''));

        if (!$name || !$email || !$message) {
            header('Location: /contact?status=error');
            return;
        }

        // 3. Prepare Email
        // Uses the 'noreply' sender to protect your personal identity
        $to = 'haroon@quartermasters.me'; // HARDCODED for MVP, or use env('ADMIN_EMAIL')
        $subject = "China Watch Comms: $name";

        $body = "New secure transmission:\n\n";
        $body .= "From: $name\n";
        $body .= "Email: $email\n";
        $body .= "Time: " . date('Y-m-d H:i:s') . "\n\n";
        $body .= "Message:\n$message\n";

        // Headers for "Zero-Exposure" Reply
        $headers = [
            'From' => 'noreply@chinawatch.blog',
            'Reply-To' => $email,
            'X-Mailer' => 'PHP/' . phpversion(),
            'Content-Type' => 'text/plain; charset=utf-8'
        ];

        // 4. Send (Basic PHP Mail for shared hosting compatibility)
        // Note: For high deliverability, upgrading to SMTP (PHPMailer) is recommended later.
        $success = mail($to, $subject, $body, $headers);

        if ($success) {
            header('Location: /contact?status=sent');
        } else {
            // Log error if needed
            header('Location: /contact?status=error');
        }
    }
}
