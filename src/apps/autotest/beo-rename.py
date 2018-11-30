import logging
import sys
import os.path
import time
import shutil
from datetime import datetime, timedelta

logging.basicConfig(stream=sys.stdout, level=logging.DEBUG)

cancel_file_path = '//pimawnas1002/gtm-na/PSTS/ECS-BEO/VA/Reporting/Cancel Date'
release_file_path = '//pimawnas1002/gtm-na/PSTS/ECS-BEO/VA/Reporting/US SH'
new_file_path = r'//pimawnas1002/gtm-na/PSTS/ECS-BEO/VA/Reporting'
def timeStamped(fname, fmt='{fname}%m-%d.TXT'):
    days_to_subtract = 1
    if datetime.today().weekday() == 0:
       days_to_subtract = 3
    d = datetime.today() - timedelta(days=days_to_subtract)
    return d.strftime(fmt).format(fname=fname)

for fname in os.listdir(cancel_file_path):
    if fname.startswith('CANCEL DATE'):
       	shutil.move(cancel_file_path + "/" + fname,timeStamped(new_file_path + "/Cancel Date/"))
       	break
for fname in os.listdir(release_file_path):
    if fname.startswith('RELEASE'):
       	shutil.move(release_file_path + "/" + fname,timeStamped(new_file_path + "/US SH/"))
       	break
