import json

with open(r'C:\Users\ASUS\.gemini\antigravity\brain\d70a13a1-bb99-4287-957f-344e10cc9786\.system_generated\logs\transcript_full.jsonl', 'r', encoding='utf-8') as f:
    for line in f:
        if 'identifikasi' in line.lower():
            try:
                obj = json.loads(line)
                if obj.get('source') in ['USER_EXPLICIT', 'USER']:
                    print(f"USER Step {obj.get('step_index')}: {obj.get('content')[:300]}")
            except:
                pass

