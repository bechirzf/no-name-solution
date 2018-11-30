FTP
OPEN 172.19.201.13
tmprxm ::   Replace this with your TSO Credentials
rex1130x
hash	


get 'R.USD.SYP141' "Z:\VA\Reporting\Cancel Date\CANCEL DATE.TXT" ::   Make sure that you put the correct file path. <- This is a comment.
get 'D.USD.ICP741.PCFILE' "Z:\VA\Reporting\ICP741.TXT"
get 'R.USD.SYP141' "Z:\VA\Reporting\SYP141.TXT"
get 'R.USD.ORP749' "Z:\VA\Reporting\ORP749.TXT"
get 'R.USD.SYP102.RELEASE.ORDER' "Z:\VA\Reporting\US SH\RELEASE.TXT"

pause
