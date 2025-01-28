# Setup

## Install XAMPP Control Panel
- Download from https://www.apachefriends.org/download.html

## Clone The Repository
- Go to the XAMPP directory and navigate to htdocs
- Open a command prompt
- Enter the following commands:
```
git clone https://github.com/LuisMav23/payroll-fingerprint-facial-gesture-php-system.git 
```

## Start The Server
- Start the Apache and MySQL server from the XAMPP Control Panel\
- Create a new database named "payroll_mdb" 
- Go to the MySQL Admin Page and Run the SQL commands from the "DATABASE FILE/payroll_mdb.sql" file

## Set up the Python Application
- Make sure tht you have the python 3.11 version (https://www.python.org/downloads/release/python-3110/)
- Download CMake (https://cmake.org/download/)
- Download the C++ Build Tools from Visual Studio Build Tools (https://visualstudio.microsoft.com/downloads/?q=build+tools)
- Download the Windows 10/11 SDK and the C++ CMake Tools for Windows from Visual Studio Build Tools
- Enter the following commands:
```
py -m pip install -r requirements.txt
```