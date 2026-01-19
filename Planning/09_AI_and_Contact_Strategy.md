# AI & Privacy Strategy

## 1. The "Analyst" AI Chatbot
Since we are on shared hosting, we cannot run a local LLM. We will build a **PHP Proxy Agent**.

### Architecture
1.  **Frontend**: A floating "Chat Bubble" widget (HTMX).
2.  **Backend (`/api/chat.php`)**:
    *   Receives user message.
    *   **Context Injection**: The script fetches the *latest daily snapshot* from the MySQL database (e.g., "Lithium Price: 98k, Port Traffic: High").
    *   **System Prompt**: "You are Red Pulse, a China economic analyst. Answer using ONLY this data context."
    *   **API Call**: Sends request to OpenAI (GPT-4o-mini) or Anthropic (Claude 3 Haiku) for speed and low cost options.
    *   **Response**: Returns the answer to the widget.

### Use Cases
*   **Summarization**: "What happened in the markets yesterday?" (AI reads the snapshot summary).
*   **Data Retrieval**: "What is the current trend for copper?" (AI extracts the specific metric).
*   **No Hallucinations**: We set `temperature: 0.1` and strictly limit it to provided database context.

## 2. "Zero-Exposure" Contact Strategy
We will strictly hide all personal contact details (Email, Phone, Address) from the frontend "scraperable" layer.

### The "Black Box" Contact Form
Instead of `support@chinawatch.com`, we use a secure form:
*   **URL**: `/contact`
*   **Mechanism**:
    1.  User fills text field.
    2.  PHP script sanitizes input.
    3.  PHP uses `PHPMailer` to send the message to your private inbox via **SMTP**.
    4.  **Crucial**: The email headers are set so the *reply-to* is the user, but the *sender* is `noreply@chinawatch.com`. Your personal address is never exposed in the source code or headers.

### Legal Address (The "Cloud" Entity)
*   **Terms of Service**: We list the entity simply as "ChinaWatch Operations".
*   **Domain Privacy**: Ensure "WHOIS Privacy Protection" is enabled at Hostinger to hide domain owner details.
*   **Regulatory Backstop**: If a specific law (like GDPR) demands an address, we recommend using a **Virtual Mailbox** (e.g., Earth Class Mail) so you never list a residential or personal office address.

## 3. Implementation Plan
1.  **AI**: Register OpenAI API Key -> Add to `env.php`.
2.  **Contact**: Configure Hostinger Email (`noreply@`) -> Setup SMTP in PHP.
