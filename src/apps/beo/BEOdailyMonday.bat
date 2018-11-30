FTP
OPEN 172.19.201.13
tmprxm  ::   Replace this with your TSO Credentials
rex1130x
hash	


get 'R.USF.SYP141' "Z:\VA\Reporting\Cancel Date\CANCEL DATE.TXT"  ::   Make sure that you put the correct file path. <- This is a comment.
get 'D.USS.ICP741.PCFILE' "Z:\VA\Reporting\ICP741.TXT"
get 'R.USF.SYP141' "Z:\VA\Reporting\SYP141.TXT"
get 'R.USW.ORP775' "Z:\VA\Reporting\ORP775.TXT"
get 'R.USF.ORP749' "Z:\VA\Reporting\ORP749.TXT"
get 'R.USF.SYP102.RELEASE.ORDER' "Z:\VA\Reporting\US SH\RELEASE.TXT"

pause
