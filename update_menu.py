import sys
import os

file_path = r'c:\xampp\htdocs\hello2\resources\views\layouts\menu.blade.php'

# Read the file
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace remaining old nav-link patterns
content = content.replace('class="nav-link d-flex align-items-center py-3"', 'class="nav-link d-flex align-items-center py-2 px-3 rounded-3 mb-2 transition-all"')
content = content.replace('class="nav-link  d-flex align-items-center py-3"', 'class="nav-link d-flex align-items-center py-2 px-3 rounded-3 mb-2 transition-all"')

# Add fw-500 to menu titles that don't have it
content = content.replace('<span class="aside-menu-title">', '<span class="aside-menu-title fw-500">')
content = content.replace('<span class="aside-menu-title fw-500 fw-500">', '<span class="aside-menu-title fw-500">')

# Write back
with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print('Menu styling updated successfully!')
