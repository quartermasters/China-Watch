# Frontend Strategy: The Island Architecture

## Philosophy
To maintain the speed of a static site with the interactivity of a SPA, we use **HTMX**. This allows the server (Standard PHP) to render and return HTML fragments directly to the browser.

## The "Defcon Board" UI
The main view is not a scrollable feed but a dense grid of indicators.

### Components
1.  **The Signal Grid**:
    -   Rows of sparkline charts (SVG rendered on server).
    -   Color-coded tiles: Green (Stable), Yellow (Variance), Red (Anomaly).
    -   *Interaction*: Hovering triggers a detail popover (fetched via HTMX).

2.  **The Global Map (Lightweight)**:
    -   A CSS/SVG-based interactive map showing "Flow" lines.
    -   Clicking a country opens the "Diplomatic Ledger" for that nation.

3.  **The Ticker**:
    -   Real-time stream of "Anomalies" (e.g., "ALERT: Regulatory Velocity Spike at CAC").
    -   *Mechanism*: Polling endpoint `/api/ticker` every 30s.

## Implementation Details
-   **CSS**: Custom, utility-first (similar to Tailwind principles).
-   **No Build Step**: Complexity is kept on the server (PHP).
-   **Updates**: HTMX Polling (`hx-trigger="every 30s"`) instead of WebSockets (blocked on shared hosting).
