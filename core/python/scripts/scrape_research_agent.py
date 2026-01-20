import argparse
import json
import sys
from duckduckgo_search import DDGS

def main():
    parser = argparse.ArgumentParser(description='Red Pulse Research Agent')
    # Make query optional, so we can fallback to 'url'
    parser.add_argument('--query', type=str, required=False, help='Search Query')
    parser.add_argument('--max_results', type=int, default=10, help='Max Results')
    parser.add_argument('--source', type=str, default='research_agent', help='Source Name')
    # The PHP Wrapper sends the "Query" as the "--url" argument
    parser.add_argument('--url', type=str, required=False, help='Used as Query in this context')
    
    args = parser.parse_args()

    # Normalize Query
    query = args.query
    if not query and args.url:
        query = args.url
    
    if not query:
        print(json.dumps({"status": "error", "message": "No query provided via --query or --url"}))
        sys.exit(1)

    try:
        results = []
        with DDGS() as ddgs:
            # Use 'text' for standard web search
            search_gen = ddgs.text(query, max_results=args.max_results)
            for r in search_gen:
                results.append({
                    "title": r.get('title'),
                    "href": r.get('href'),
                    "body": r.get('body')
                })

        output = {
            "status": "success",
            "platform": "research_agent", # Matches the generic key
            "query": query,
            "results": results,
            "count": len(results)
        }
        
        print(json.dumps(output))
        sys.exit(0)

    except Exception as e:
        print(json.dumps({"status": "error", "message": str(e)}))
        sys.exit(1)

if __name__ == "__main__":
    main()
