@echo off
cd /d "C:\laragon\www\family-bazar"
"C:\laragon\bin\php\php-8.3.19-nts-Win32-vs16-x64\php.exe" artisan schedule:run
