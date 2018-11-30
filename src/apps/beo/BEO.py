import shutil #  This is just for file management later on
from subprocess import Popen
from datetime import datetime, timedelta

username = 'tmprxm'
password = 'rex1130x'
ip = '172.19.201.13'
add = 1
localUser = 'usmatr06'
scriptDir = f'C:/Users/{localUser}/Desktop/Tasks/BEO/Script'
backUpDir  = f'C:/Users/{localUser}/Desktop/Tasks/BEO/Script/backup'
if datetime.today().weekday() == 1:
	add = 4
# new_file_path = r"C:/Users/usmatr06/Downloads/truth"

# Local | Testing
# reportingDir = 'C:\\Users\\usmatr06\\Desktop\\Tasks\\BEO\\Script\\temp\\'
# autovoidDir = 'C:\\Users\\usmatr06\\Desktop\\Tasks\\BEO\\Script\\temp\\Autovoids\\'
# Production
reportingDir = 'Z:\\VA\\Reporting\\'
autovoidDir = 'Z:\\VA\\Reporting\\Autovoids\\'
backUpDir  = f'C:/Users/{localUser}/Desktop/Tasks/BEO/Script/backup'
pupPostFile = "PUP807.TXT"
inrxresPostFile = "INRXRES3.TXT"
resvinvPostFile = "RESVINV.TXT"


def timeStamped(fname,fmt='{fname}%m-%d.TXT'):
    d = datetime.today() - timedelta(days=1)
    return d.strftime(fmt).format(fname=fname)

origin = scriptDir + "/BEO.bat"
destination = timeStamped(backUpDir + "/BEO",'{fname}%m-%d.bat') 
shutil.copy(origin, destination)


curl_file = open("BEO.bat", "w")
curl_file.write('FTP\n')
curl_file.write('OPEN %s\n' % (ip))
curl_file.write('%s \n' % (username))
curl_file.write('%s \n' % (password))
curl_file.write('hash\n')

alist = []
# TODO : Add Condition Here
with open('BEOcustom.bat') as f:
   for line in f:
       if 'get' in line and '::' in line:
          a,b = line.split("::")
          alist.append(int(b)+add)


curl_file.write('get  \'D.USD.PUP807.ALLCSKU.PCFIL2.G%sV00\' \"%s%s\" ::%s\n' % (alist[0],reportingDir,pupPostFile,alist[0]))
curl_file.write('get  \'P.USD.ICP653.INRXRES3.G%sV00\' \"%s%s\" ::%s\n' % (alist[1],reportingDir,inrxresPostFile,alist[1]))
curl_file.write('get  \'P.USD.ORP856.ORRDSSL.DSS.G%sV00\' \"%s%s\" ::%s\n' % (alist[2],reportingDir,resvinvPostFile,alist[2]))
curl_file.write('get  \'P.USD.ORP716.EXCELF.G%sV00\' \"%s\" ::%s\n' % (alist[3],timeStamped(autovoidDir),alist[3]))
curl_file.write('pause')
curl_file.close()

new_file = open("BEOcustom.bat", "w")
# Change its content
with open('BEO.bat') as f:
   for line in f:
       new_file.write(line)

new_file.close()

curl_file = open("ftpBEO.bat", "w")
curl_file.write('cd "C:/Users/usmatr06/Desktop/Tasks/BEO/Script"\n')
curl_file.write('ftp -i -s:BEO.bat\n')
curl_file.close()


p = Popen("ftpBEO.bat", cwd=r"C:/Users/usmatr06/Desktop/Tasks/BEO/Script")
stdout, stderr = p.communicate()