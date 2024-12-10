@echo off
for /f "tokens=14" %%i in ('ipconfig ^| findstr /i "IPv4"') do echo http://%%i/
