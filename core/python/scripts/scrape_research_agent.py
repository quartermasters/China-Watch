import argparse
import json
import sys
import time
import warnings
from duckduckgo_search import DDGS

# Filters warnings about package renaming/async loops
warnings.filterwarnings("ignore")

def main():
    parser = argparse.ArgumentParser(description='Red Pulse Research Agent')
    parser.add_argument('--query', type=str, required=False, help='Search Query')
    parser.add_argument('--max_results', type=int, default=10, help='Max Results')
    parser.add_argument('--source', type=str, default='research_agent', help='Source Name')
    parser.add_argument('--url', type=str, required=False, help='Used as Query in this context')
    
    args = parser.parse_args()

    # Normalize Query
    query = args.query
    if not query and args.url:
        query = args.url
    
    if not query:
        print(json.dumps({"status": "error", "message": "No query provided"}))
        sys.exit(1)

    try:
        results = []
        # Retry logic: Try 3 times with exponential backoff
        for attempt in range(3):
            try:
                with DDGS() as ddgs:
                    # 'wt-wt' is "World-World" (No Region), 'cn-zh' is China
                    # Using backend='api' or 'lite' is sometimes more stable
                    search_gen = ddgs.text(query, region='wt-wt', safesearch='off', max_results=args.max_results)
                    
                    if search_gen:
                        for r in search_gen:
                            results.append({
                                "title": r.get('title'),
                                "href": r.get('href'),
                                "body": r.get('body')
                            })
                        break # Success
            except Exception as e:
                if attempt == 2:
                    raise e # Re-raise on last attempt
                time.sleep(2 ** attempt)

        output = {
            "status": "success",
            "platform": "research_agent",
            "query": query,
            "results": results,
            "count": len(results),
            "debug_metadata": {"attempts": attempt + 1}
        }
        
        print(json.dumps(output))
        sys.exit(0)

    except Exception as e:
        # Catch-all for any DDGS error
        print(json.dumps({"status": "error", "message": str(e)}))
        sys.exit(1)

if __name__ == "__main__":
    main()
