import json
import os

locales = [
    'src/locales/es.json',
    'src/locales/en.json',
    'src/locales/ca.json'
]

def escape_at(obj):
    if isinstance(obj, dict):
        return {k: escape_at(v) for k, v in obj.items()}
    elif isinstance(obj, str):
        return obj.replace('@', "{'@'}")
    return obj

for path in locales:
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as f:
            data = json.load(f)
        
        data = escape_at(data)
        
        with open(path, 'w', encoding='utf-8') as f:
            json.dump(data, f, ensure_ascii=False, indent=2)

print("Locales fixed")
