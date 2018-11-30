import sys
import requests
import pandas as pd
from bs4 import BeautifulSoup

print('Python version ' + sys.version)
print('Pandas version ' + pd.__version__)
print('Requests version ' + requests.__version__)


# username = 'usmatr06'
# password = ''
# new_file_path = r"C:/Users/usmatr06/Downloads/truth/Email Topia 110218.html"

url = "https://www.fantasypros.com/nfl/reports/leaders/qb.php?year=2015"
response = requests.get(url)
response.text

# def getUrl():
#     date = datetime.datetime.now()
#     dateToday = date.strftime('%m%%%%2F%d%%%%2F%Y')
#     date -= datetime.timedelta(6*30)
#     dateLastSixMonths = date.strftime('%m%%%%2F%d%%%%2F%Y')
#     url = f"http://uschizwemt1003v.corporate.ingrammicro.com:8890/ReportServlet"
#     #print(url)
#     return url

# def timeStamped(fname, fmt='{fname} %m%d%y.html'):
#     return datetime.datetime.now().strftime(fmt).format(fname=fname)

# def getFormData():
#     date = datetime.datetime.now() - datetime.timedelta(days=1)
#     dateYesterday = date.strftime('%m%%%%2F%d%%%%2F%Y')
#     data = f"dategroupreport=Day&enddate={dateYesterday}&groupid=&ms_groupid=&report=dailygroupperformance&servicelevel=24&sl_unit=h&startdate={dateYesterday}&submitButton=%2B%2B%2B%2B%2BOK%2B%2B%2B%2B%2B"
#     #print(url)
#     return data

# destination = timeStamped(new_file_path + "/Email Topia")

# curl_file = open("curl-emailtopia.bat", "w")
# curl_file.write('curl  -X POST "%s" -o "%s" -H "authorization: Basic dXNnb25kMDA6TWFkMHh4MTI=" -H "content-type: application/x-www-form-urlencoded; " -d "%s" \npause' % (getUrl(),destination,getFormData()))
# #curl_file.write('curl --user %s:%s -X POST "%s" -o "%s" -H "content-type: application/x-www-form-urlencoded;" -d "%s" \npause' % (username,password,getUrl(),destination,getFormData()))
# curl_file.close()

# p = Popen("curl-emailtopia.bat", cwd=r"C:/Users/usmatr06/Desktop/autotest")
# stdout, stderr = p.communicate()