# Legal Strategy: Compliance & Liability

## 1. The "Data Aggregator" Disclaimer
Since *Red Pulse* provides financial/economic data that could influence investment decisions, we must protect against liability.

### Critical Disclaimer (Footer & Terms)
> "The data provided on ChinaWatch.com is for informational purposes only. It does not constitute financial, investment, or legal advice. While we strive for accuracy, we do not guarantee the reliability of 3rd-party data sources (e.g., Shanghai Shipping Exchange). Use at your own risk."

## 2. Privacy Policy Strategy (GDPR & CCPA)
We will generate a "Global-Ready" policy using a hybrid approach.

### Key Clauses
1.  **Data Collection**: We collect minimal data (IP address, Browser Type) for *security* (WAF) and *analytics*.
2.  **No Account Required**: Most users are anonymous.
3.  **Third-Party Sharing**: We share data with:
    *   Google Analytics (Traffic)
    *   Hostinger (Server Logs)
4.  **Rights**:
    *   "Right to Delete" (GDPR Art. 17)
    *   "Do Not Sell My Info" (CCPA/CPRA)

## 3. Cookie Consent (The "Lightweight" Approach)
Avoid expensive SaaS platforms (like OneTrust) for this MVP. Use **Open Source**.

### Implementation: `orestbida/cookieconsent` (v3)
*   **Why**: Lightweight (30kb), GDPR compliant, blocking architecture.
*   **Categories**:
    *   **Necessary**: Security tokens, load balancing (Always On).
    *   **Analytics**: Google Analytics 4 (Blocked until "Accept").
*   **UI**: A bottom-bar banner:
    > "We use cookies to analyze traffic. [Accept All] [Reject Non-Essential]"

## 4. US State Compliance (CPRA/VCDPA)
*   **"Do Not Sell" Link**: A standardized link in the footer is required for California users.
*   **Opt-Out Signal**: We will respect the "Global Privacy Control" (GPC) header if sent by the browser.

## 5. Implementation Plan
1.  **Draft Policies**: Use a generator (e.g., Termly/GetTerms) for the base text, then customize with our specific data sources.
2.  **Deploy Banner**: Add `cookieconsent.js` to the `Footer` partial in PHP.
3.  **GTM Triggers**: Configure Google Tag Manager to *only* fire tags when the `analytics_storage` consent variable is 'granted'.
