import json

with open(r'C:\Users\ASUS\.gemini\antigravity\brain\d70a13a1-bb99-4287-957f-344e10cc9786\.system_generated\logs\transcript_full.jsonl', 'r', encoding='utf-8') as f:
    for line in f:
        try:
            obj = json.loads(line)
            if obj.get('step_index') in [3866, 3871, 3872]:
                print(f"=== STEP {obj.get('step_index')} ===")
                print(json.dumps(obj, indent=2)[:1500])
        except Exception as e:
            pass

