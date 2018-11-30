import shutil #  This is just for file management later on
from subprocess import Popen
from datetime import datetime, timedelta

localUser = 'usmatr06'
username = 'tmprxm'
password = 'rex1130x'
ip = '172.19.201.13'
add = 7
localUser = 'usmatr06'
scriptDir = f'C:/Users/{localUser}/Desktop/Tasks/BEO/Script'
backUpDir  = f'C:/Users/{localUser}/Desktop/Tasks/BEO/Script/backup'
# new_file_path = r"C:/Users/usmatr06/Downloads/truth"

# Local | Testing
# reportingDir = f'C:\\Users\\{localUser}\\Desktop\\Tasks\\BEO\\Script\\temp\\'
# autovoidDir = f'C:\\Users\\{localUser}\\Desktop\\Tasks\\BEO\\Script\\temp\\Autovoids\\'
# Production
reportingDir = 'Z:\\VA\\Reporting\\'
autovoidDir = 'Z:\\VA\\Reporting\\Autovoids\\'
pupPostFile = "PUP807.TXT"
inrxresPostFile = "INRXRES3.TXT"
resvinvPostFile = "RESVINV.TXT"


def timeStamped(fname,days,fmt='{fname}%m-%d.TXT'):
    d = datetime.today() - timedelta(days=days)
    return d.strftime(fmt).format(fname=fname)

origin = scriptDir + "/BEOmonday.bat"
destination = timeStamped(backUpDir + "/BEOmonday",7,'{fname}%m-%d.bat') 
shutil.copy(origin, destination)


curl_file = open("BEOmonday.bat", "w")
curl_file.write('FTP\n')
curl_file.write('OPEN %s\n' % (ip))
curl_file.write('%s \n' % (username))
curl_file.write('%s \n' % (password))
curl_file.write('hash\n')

alist = []
# TODO : Add Condition Here
with open('BEOcustoMonday.bat') as f:
   for line in f:
        if 'RESVINV' in line:
       	  add = 5
       	else:
       	  add = 7
        if 'get' in line and '::' in line:
          a,b = line.split("::")
          alist.append(int(b)+add)


curl_file.write('get  \'D.USS.PUP807.ALLCSKU.PCFIL2\' \"%s%s\"\n' % (reportingDir,pupPostFile))
curl_file.write('get  \'P.USD.ICP653.INRXRES3.G%sV00\' \"%s%s\" ::%s\n' % (alist[0],reportingDir,inrxresPostFile,alist[0]))
curl_file.write('get  \'P.USD.ORP856.ORRDSSL.DSS.G%sV00\' \"%s%s\" ::%s\n' % (alist[1],reportingDir,resvinvPostFile,alist[1]))
curl_file.write('get  \'P.USD.ORP716.EXCELF.G%sV00\' \"%s\" ::%s\n' % (alist[2],timeStamped(autovoidDir,3),alist[2]))
curl_file.write('get  \'P.USD.ORP716.EXCELF.G%sV00\' \"%s\" ::%s\n' % (alist[3],timeStamped(autovoidDir,2),alist[3]))
curl_file.write('pause')
curl_file.close()

new_file = open("BEOcustoMonday.bat", "w")
# Change its content
with open('BEOmonday.bat') as f:
   for line in f:
       new_file.write(line)

new_file.close()

curl_file = open("ftpBEOmonday.bat", "w")
curl_file.write('cd "%s"\n' % (scriptDir))
curl_file.write('ftp -i -s:BEOmonday.bat\n')
curl_file.close()


p = Popen("ftpBEOmonday.bat", cwd=scriptDir)
stdout, stderr = p.communicate()