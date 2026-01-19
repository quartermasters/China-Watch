<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Domain\AIAnalyst;

class ChatController
{

    public function ask(): void
    {
        header('Content-Type: text/html');

        $question = $_POST['question'] ?? '';

        if (empty(trim($question))) {
            echo "<div class='message bot'>Awaiting input...</div>";
            return;
        }

        // Mock Data Context (In prod, fetch from DB)
        $context = [
            'LITHIUM' => '98,500 RMB/T (Falling)',
            'PORT_SHANGHAI' => 'Congestion Critical (+4.5%)',
            'REGULATORY' => 'High Activity (12 docs/hr)'
        ];

        try {
            $analyst = new AIAnalyst();
            $answer = $analyst->ask($question, $context);

            // Return HTMX partial: The User Q and the Bot A
            echo "<div class='message user'>" . htmlspecialchars($question) . "</div>";
            echo "<div class='message bot'>{$answer}</div>";

        } catch (\Exception $e) {
            echo "<div class='message error'>System Failure.</div>";
        }
    }
}
