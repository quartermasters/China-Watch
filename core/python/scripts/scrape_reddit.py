import argparse
import json
import os
import sys
import praw
from dotenv import load_dotenv

# Load Environment Variables (API Keys)
# Assumes .env is in the project root or core/python
load_dotenv(os.path.join(os.path.dirname(__file__), '../../.env'))

def main():
    parser = argparse.ArgumentParser(description='Red Pulse Reddit Scraper')
    parser.add_argument('--url', type=str, required=True, help='Target Subreddit URL (e.g. reddit.com/r/China)')
    parser.add_argument('--source', type=str, required=True, help='Source Name')
    args = parser.parse_args()

    # Reddit API Credentials
    client_id = os.getenv('REDDIT_CLIENT_ID')
    client_secret = os.getenv('REDDIT_CLIENT_SECRET')
    user_agent = os.getenv('REDDIT_USER_AGENT', 'RedPulse/1.0')

    if not client_id or not client_secret:
        print(json.dumps({"status": "error", "message": "Missing REDDIT_CLIENT_ID or REDDIT_CLIENT_SECRET in .env"}))
        sys.exit(1)

    try:
        reddit = praw.Reddit(
            client_id=client_id,
            client_secret=client_secret,
            user_agent=user_agent
        )

        # Extract Subreddit Name from URL
        # e.g. https://www.reddit.com/r/China/ -> China
        subreddit_name = args.url.split('/r/')[-1].strip('/')
        
        # Scrape Top 5 Hot Posts
        posts = []
        subreddit = reddit.subreddit(subreddit_name)
        
        for post in subreddit.hot(limit=5):
            if post.stickied:
                continue

            posts.append({
                "title": post.title,
                "url": post.url,
                "permalink": f"https://reddit.com{post.permalink}",
                "score": post.score,
                "created_utc": post.created_utc,
                "selftext": post.selftext[:5000] # Limit text size
            })

        # Return structured JSON
        result = {
            "status": "success",
            "platform": "reddit",
            "subreddit": subreddit_name,
            "posts": posts,
            "count": len(posts)
        }
        
        print(json.dumps(result))
        sys.exit(0)

    except Exception as e:
        print(json.dumps({"status": "error", "message": str(e)}))
        sys.exit(1)

if __name__ == "__main__":
    main()
