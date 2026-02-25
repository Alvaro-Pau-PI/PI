import urllib.request
import json
import os
import re
import time

def fetch_translation(text, source='ca', target='es'):
    if not text.strip():
        return text
    
    url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl={}&tl={}&dt=t&q={}".format(
        source, target, urllib.parse.quote(text)
    )
    
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req) as response:
            result = json.loads(response.read().decode())
            return ''.join([sentence[0] for sentence in result[0] if sentence[0]])
    except Exception as e:
        print(f"Error translating '{text[:30]}...': {e}")
        return text

def translate_markdown_file(filepath):
    print(f"Translating {filepath}...")
    with open(filepath, 'r', encoding='utf-8') as f:
        lines = f.readlines()
        
    translated_lines = []
    in_code_block = False
    
    for line in lines:
        stripped = line.strip()
        
        if stripped.startswith('```') or stripped.startswith('---'):
            if stripped.startswith('```'):
                in_code_block = not in_code_block
            translated_lines.append(line)
            continue
            
        if in_code_block or not stripped or stripped.startswith('<') or stripped.startswith('|') or stripped.startswith('<!--'):
            translated_lines.append(line)
            continue
            
        # Match markdown prefixes like `# `, `- `, `1. `, `> `, `* `, `  - `
        match = re.match(r'^(\s*(?:#+|\*|-|\d+\.|>)\s+)(.*)', line)
        if match:
            prefix = match.group(1)
            text_to_translate = match.group(2)
        else:
            # Handle lines starting with leading spaces but no list items, just translate them directly without matching prefix
            prefix = ''
            text_to_translate = line.rstrip('\n')
            
        if not text_to_translate.strip():
            translated_lines.append(line)
            continue
            
        translated_text = fetch_translation(text_to_translate)
        # Fix typical markdown link breaks: `] (url)` -> `](url)`
        translated_text = translated_text.replace('] (', '](')
        
        # If the original line had a newline, ensure the translated one does too
        if line.endswith('\n'):
            translated_lines.append(prefix + translated_text + '\n')
        else:
            translated_lines.append(prefix + translated_text)
            
        time.sleep(0.5)

    with open(filepath, 'w', encoding='utf-8') as f:
        f.writelines(translated_lines)

target_files = [
    'global_system.md', 'index.md', 'sprint1.md', 'sprint2.md', 'sprint3.md',
    'guia_contribucio.md', 'manual_desplegament.md', 'manual_despliegue_aws.md',
    'riscos_individuals.md', 'rols.md', 'sostenibilidad.md', 'arquitectura_aws.md', 'RISKS.md'
]

for file in target_files:
    path = os.path.join('docs', file)
    if os.path.exists(path):
        translate_markdown_file(path)
print("Finished!")
