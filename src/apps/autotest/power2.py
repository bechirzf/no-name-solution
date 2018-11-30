#!/usr/bin/python
# -*- coding: utf-8 -*-
import logging
import sys
import os.path
import time
import shutil
import datetime

logging.basicConfig(stream=sys.stdout, level=logging.DEBUG)

file_path = 'C:/Users/usgond00/Downloads'
new_file_path = r'G:/TS/MGTADMIN/Yochum/Agent Metrics/ERMATS/Open Claims'
def timeStamped(fname, fmt='{fname} %m%d%y.html'):
    return datetime.datetime.now().strftime(fmt).format(fname=fname)
for fname in os.listdir(file_path):
    if fname.endswith('.xls'):
        shutil.move(file_path + "/" + fname,timeStamped(new_file_path + "/Open Claims"))
       # os.rename(file_path + "/" + fname, new_file_path + "/power.html")
        break
