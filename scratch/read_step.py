import json

with open(r'C:\Users\ASUS\.gemini\antigravity\brain\d70a13a1-bb99-4287-957f-344e10cc9786\.system_generated\logs\transcript.jsonl', 'r', encoding='utf-8') as f:
    for line in f:
        try:
            obj = json.loads(line)
            idx = obj.get('step_index')
            if idx and 3858 <= idx <= 3870:
                print(f"=== Step {idx} ({obj.get('source')}, {obj.get('type')}) ===")
                content = str(obj.get('content', ''))
                thinking = str(obj.get('thinking', ''))
                if thinking:
                    print("THINKING:", thinking[:200])
                if content:
                    print("CONTENT:", content[:200])
                if obj.get('tool_calls'):
                    for tc in obj['tool_calls']:
                        print("TOOL CALL:", tc.get('name'), tc.get('arguments'))
        except Exception as e:
            pass

