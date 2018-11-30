@Echo On


rem set errorlevel = 0
rem ::WebDriver update
rem start /b webdriver-manager update
rem     If %errorlevel% neq 0 set &quot;job=webdriver-manager update&quot; exit/b &amp;goto err
rem     ::exit/b 
     
rem ::Start selenium  server
rem start /b webdriver-manager start
rem     If %errorlevel% neq 0 set &quot;job=webdriver-manager start&quot; exit/b &amp;goto err
     
::Change directory
cd C:\Users\usmatr06\Desktop\autotest
    If %errorlevel% neq 0 set &quot;job=changing directory&quot; exit/b &amp;goto err
 
::Start running tests
protractor conf.js
    If %errorlevel% neq 0 set &quot;job=protractor conf.js&quot; exit/b &amp;goto err
 
:err
    echo ERROR: %job% execution failed with error.
 
exit