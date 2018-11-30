FTP
OPEN 172.19.201.13
tmpbxt
tabz11
hash	

set day=-1
echo >"%temp%\%~n0.vbs" s=DateAdd("d",%day%,now) : d=weekday(s)
echo>>"%temp%\%~n0.vbs" WScript.Echo year(s)^& right(100+month(s),2)^& right(100+day(s),2)
for /f %%a in ('cscript /nologo "%temp%\%~n0.vbs"') do set "result=%%a"
del "%temp%\%~n0.vbs"
set "YYYY=%result:~0,4%"
set "MM=%result:~4,2%"
set "DD=%result:~6,2%"
set "data=%yyyy%-%mm%-%dd%"



* US
get 'R.USF.RMP001.CS1' "C:\Users\usmatr06\Desktop\autotest\tso\DAILY_CUSTSERV_HEADER.XLS"
get 'R.USF.RMP001.CS2' "C:\Users\usmatr06\Desktop\autotest\tso\DAILY_SALES_HEADER.XLS"
get 'R.USF.RMP001.CS3' "C:\Users\usmatr06\Desktop\autotest\tso\DAILY_CUSTSERV_LINE.XLS"
get 'R.USF.RMP001.CS4' "C:\Users\usmatr06\Desktop\autotest\tso\DAILY_SALES_LINE.XLS"

* CANADA
get 'R.CAF.RMP001.CS1' "C:\Users\usmatr06\Desktop\autotest\tso\CANADA_CUSTSERVHEADER.XLS"
get 'R.CAF.RMP001.CS2' "C:\Users\usmatr06\Desktop\autotest\tso\CANADA_SALESHEADER.XLS"
get 'R.CAF.RMP001.CS3' "C:\Users\usmatr06\Desktop\autotest\tso\CANADA_CUSTSERVLINES.XLS"
get 'R.CAF.RMP001.CS4' "C:\Users\usmatr06\Desktop\autotest\tso\CANADA_SALESLINES.XLS"

quit
