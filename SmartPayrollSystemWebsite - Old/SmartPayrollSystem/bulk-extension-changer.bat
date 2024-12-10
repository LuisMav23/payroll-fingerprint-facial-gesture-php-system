@echo off
setlocal enabledelayedexpansion

rem Prompt user to input old extension
set /p old_extension=Enter the old extension (without dot): 

rem Prompt user to input new extension
set /p new_extension=Enter the new extension (without dot): 

rem Iterate through all files with the old extension recursively in current directory and its subfolders
for /r %%i in (*.%old_extension%) do (
    rem Extract file name and directory of each file
    set "file=%%~ni"
    set "dir=%%~dpi"

    rem Rename the file with the new extension
    ren "!dir!!file!.%old_extension%" "!file!.%new_extension%"
)

echo Conversion complete.
