<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Core\DB;

class ChatController
{
    /**
     * Handle chat queries (HTMX POST)
     */
    public function ask(): void
    {
        header('Content-Type: text/html');

        $question = trim($_POST['question'] ?? '');

        // 1. Render User Message
        echo '<div class="message user" style="text-align:right; color:#ccc; margin-bottom:10px;">' . htmlspecialchars($question) . '</div>';

        // 2. Artificial Delay (Cinematic Feel)
        usleep(500000); // 0.5s

        // 3. Context Retrieval (The "Brain")
        $answer = $this->generateAnswer($question);

        // 4. Render Bot Response
        echo '<div class="message bot" style="color:var(--signal-blue); margin-bottom:10px;">' . $answer . '</div>';
    }

    private function generateAnswer(string $input): string
    {
        $input = strtolower($input);

        // A. Keyword: "Lithium"
        if (strpos($input, 'lithium') !== false) {
            $data = DB::query("SELECT value, captured_at FROM signals WHERE source_id IN (SELECT id FROM sources WHERE name LIKE '%Lithium%') ORDER BY captured_at DESC LIMIT 1");
            if ($data) {
                return "Latest Lithium Carbonate spot price: <strong>" . number_format((float) $data[0]['value'], 2) . "</strong> (Captured: " . substr($data[0]['captured_at'], 0, 10) . "). Trend is stabilizing.";
            }
            return "Searching... No recent Lithium data found in the repository.";
        }

        // B. Keyword: "Port" or "Shipping"
        if (strpos($input, 'port') !== false || strpos($input, 'shipping') !== false) {
            $data = DB::query("SELECT value, captured_at FROM signals WHERE source_id IN (SELECT id FROM sources WHERE name LIKE '%Shanghai Port%') ORDER BY captured_at DESC LIMIT 1");
            if ($data) {
                return "Shanghai Port TEU Index is currently at <strong>" . number_format((float) $data[0]['value']) . "</strong>. Congestion levels are within normal parameters.";
            }
            return "Port telemetry is currently unavailable.";
        }

        // C. Keyword: "Anomaly" or "Alert"
        if (strpos($input, 'anomaly') !== false || strpos($input, 'alert') !== false) {
            $alerts = DB::query("SELECT message FROM anomalies ORDER BY created_at DESC LIMIT 3");
            if ($alerts) {
                $html = "Recent System Alerts:<br>";
                foreach ($alerts as $a) {
                    $html .= "- " . htmlspecialchars($a['message']) . "<br>";
                }
                return $html;
            }
            return "System Status Optimal. No anomalies detected in the last 24 hours.";
        }

        // D. Fallback (The "LLM" Simulation)
        $fallbacks = [
            "I am the China Watch Analyst. I can answer questions about Lithium prices, Port traffic, or System anomalies.",
            "Accessing distributed ledger... Data not found for that specific query. Try asking about 'Lithium' or 'Ports'.",
            "Awaiting input. Please specify a sector (e.g., Energy, Logistics)."
        ];

        return $fallbacks[array_rand($fallbacks)];
    }
}
