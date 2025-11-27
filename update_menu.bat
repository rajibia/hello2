@echo off
setlocal enabledelayedexpansion
cd /d c:\xampp\htdocs\hello2

REM Use PowerShell to replace the content
powershell -NoProfile -Command ^
  "$content = [System.IO.File]::ReadAllText('resources\views\layouts\menu.blade.php', [System.Text.Encoding]::UTF8); ^
  $content = $content -replace 'class=\"nav-link d-flex align-items-center py-3\"', 'class=\"nav-link d-flex align-items-center py-2 px-3 rounded-3 mb-2 transition-all\"'; ^
  $content = $content -replace 'class=\"nav-link  d-flex align-items-center py-3\"', 'class=\"nav-link d-flex align-items-center py-2 px-3 rounded-3 mb-2 transition-all\"'; ^
  $content = $content -replace '<span class=\"aside-menu-title\">', '<span class=\"aside-menu-title fw-500\">'; ^
  $content = $content -replace '<span class=\"aside-menu-title fw-500 fw-500\">', '<span class=\"aside-menu-title fw-500\">'; ^
  [System.IO.File]::WriteAllText('resources\views\layouts\menu.blade.php', $content, [System.Text.Encoding]::UTF8)"

echo Menu updated successfully!
