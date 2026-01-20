import argparse
import json
import sys
import time
import warnings
# Try importing, handling both old and new package structures if needed
try:
    from duckduckgo_search import DDGS
except ImportError:
    # If installed via 'pip install ddgs', it might still import slightly differently or just work.
    # Usually it exposes the same class.
    from duckduckgo_search import DDGS

warnings.filterwarnings("ignore")

def main():
    parser = argparse.ArgumentParser(description='Red Pulse Research Agent')
    parser.add_argument('--query', type=str, required=False, help='Search Query')
    parser.add_argument('--max_results', type=int, default=10, help='Max Results')
    parser.add_argument('--source', type=str, default='research_agent', help='Source Name')
    parser.add_argument('--url', type=str, required=False, help='Used as Query')
    
    args = parser.parse_args()

    query = args.query if args.query else args.url
    
    if not query:
        print(json.dumps({"status": "error", "message": "No query provided"}))
        sys.exit(1)

    results = []
    messages = []
    
    # Try different backends explicitly
    # 'html' is often best for scraping volume. 'api' is cleaner but stricter limits.
    backends = ['html', 'lite', 'api']
    
    for backend in backends:
        if len(results) > 0:
            break
            
        try:
            with DDGS() as ddgs:
                # Retrieve slightly more to filter
                gen = ddgs.text(query, region='wt-wt', safesearch='off', backend=backend, max_results=args.max_results)
                if gen:
                    for r in gen:
                        results.append({
                            "title": r.get('title'),
                            "href": r.get('href'),
                            "body": r.get('body')
                        })
                    messages.append(f"Backend '{backend}' success: {len(results)} items")
                else:
                    messages.append(f"Backend '{backend}' returned empty generator")
                    
        except Exception as e:
            messages.append(f"Backend '{backend}' error: {str(e)}")
            time.sleep(1)

    output = {
        "status": "success",
        "platform": "research_agent_debug",
        "query": query,
        "results": results,
        "count": len(results),
        "debug_log": messages
    }
    
    print(json.dumps(output))
    sys.exit(0)

if __name__ == "__main__":
    main()
