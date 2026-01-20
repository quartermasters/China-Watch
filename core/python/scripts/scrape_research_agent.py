import argparse
import json
import sys
import time
import warnings
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
    last_error = "None"
    
    # Simple Retry Logic (Proven to work)
    for attempt in range(3):
        try:
            with DDGS() as ddgs:
                # Removed 'backend' arg as it caused regression.
                # 'wt-wt' is global. 'max_results' set to requested amount.
                search_gen = ddgs.text(query, region='wt-wt', safesearch='off', max_results=args.max_results)
                
                if search_gen:
                    for r in search_gen:
                        results.append({
                            "title": r.get('title'),
                            "href": r.get('href'),
                            "body": r.get('body')
                        })
                    
                    # If we got results, break the retry loop
                    if len(results) > 0:
                        break
        except Exception as e:
            last_error = str(e)
            time.sleep(2)

    output = {
        "status": "success", # Always return success so PHP can see the count
        "platform": "research_agent_v3",
        "query": query,
        "results": results,
        "count": len(results),
        "debug_error": last_error if len(results) == 0 else "None",
        "debug_metadata": {"attempts_made": attempt + 1}
    }
    
    print(json.dumps(output))
    sys.exit(0)

if __name__ == "__main__":
    main()
