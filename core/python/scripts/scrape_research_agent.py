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

    unique_results = {}
    backends = ['api', 'html', 'lite'] # 'api' is standard, 'html' is legacy, 'lite' is no-js
    
    try:
        with DDGS() as ddgs:
            for backend in backends:
                if len(unique_results) >= args.max_results:
                    break

                try:
                    # Fetch slightly more than needed to account for dupes
                    remaining = args.max_results - len(unique_results)
                    # backend param might be deprecated in some versions, but region is key
                    # We iterate backends manually if the library supports it, 
                    # otherwise simply retrying with different regions/params helps.
                    # As of recent ddgs, 'backend' param is valid for text()
                    
                    results = ddgs.text(query, region='wt-wt', safesearch='off', backend=backend, max_results=remaining + 5)
                    
                    if results:
                        for r in results:
                            if len(unique_results) >= args.max_results:
                                break
                            
                            href = r.get('href')
                            if href and href not in unique_results:
                                unique_results[href] = {
                                    "title": r.get('title'),
                                    "href": href,
                                    "body": r.get('body')
                                }
                except Exception as e:
                    # Silently fail on one backend and try the next
                    continue
                
                # Small delay between backend switches
                time.sleep(1)

        final_results = list(unique_results.values())

        output = {
            "status": "success",
            "platform": "research_agent_v2",
            "query": query,
            "results": final_results,
            "count": len(final_results),
            "debug_metadata": {"method": "backend_rotation", "backends_used": len(backends)}
        }
        
        print(json.dumps(output))
        sys.exit(0)

    except Exception as e:
        print(json.dumps({"status": "error", "message": str(e)}))
        sys.exit(1)

if __name__ == "__main__":
    main()
