# Research: Perplexity AI Integration & Data Strategy

**Objective:** specific analysis of Perplexity AI's capabilities to determine if we should (A) Integrate their API directly or (B) Replicate their "Search-Reason-Cite" architecture to improve our `SmartSpider`.

## 1. Perplexity API (Sonar) analysis
Perplexity offers a "RAG-as-a-Service" API called **Sonar**. Unlike standard LLMs (GPT-4), Sonar models have built-in access to the live internet and are trained to cite sources.

### Key Models & Pricing (Jan 2026)
| Model | Best For | Approx Cost (Input/Output per 1M) |
| :--- | :--- | :--- |
| **sonar** | Quick, factual lookups (Low latency) | $1.00 / $1.00 |
| **sonar-pro** | Deep research, complex queries (GPT-4 class) | $3.00 / $15.00 |
| **sonar-reasoning-pro** | Multi-step logic, Chain-of-Thought | $2.00 / $8.00 (+ tokens) |
| **sonar-deep-research** | Exhaustive reports (The "Analyst" replacement) | Higher variable cost |

### Implementation Strategy (The "Buy" Option)
We can replace the complex `SmartSpider` (SerpAPI + Scrape + Content Clean + OpenAI) loop with a single `sonar-pro` call for certain tasks.

**Workflow:**
1.  **Topic Trigger:** "China Semiconductor Restrictions"
2.  **Perplexity Call:** "Write a detailed intelligence report on the latest China semiconductor restrictions, citing credible sources like SCMP, Reuters, and Caixin."
3.  **Result:** We receive a fully formed, cited report + a list of citations (URLs).
4.  **Archive:** We save the report and valid source URLs to our DB.

**Pros:**
*   **Speed:** One call vs. multiple scrapers.
*   **Reliability:** No "JS-walls" or 403 blocks (Perplexity handles the crawling).
*   **Citations:** Built-in grounding reduces hallucinations.

**Cons:**
*   **Control:** We cannot force it to "only read this specific PDF".
*   **Depth:** It may summarize too much compared to reading raw full text.

---

## 2. Replicating the "Perplexity Model" (The "Build" Option)
To "copy" their data collection model means upgrading our current `SmartSpider` + `ReportGenerator` into a true **Agentic RAG System**.

### Core Components to Build
1.  **Iterative Query Generation (The "Planner"):**
    *   *Current:* We search the exact topic string once.
    *   *Perplexity-style:* The AI analyzes the topic and generates 3-5 sub-queries.
    *   *Example:* Topic "China EV Tariffs" -> Queries: "EU tariffs on Chinese EVs 2025", "US 301 investigation EV outcome", "BYD response to trade barriers".

2.  **Multi-Source Synthesis & Grounding:**
    *   *Current:* We summarize article-by-article.
    *   *Perplexity-style:* We scrape 10 articles, chunk them, and feed them into the LLM with instructions to "Answer the user quote using ONLY these chunks and cite them [1]."

3.  **Active Crawling (Deep Research):**
    *   If the initial search results are vague, the Agent detects this and triggers a "Deep Dive" (following links inside the articles).

### Recommended Architecture Upgrade
We shout adopt a **Hybrid Approach**:
1.  **Use Perplexity API (`sonar-pro`)** for the "Discovery Phase" (finding what's new and getting a high-level summary overview).
2.  **Use our `SmartSpider`** to visit the *specific* citation URLs returned by Perplexity to archive the **full raw text** for our own database (ownership of data).

## 3. Immediate Action Plan
1.  **Sign up for Perplexity API** (User needs to do this).
2.  **Create `PerplexityService` class** in `core/src/Services`.
3.  **Update `crawl.php`:** Add a "Deep Research" mode that uses Perplexity to find 10 high-value URLs, then uses our Spider to archive them.

### Code Preview: Perplexity Service
```php
public function deep_research(string $topicModel): array
{
    $response = $this->client->post('chat/completions', [
        'model' => 'sonar-pro',
        'messages' => [
            ['role' => 'system', 'content' => 'You are an OSINT analyst. Find 5 key recent articles about: ' . $topicModel],
        ]
    ]);
    
    // We get citations (URLs) automatically in the response metadata!
    return $response['citations'];
}
```
