#C:\Users\usmatr06\Downloads\curl-7.60.0-win64-mingw\curl-7.60.0-win64-mingw\bin
from subprocess import Popen
import datetime

username = 'usmatr06'
password = ''
new_file_path = r"C:/Users/usmatr06/Downloads/truth"

def getUrl():
    date = datetime.datetime.now()
    dateToday = date.strftime('%m%%%%2F%d%%%%2F%Y')
    date -= datetime.timedelta(6*30)
    dateLastSixMonths = date.strftime('%m%%%%2F%d%%%%2F%Y')
    url = f"http://im-informed/issues/search_process.asp?txtStartDate={dateLastSixMonths}&txtEndDate={dateToday}&cboIssueStatus=0&cboIssueType=3&txtCustomerNumber=&cboIssueResult=0&txtOwnerName=&txtRecipientName=&cboPriority=-1&cboRegion=-1&chkIssueID=on&chkIssueStatus=on&chkOwnerName=on&chkCustomerName=on&chkCreationDate=on&chkRegion=on&txtAPVendorName=&txtAPVendorNumber=&cboAPRep=0&txtVendorName=&txtVendorNumber=&txtSkuNumber=&chkClickAll=on&chkVendorName=on&chkVendorNumber=on&chkIMSKU=on&chkBuyerNameP=on&chkRejectionReason=on&chkPartResult=on&chkPartDisposition=on&chkPartQuantity=on&chkReturnReason=on&chkRMANumber=on&chkAmountEach=on&chkAuthPrice=on&chkAdjAuthPrice=on&chkCost=on&chkDefaultApprovalReductionPercent=on&chkSerialNumber=on&chkProductClass=on&chkCap=on&chkInvDate=on&chkNTID=on&chkDetails=on&txtTSReturnSkuNumber=&txtTSReturnTechID=&txtTSReturnSegment=&txtTSReturnVendor=&chkAmountEach=on&cboWSManager=0&txtWSSkuNumber=&cboRCNManager=0&txtRCNSkuNumber=&chkExcel=on&cboMaxRecords=-1"
    #print(url)
    return url

def timeStamped(fname, fmt='{fname} %m%d%y.html'):
    return datetime.datetime.now().strftime(fmt).format(fname=fname)

destination = timeStamped(new_file_path + "/IMI Raw")

curl_file = open("curl-imi.bat", "w")
curl_file.write('curl -X GET "%s" -o "%s" -H "authorization: Basic dXNnb25kMDA6TWFkMHh4MTE=" \npause' % (getUrl(),destination))
#curl_file.write('curl --user %s:%s -X GET "%s" -o "%s" \npause' % (username,password,getUrl(),destination))
curl_file.close()

p = Popen("curl-imi.bat", cwd=r"C:/Users/usmatr06/Desktop/autotest")
stdout, stderr = p.communicate()