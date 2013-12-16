import re
line = "		#1036	GV6 TEXT	= BID_HI	(TRWF_MF_RMTES_STRING - Length=6)"
#line = line.strip()
print line
r = re.compile('^\t\t#(\d{1,5})[^=]+=\s([^\(]+)\(.*')
m = r.match(line)
print m.groups(0)[2]