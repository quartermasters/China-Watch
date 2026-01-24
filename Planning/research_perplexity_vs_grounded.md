# Research: Perplexity Search API vs. Grounded LLM

The user asked for the difference between **Perplexity Search API** and **Grounded LLM**. These represent two different architectural approaches to solving the same problem: *Preventing Hallucinations with Real-Time Data.*

## 1. High-Level Difference

| Feature | **Perplexity Search API (Sonar)** | **Grounded LLM (Google Vertex / Custom RAG)** |
| :--- | :--- | :--- |
| **Philosophy** | **"Buy the Result"** (Service) | **"Build the Pipeline"** (Architecture) |
| **What it is** | An end-to-end "Answer Engine". You send a question, it returns a final answer + citations. | A raw LLM (Gemini/GPT) connected to a Search Tool (Google Search). The LLM "reads" the results in real-time. |
| **Complexity** | **Low**. 1 API call does everything (Search -> Read -> Synthesize -> Cite). | **High**. You must orchestrate: Search API -> Scraping -> Context Injection -> LLM Prompting. |
| **Control** | **Low**. Perplexity decides which sites to read and how to summarize them. | **High**. You decide exactly which sites to trust, what parts to read, and how to verify facts. |
| **Cost Model** | Token-based (Input + Output). | Mixed (Search API cost per query + LLM Token cost). |

---

## 2. Deep Dive: Perplexity Search API (`sonar-pro`)

**How it works:**
It is a "Black Box". When you send a query *"What are China's latest cobalt export restrictions?"*:
1.  Perplexity's internal system searches the web (Google/Bing).
2.  It uses a specialized "reasoning model" to select the best 5-10 pages.
3.  It scrapes those pages (handling paywalls, PDFs, and blockers itself).
4.  It synthesizes the answer and forces the model to cite the exact URL for every claim.

**Best For:**
*   **China Watch's "Discovery" Phase:** Adding a feature to "Find new topics" or "Summarize the daily news".
*   **Rapid Development:** You can build a "Research Agent" in 5 minutes.
*   **Reliability:** You don't have to worry about your IP getting banned by news sites.

---

## 3. Deep Dive: Grounded LLM (The "Build It" Approach)

**How it works:**
This is what Google calls "Grounding with Google Search" or what we currently do with `SmartSpider` but more integrated.
1.  **Orchestration:** Your code receives the user query.
2.  **Tool Use:** You call `Google Search API` (or `SerpAPI`) to get a list of links.
3.  **Retrieval:** Your code (or the Grounding service) visits those links to get the text.
4.  **Context Window:** You paste that text into the LLM's prompt: *"Answer the user using ONLY these facts..."*

**Best For:**
*   **China Watch's "Archival" Phase:** We *need* the raw text to save into our database for history. Perplexity doesn't always give the full raw text, just the answer.
*   **Strict Sourcing:** If we *only* want to trust "Caixin" and "People's Daily" and ignore "Twitter", we can enforce that manually here.

---

## 4. Recommendation for China Watch

We should use **Both**, but for different stages of the pipeline.

### Stage A: The Scout (Perplexity API)
Use Perplexity to **find the needle in the haystack**.
*   *Query:* "Find the most obscure but important regulations released by the NDRC this week."
*   *Result:* Perplexity returns a summary and **5 specific URLs**.
*   *Why:* It performs the "intelligence reasoning" better than a simple keyword search.

### Stage B: The Archivist (Grounded LLM / SmartSpider)
Use our custom spider to **fetch those specific URLs**.
*   *Action:* We take the 5 URLs from Perplexity.
*   *Process:* We visit them, download the full HTML, clean it, and save it to our `signals` database.
*   *Why:* We own the data. 

**Summary:** use Perplexity to *find* the news, use your own Grounded system to *keep* the news.
