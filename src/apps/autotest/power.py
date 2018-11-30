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

file_path = 'C:/Users/usgond00/Downloads'
new_file_path = '\\usbffnp22\share\TS\MGTADMIN\Yochum\Agent Metrics\ERMATS\Open Claims'
options = Options()
options.add_experimental_option('prefs', {
    'download.default_directory': r"C:/Users/usgond00/Downloads",
    'download.prompt_for_download': False,
    'download.directory_upgrade': True,
    'safebrowsing.enabled': True,
    })

driver = webdriver.Chrome(chrome_options=options)
driver.get('http://impower/webs/GridView.aspx')
assert 'IMPower' in driver.title

select = Select(driver.find_element_by_id('mainFilter'))

# select by visible text - select.select_by_visible_text('All Open Claims')
# select by value

select.select_by_value('Open')

btn = driver.find_element_by_id('ExcelExport')
btn.click()

# for fname in os.listdir(file_path):
   # if fname.endswith('.xls'):
    #    os.rename(file_path + "/" + fname, new_file_path + "/power.html")
     #   break
# driver.implicitly_wait(60)
# driver.close()
