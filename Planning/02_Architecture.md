# Technical Architecture: The "Bunker" Stack (Shared Hosting)

## Overview
Adapted for Hostinger Business Hosting (Standard LAMP environment), "Red Pulse" shifts from a resident-memory model (Swoole) to a **stateless, queue-driven architecture**. This ensures reliability within execution time limits (360s) and memory constraints (2048MB).

## The Core Stack
-   **Runtime**: PHP 8.2 / 8.3 (FPM/CGI mode)
-   **Database**: MySQL 8.0 / MariaDB (Optimized with standard indexing)
-   **Queue**: Database-backed Job Queue (since Redis persistence isn't guaranteed on shared)
-   **Frontend**: HTMX + Twig (Server-Side Rendering)

## Domain-Driven Design (DDD) & Hexagonal Architecture
We retain the DDD approach but adapt the Infrastructure Adapters.

### 1. The Core Domain (Pure PHP)
-   `Signal`: Value object representing a data point.
-   `Anomaly`: Validation logic remains identical.

### 2. Adapters (Infrastructure)
-   **Inbound**:
    -   `HttpController`: Standard `public/index.php` router.
    -   `CronDispatcher`: A single entry point (`php scripts/dispatch.php`) run every minute by the host's cron.
-   **Outbound**:
    -   `DomScraper`: `Symfony\DomCrawler` wrapped in `Guzzle` with timeouts.
    -   `SqlRepository`: Standard PDO implementation with careful indexing.

## Queued Workflow (The "Pulse" Mechanism)
Instead of simultaneous async processes, we use a "Chunk & Churn" method.
1.  **Minute 0 (The Heartbeat)**: `cron` triggers `dispatch.php`. It checks the `sources` table for "due" items (e.g., source X hasn't been checked in 60 mins).
2.  **Dispatch**: It pushes 5 jobs to the `jobs` table (database queue).
3.  **Processing**: The same cron script (or a secondary generic worker) picks up the next 3 available jobs from the DB table.
4.  **Execution**:
    -   Scrape Source A.
    -   Parse.
    -   Store Signal.
    -   Check for Variance (Anomaly logic).
    -   Mark Job Complete.
5.  **Limits**: The worker enforces a "time-to-live" check. If it has been running for 50 seconds, it stops picking up new jobs to avoid the 60s cron overlap or 360s hard limit.

## Real-time Updates (The "Long Poll")
Since generic WebSockets are blocked (no incoming ports):
-   **Client**: HTMX uses `hx-trigger="every 30s"` to poll the server for the "Anomaly Ticker".
-   **Server**: Extremely lightweight query (indexed retrieval of last 5 anomalies) to respond in <20ms.
