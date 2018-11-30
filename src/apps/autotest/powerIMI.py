#!/usr/bin/python
# -*- coding: utf-8 -*-
import logging
import sys
import os.path
import time
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select, WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.chrome.options import Options

logging.basicConfig(stream=sys.stdout, level=logging.DEBUG)

file_path = 'C:/Users/usmatr06/Downloads'
new_file_path = '\\usbffnp22\share\TS\MGTADMIN\Yochum\Agent Metrics\ERMATS\Open Claims'
options = Options()
options.add_experimental_option('prefs', {
    'download.default_directory': r"C:/Users/usmatr06/Downloads/temp",
    'download.prompt_for_download': False,
    'download.directory_upgrade': True,
    'safebrowsing.enabled': True,
    })

driver = webdriver.Chrome(chrome_options=options)
driver.get("http://im-informed/issues/search_process.asp?txtStartDate=4%2F26%2F2018&txtEndDate=10%2F23%2F2018&cboIssueStatus=0&cboIssueType=3&txtCustomerNumber=&cboIssueResult=0&txtOwnerName=&txtRecipientName=&cboPriority=-1&cboRegion=-1&chkIssueID=on&chkIssueStatus=on&chkOwnerName=on&chkCustomerName=on&chkCreationDate=on&chkRegion=on&txtAPVendorName=&txtAPVendorNumber=&cboAPRep=0&txtVendorName=&txtVendorNumber=&txtSkuNumber=&chkClickAll=on&chkVendorName=on&chkVendorNumber=on&chkIMSKU=on&chkBuyerNameP=on&chkRejectionReason=on&chkPartResult=on&chkPartDisposition=on&chkPartQuantity=on&chkReturnReason=on&chkRMANumber=on&chkAmountEach=on&chkAuthPrice=on&chkAdjAuthPrice=on&chkCost=on&chkDefaultApprovalReductionPercent=on&chkSerialNumber=on&chkProductClass=on&chkCap=on&chkInvDate=on&chkNTID=on&chkDetails=on&txtTSReturnSkuNumber=&txtTSReturnTechID=&txtTSReturnSegment=&txtTSReturnVendor=&chkAmountEach=on&cboWSManager=0&txtWSSkuNumber=&cboRCNManager=0&txtRCNSkuNumber=&chkExcel=on&cboMaxRecords=-1")


# for fname in os.listdir(file_path):
   # if fname.endswith('.xls'):
    #    os.rename(file_path + "/" + fname, new_file_path + "/power.html")
     #   break
# driver.implicitly_wait(60)
# driver.close()
