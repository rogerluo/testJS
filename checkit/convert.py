#!/opt/python-2.7.6/bin/python
import re
import sys
import MySQLdb

MCUlist={}
ADHlist={}
EEDlist={}


# Read from TRWF2.DAT and generate dictionary: key as FID ID, value as FID name
def DumpRT(file):
	list=[]

	TRWFFH = open(file)
	try:
		for line in TRWFFH.readlines():
			line = line.strip("\n")
			if re.match('^!', line) is None:			
				r1 = re.compile('^(\S+)\s*".+"\s+(-?\d{1,5})\s+\S+\s+\S+\s+\d{1,3}\s*')
				m = r1.match(line)
				list.append(m.groups(0))   # m.groups(0)=[FIDName,FIDID]
	finally:
		TRWFFH.close()

	#Store FID ID and FID name into mysql
	conn = MySQLdb.connect(db='checkit',user='root', passwd='intel@123')
	cur = conn.cursor()
	cur.execute('DROP TABLE IF EXISTS RecordTemplate')
	cur.execute('CREATE TABLE RecordTemplate(fidname VARCHAR(20), fidid VARCHAR(5))')
	cur.executemany("INSERT INTO RecordTemplate VALUES(%s, %s)", list)
	cur.close()
	conn.commit()
	conn.close()

# Get FID ID:FID value from MCU dump
def CheckRIC_MCU(dbfile):
		# Define regex for MCU
		r = re.compile('^#(\d{1,5})[^=]+=\s([^\(]+)\(.*')
		
		FH = open(dbfile)
		try:
				for line in FH.readlines():
						line = line.strip()
						#line = line.lstrip('#')

						if re.match('^#',line) is not None:
								m = r.match(line)
								MCUlist[m.groups(0)[0]] = m.groups(0)[1].replace('\t','').strip()

		finally:
				FH.close()

# Get FID ID:FID value from ADH dump
def CheckRIC_ADH(dbfile):
		# Define regex for ADH
		r = re.compile('^(\d{1,5})<[^>\)]+\)>(.*)')
		
		FH = open(dbfile)
		try:
				for line in FH.readlines():
						line = line.strip()

						if re.match('^\d{1,5}<',line) is not None:
								m = r.match(line)
								#print m.groups(0),'...'
								# Need to check the data type here
								m_uint = re.match('^\(\d{1,2}\)\s(.*)',m.groups(0)[1])
								m_buff = re.match('^\"([^\"]+)\"',m.groups(0)[1])
								m_real = re.match('^(\S+)\s\(\d{1,2}\)',m.groups(0)[1])
								m_date = re.match('^(\d{2}\/\d{2}\/\d{4})\s*',m.groups(0)[1])
								
								if m_uint is not None:
										ADHlist[m.groups(0)[0]] = m_uint.groups(0)[0]
								elif m_buff is not None:
										ADHlist[m.groups(0)[0]] = m_buff.groups(0)[0]
								elif m_real is not None:
										ADHlist[m.groups(0)[0]] = m_real.groups(0)[0]
								else:
										ADHlist[m.groups(0)[0]] = m_date.groups(0)[0]

		finally:
				FH.close()		  

# Get FID ID:FID value from EED dump
def CheckRIC_EED(dbfile):
		# Define regex for EED
		r = re.compile('^(\d{1,5})<[^>\)]+\)>(.*)')
		
		FH = open(dbfile)
		try:
				for line in FH.readlines():
						line = line.strip()

						if re.match('^\d{1,5}<',line) is not None:
								m = r.match(line)
								#print m.groups(0),'...'
								# Need to check the data type here
								m_uint = re.match('^\(\d{1,2}\)\s(.*)',m.groups(0)[1])
								m_buff = re.match('^\"([^\"]+)\"',m.groups(0)[1])
								m_real = re.match('^(\S+)\s\(\d{1,2}\)',m.groups(0)[1])
								m_date = re.match('^(\d{2}\/\d{2}\/\d{4})\s*',m.groups(0)[1])
								
								if m_uint is not None:
										EEDlist[m.groups(0)[0]] = m_uint.groups(0)[0]
								elif m_buff is not None:
										EEDlist[m.groups(0)[0]] = m_buff.groups(0)[0]
								elif m_real is not None:
										EEDlist[m.groups(0)[0]] = m_real.groups(0)[0]
								else:
										EEDlist[m.groups(0)[0]] = m_date.groups(0)[0]

		finally:
				FH.close()		  

def TestSingle(argvs):
	print strlen(argvs)
	for argv in argvs:
		print argv

def main(argvs):
		if(len(argvs) == 1):
			print 'invalid arguments'
			return
		# DumpRT(r'./data/TRWF2.DAT')
		
		# check which ricdump
		lowricdump = argvs[1].lower()
		if (lowricdump.find('mcu') != -1):
			CheckRIC_MCU(argvs[1])
			# print str(len(MCUlist))
			# print str(len(sorted(MCUlist.iteritems(), key = lambda kv:int(kv[0]))))
			for elem in sorted(MCUlist.iteritems(), key = lambda kv:int(kv[0])):
				print "%s,%s" %(elem[0], elem[1].strip())
		elif (lowricdump.find('adh') != -1):
			CheckRIC_ADH(argvs[1])
			# print str(len(ADHlist))
			# print str(len(sorted(ADHlist.iteritems(), key = lambda kv:int(kv[0]))))
			for elem in sorted(ADHlist.iteritems(), key = lambda kv:int(kv[0])):
				print "%s,%s" %(elem[0], elem[1].strip())
		elif (lowricdump.find('eed') != -1):
			CheckRIC_EED(argvs[1])
			# print str(len(EEDlist))
			# print str(len(sorted(EEDlist.iteritems(), key = lambda kv:int(kv[0]))))
			for elem in sorted(EEDlist.iteritems(), key = lambda kv:int(kv[0])):
				print "%s,%s" %(elem[0], elem[1].strip())
		else:
			print "dump file should contain key word from 'mcu', 'adh' or 'eed'."
			return
			
		# for elem in sorted(MCUlist.iteritems(), key = lambda kv:int(kv[0])):
			# print "%s, %s" %(elem[0], elem[1])
		# for key in MCUlist.keys():
				# print "%s,%s" %(key,MCUlist[key])
		

		# CheckRIC_ADH(r'./data/ADH_RIC.dump')
		# for key in ADHlist.keys():
				# print "%s,%s...." %(key,ADHlist[key])
		
		# CheckRIC_EED(r'./data/EED_RIC.dump')
		# for key in EEDlist.keys():
				# print "%s,%s...." %(key,EEDlist[key])

if __name__ == '__main__':
	main(sys.argv)
