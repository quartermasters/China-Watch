# Growth Strategy: SEO, GEO, and Keywords

## 1. SEO Strategy: Programmatic Data Pages
Since "Red Pulse" is a data platform, we leverage **Programmatic SEO**. Instead of writing blog posts, we auto-generate thousands of specific landing pages for our data.

### The "Metric Page" Template
For every signal we track (e.g., "Shanghai Port Traffic", "Lithium Spot Price"), we generate a permanent URL:
*   `chinawatch.com/data/shanghai-port-teu-traffic`
*   `chinawatch.com/data/lithium-carbonate-spot-price`

**Key Elements:**
*   **H1**: "Current Shanghai Port Traffic Data & Trends (2026)"
*   **The Answer Box**: A direct HTML summary at the top ("Traffic is down 2% week-over-week").
*   **Schema.org**: We wrap this in `Dataset` schema so Google highlights it as a data source.
*   **Performance**: Server-Rendered (HTMX) = Near instant First Contentful Paint (FCP), boosting rankings.

## 2. GEO (Generative Engine Optimization)
We optimize for AI Search (Perplexity, ChatGPT, Gemini). These engines look for **Facts**, not fluff.

### The "Fact Snippet" Architecture
We structure our HTML so LLMs can easily extract the "Truth":
*   **Definition Lists**: Use `<dl>`, `<dt>`, `<dd>` tags for metrics.
    ```html
    <dl>
        <dt>Current Lithium Price</dt>
        <dd>98,000 RMB/ton</dd>
        <dt>Trend</dt>
        <dd>Decreasing (-1.2%)</dd>
    </dl>
    ```
*   **Citations**: Every page has a clear "Source: Shanghai Shipping Exchange, Accessed: [Date]" footer. LLMs prioritize sources they can verify.
*   **Context Blocks**: We add a "Why this matters" paragraph to every metric, giving the AI the *qualitative* context it needs to construct a narrative answer.

## 3. Keyword Strategy: The "Analyst" Long-Tail
We avoid generic, high-competition keywords like "China News" (dominated by CNN, BBC).
We target **High-Intent Commercial/Financial Keywords**:

*   **Tier 1 (The "Data" Seekers)**:
    *   "China manufacturing PMI historical data"
    *   "Shenzhen port congestion index live"
    *   "Rare earth export quotas 2026"
*   **Tier 2 (The "Risk" Seekers)**:
    *   "China supply chain disruption alerts"
    *   "Regulatory risk china tech sector"
    *   "Political risk index china investment"

**Result**: We get fewer visitors, but they are investment professionals and decision-makers, not casual readers.
