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
new_file_path = r"C:/Users/usmatr06/Downloads/truth"
url = 'http://impower/webs/GridView.aspx'
options = Options()
options.add_experimental_option('prefs', {
    'download.default_directory': r"C:/Users/usmatr06/Downloads/temp",
    'download.prompt_for_download': False,
    'download.directory_upgrade': True,
    'safebrowsing.enabled': True,
    })

driver = webdriver.Chrome(chrome_options=options)


def submitQuery(url):
    driver.get(url)
    assert 'IMPower' in driver.title
    select = Select(driver.find_element_by_id('mainFilter'))
    select.select_by_value('Open')
    btn = driver.find_element_by_id('ExcelExport')
    btn.click()
    return driver
def timeStamped(fname, fmt='{fname} %m%d%y.html'):
    return datetime.datetime.now().strftime(fmt).format(fname=fname)
# MAKE SURE TO ADJUST THE PATHS BELOW BEFORE RUNNING
driver = submitQuery(url)
waiting = True
timeout = 0
while waiting:
    # time.sleep(60) #  Wait a minute 
    if 'No sites' in driver.page_source:
        waiting = False
    for fname in os.listdir(file_path):
        if fname.endswith('.xls'):
           waiting = False
           # Download complete, copy file and delete original 
           # CHANGE ORIGIN AND DESTINATION FILES TO MATCH YOUR SYSTEM
           origin = file_path + "/" + fname
           destination = timeStamped(new_file_path + "/Open Claims") 
           shutil.move(origin, destination)
        break

driver.close()
print("DONE")