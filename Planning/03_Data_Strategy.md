# Data Strategy: Signal Intelligence (SQL Version)

## Philosophy
Without TimescaleDB's "continuous aggregates," we must manage time-series aggregation manually to keep the dashboard fast.

## Schema Design (MySQL/MariaDB)

### 1. The `signals` Table (The Firehose)
Stores every raw data point. Partitioned by MONTH if host allows, otherwise indexed heavily by `created_at`.
```sql
CREATE TABLE signals (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    source_id INT NOT NULL,
    metric_code VARCHAR(50) NOT NULL, -- e.g. 'SHANGHAI_PORT_TEU'
    value DECIMAL(16, 4) NOT NULL,    -- Hard numbers
    captured_at DATETIME NOT NULL,
    INDEX idx_metric_time (metric_code, captured_at)
);
```

### 2. The `daily_snapshots` Table (The Cache)
To avoid scanning millions of rows for a 30-day chart, we aggregate daily.
-   *Trigger*: A nightly cron job sums up the `signals` table for the previous day.
-   *Columns*: `metric_code`, `date`, `avg_value`, `min`, `max`, `variance_score`.

### 3. The `anomalies` Table (The Alerts)
Only interesting events.
-   *Trigger*: Inserted immediately during ingestion if `RunAnomalyCheck()` returns true.
-   *Usage*: This is what the Dashboard polls every 30s.

## Domain Modules (Adapted)

### 1. Economic Monitor
-   **Logic**: Same scraping logic, but results are saved to `signals`.

### 2. Political Monitor ("Ritual Engine")
-   Instead of storing every word, we store **Hourly Frequency Counts** in `signals`.
    -   `metric_code`: `KEYWORD_FOOD_SECURITY`
    -   `value`: `42` (mentions per hour)

## Performance Strategy
-   **Ingestion**: "Upsert" logic to prevent duplicates (`INSERT IGNORE` or `ON DUPLICATE KEY UPDATE`).
-   **Reading**: usage of "Covering Indexes" so charts can be rendered purely from index scans without hitting table data pages.
-   **Pruning**: A cron script deletes `signals` older than 90 days, keeping only `daily_snapshots` for long-term trends, keeping table size manageable on shared hosting (usually 50GB limit).
