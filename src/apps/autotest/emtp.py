#!/usr/bin/python
# -*- coding: utf-8 -*-
import logging
import sys
import os.path
import time
import datetime
import shutil
from selenium import webdriver
from selenium.webdriver.support.ui import Select
from selenium.webdriver.chrome.options import Options

# Initialize Variables
username = 'usgond00'
dirusername = 'usmatr06'
password = 'Mad0xx12'
reportName = 'uservolume'
startDateValue = ''
endDateValue = ''
serviceLevelValue = '24'
filename = "/Email Topia 24"

#
file_path = f'C:/Users/{dirusername}/Desktop/Emailtopia Auto/temp'

# Production Directory
# new_file_path = '\\usbffnp22\share\TS\MGTADMIN\Yochum\Agent Metrics\ERMATS\--------'
# Local Directory
new_file_path = f'C:/Users/{dirusername}/Desktop/Emailtopia Auto/truth'
# Chrome Settings
options = Options()
options.add_experimental_option('prefs', {
    'download.default_directory': file_path,
    'download.prompt_for_download': False,
    'download.directory_upgrade': True,
    'safebrowsing.enabled': True,
    })
driver = webdriver.Chrome(chrome_options=options)
#----------------------------------------------------------------

driver.get(f'http://{username}:{password}@uschizwemt1003v.corporate.ingrammicro.com:8890/InitialServlet?report={reportName}')

def timeStamped(fname, fmt='{fname} %m%d%y.csv'):
    return datetime.datetime.now().strftime(fmt).format(fname=fname)

def getYesterday():
	global startDateValue
	global endDateValue
	# start_date = datetime.datetime.now() - datetime.timedelta(days=3)
	# end_date = start_date + datetime.timedelta(days=1)
	date = datetime.datetime.now() - datetime.timedelta(days=1)
	slash = '/'
	dateYesterday = date.strftime('%m{slash}%d{slash}%Y').format(slash=slash)
	startDateValue = dateYesterday
	endDateValue = dateYesterday
	# return dateYesterday

getYesterday()
##print(startDateValue)
##print(endDateValue)

startDate = driver.find_element_by_name('startdate')
startDate.clear()
startDate.send_keys(startDateValue)
endDate = driver.find_element_by_name('enddate')
endDate.clear()
endDate.send_keys(endDateValue)

select = Select(driver.find_element_by_name('ms_userid'))
optionz = select.options
for option in optionz:
    if "(Deleted)" not in option.text:
      select.select_by_visible_text(option.text)

# serviceLevel = driver.find_element_by_name('servicelevel')
# serviceLevel.clear()
# serviceLevel.send_keys(serviceLevelValue)


grouping = Select(driver.find_element_by_name('dategroupreport'))
grouping.select_by_visible_text('Day')

# btn = driver.find_element_by_name('submitButton')
# btn.click()

form = driver.find_element_by_name("params")
form.submit()

export = driver.find_element_by_xpath("//input[@value='Export...']")
export.click()

waiting = True
timeout = 0
while waiting:
    # time.sleep(60) #  Wait a minute 
    if 'No sites' in driver.page_source:
        waiting = False
    for fname in os.listdir(file_path):
        if fname.endswith('.csv'):
           waiting = False
           # Download complete, copy file and delete original 
           # CHANGE ORIGIN AND DESTINATION FILES TO MATCH YOUR SYSTEM
           origin = file_path + "/" + fname
           destination = timeStamped(new_file_path + filename) 
           shutil.move(origin, destination)
        break
#driver.implicitly_wait(60)
driver.close()
driver.quit()
