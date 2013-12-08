#!/usr/bin/python
import re
import shlex
import MySQLdb
'''
r1 = re.compile(r'/s+(/d{1,4})<.*>')
match = re.match('Hello(.*)world',"HelloPython world")
match.group(1)
'''

# Read from TRWF2.DAT and generate dictionary: key as FID ID, value as FID name
dict={}

TRWFFH = open("TRWF2.DAT")
try:
    for line in TRWFFH.readlines():
        line = line.strip("\n")
    
        if re.match('^!', line) is None:
            arr = shlex.split(line)
            dict[arr[2]] = arr[0]


finally:
    TRWFFH.close()


# Store FID ID and FID name into mysql
conn = MySQLdb.connect(db='checkit',user='root')
cur = conn.cursor()
cur.execute('CREATE TABLE DisplayTemplate(fidid INT, fidname VARCHAR(15))')
for key in dict:
    cur.execute("INSERT INTO DisplayTemplate VALUES(key, dict[key])")
cur.close()
conn.commit()
conn.close()
