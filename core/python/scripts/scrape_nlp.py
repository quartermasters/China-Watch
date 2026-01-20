import argparse
import json
import sys
import spacy
import warnings

# Suppress spacy/cuda warnings if any
warnings.filterwarnings("ignore")

def main():
    parser = argparse.ArgumentParser(description='Red Pulse NLP Entity Extractor')
    parser.add_argument('--text', type=str, required=False, help='Text to analyze')
    parser.add_argument('--url', type=str, required=False, help='Text to analyze (via wrapper)')
    parser.add_argument('--file', type=str, required=False, help='Path to text file')
    parser.add_argument('--source', type=str, required=False, help='Source Name (Automated by Wrapper)')
    
    args = parser.parse_args()

    content = ""
    if args.text:
        content = args.text
    elif args.url:
        content = args.url
    elif args.file:
        try:
            with open(args.file, 'r', encoding='utf-8') as f:
                content = f.read()
        except Exception as e:
            print(json.dumps({"status": "error", "message": f"File read error: {str(e)}"}))
            sys.exit(1)

    if not content:
        # Check stdin as last resort
        if not sys.stdin.isatty():
            content = sys.stdin.read()

    if not content or len(content.strip()) < 10:
        print(json.dumps({"status": "success", "entities": [], "count": 0}))
        sys.exit(0)

    try:
        # Load the small English model (fast, good enough for high-level entities)
        try:
            nlp = spacy.load("en_core_web_sm")
        except OSError:
            print(json.dumps({
                "status": "error", 
                "message": "Model 'en_core_web_sm' not found. Run: python3 -m spacy download en_core_web_sm"
            }))
            sys.exit(1)

        doc = nlp(content)
        
        entities = []
        seen = set()
        
        # Target labels: ORG (Company), PERSON (People), GPE (Location), NORP (Groups/Politics)
        target_labels = ['ORG', 'PERSON', 'GPE', 'NORP', 'PRODUCT', 'EVENT']
        
        for ent in doc.ents:
            if ent.label_ in target_labels:
                name = ent.text.strip().replace("\n", " ")
                key = f"{name.lower()}|{ent.label_}"
                
                if key not in seen and len(name) > 1:
                    entities.append({
                        "name": name,
                        "type": ent.label_
                    })
                    seen.add(key)

        print(json.dumps({
            "status": "success",
            "entities": entities,
            "count": len(entities)
        }))
        sys.exit(0)

    except Exception as e:
        print(json.dumps({"status": "error", "message": str(e)}))
        sys.exit(1)

if __name__ == "__main__":
    main()
