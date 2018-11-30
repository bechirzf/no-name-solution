import logging
import sys
import os.path
import time
import datetime
import shutil #  This is just for file management later on
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select, WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.chrome.options import Options



# Pull all the samples from NWIS for each parameter code

# MISSING THE PART OF THE CODE THAT LOADS THE PARAMETER CODES
# THIS SCRIPT WILL NOT WORK WITHOUT SOME LIST OF CODES
# THE FOLLOWING IS JUST A SHORT EXCERPT SO YOU CAN RUN THIS AND 
# SEE HOW THE SCRIPT WORKS STEP BY STEP

# logging.basicConfig(stream=sys.stdout, level=logging.DEBUG)

file_path = 'C:/Users/usmatr06/Downloads/temp'
firefox_file_path = "C:\\Users\\usmatr06\\Downloads\\temp"
new_file_path = r"C:/Users/usmatr06/Downloads/truth"
options = Options()
options.add_experimental_option('prefs', {
    'download.default_directory': r"C:/Users/usmatr06/Downloads/temp",
    'download.prompt_for_download': False,
    'download.directory_upgrade': True,
    'safebrowsing.enabled': True,
    })

# To prevent download dialog
profile = webdriver.FirefoxProfile()
profile.set_preference('browser.download.folderList', 2) # custom location
profile.set_preference('browser.download.manager.showWhenStarting', False)
profile.set_preference('browser.download.dir', firefox_file_path)
profile.set_preference("browser.helperApps.neverAsk.openFile","text/html,text/csv,application/vnd.ms-excel")
profile.set_preference('browser.helperApps.neverAsk.saveToDisk', "text/html,text/csv,application/vnd.ms-excel")
driver = webdriver.Firefox(profile)
# driver = webdriver.Chrome(chrome_options=options)

def getUrl():
    date = datetime.datetime.now()
    dateToday = date.strftime('%m%%2F%d%%2F%Y')
    date -= datetime.timedelta(6*30)
    dateLastSixMonths = date.strftime('%m%%2F%d%%2F%Y')
    url = f"http://usmatr06:cloud@6969@im-informed/issues/search_process.asp?txtStartDate={dateLastSixMonths}&txtEndDate={dateToday}&cboIssueStatus=0&cboIssueType=3&txtCustomerNumber=&cboIssueResult=0&txtOwnerName=&txtRecipientName=&cboPriority=-1&cboRegion=-1&chkIssueID=on&chkIssueStatus=on&chkOwnerName=on&chkCustomerName=on&chkCreationDate=on&chkRegion=on&txtAPVendorName=&txtAPVendorNumber=&cboAPRep=0&txtVendorName=&txtVendorNumber=&txtSkuNumber=&chkClickAll=on&chkVendorName=on&chkVendorNumber=on&chkIMSKU=on&chkBuyerNameP=on&chkRejectionReason=on&chkPartResult=on&chkPartDisposition=on&chkPartQuantity=on&chkReturnReason=on&chkRMANumber=on&chkAmountEach=on&chkAuthPrice=on&chkAdjAuthPrice=on&chkCost=on&chkDefaultApprovalReductionPercent=on&chkSerialNumber=on&chkProductClass=on&chkCap=on&chkInvDate=on&chkNTID=on&chkDetails=on&txtTSReturnSkuNumber=&txtTSReturnTechID=&txtTSReturnSegment=&txtTSReturnVendor=&chkAmountEach=on&cboWSManager=0&txtWSSkuNumber=&cboRCNManager=0&txtRCNSkuNumber=&chkExcel=on&cboMaxRecords=-1"
    return url

def submitQuery(url):
    driver.get(url)
    return driver
def timeStamped(fname, fmt='{fname} %m%d%y.html'):
    return datetime.datetime.now().strftime(fmt).format(fname=fname)
# MAKE SURE TO ADJUST THE PATHS BELOW BEFORE RUNNING
driver = submitQuery(getUrl())
waiting = True
timeout = 0
while waiting:
    # time.sleep(60) #  Wait a minute 
    #if 'No sites' in driver.page_source:
        #waiting = False
    for fname in os.listdir(file_path):
        if fname.endswith('.xls'):
           waiting = False
           # Download complete, copy file and delete original 
           # CHANGE ORIGIN AND DESTINATION FILES TO MATCH YOUR SYSTEM
           origin = file_path + "/" + fname
           destination = timeStamped(new_file_path + "/IMI Raw") 
           shutil.move(origin, destination)
        break

driver.quit()
print("DONE")