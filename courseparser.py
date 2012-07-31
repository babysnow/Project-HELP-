import re
import string
import urllib2
import sys
import MySQLdb as mdb
from xml.dom import minidom
from datetime import date

con = mdb.connect('localhost', 'dhe6', 
        'dhe6', 'dhe6');
    


#let's automatically grab MULTIPLE?
#abbrevlist = ['CS', 'ECE']

if len(sys.argv) > 3:
	abbrev = sys.argv[1]
	season = sys.argv[2]
	year = sys.argv[3]

#abbrev = raw_input("CS or ECE or what? : ")
#year = raw_input("what year? ")
#season = raw_input("what season? ")
abbrevlist = [abbrev]
for abbrev in abbrevlist:
	'''
	thisday = date.today().timetuple()
	year = thisday[0]
	month = thisday[1]
	day = thisday[2]
	if month > 8 or (month == 8 and day > 5):
		season = 'fall'
	if month < 6 or (month == 6 and day < 12):
		season = 'spring'
	else:
		season = 'summer'
	'''
	URL = 'http://courses.illinois.edu/cisapp/explorer/schedule/' + str(year) + '/' + str(season) + '/' + abbrev
	courseXML = urllib2.urlopen(URL + '.xml')
	xml = minidom.parse(courseXML)
	courseXML.close()
	courseList = xml.getElementsByTagName('course')
	for i in courseList:
		_courseNum = i.getAttribute('id')
		#print _courseNum
		
		courseXML = urllib2.urlopen(URL + '/' + _courseNum + '.xml')
		xml = minidom.parse(courseXML)
		courseXML.close()
		#do stuff
		_description = xml.getElementsByTagName('description')[0].firstChild.data
		_credits = xml.getElementsByTagName('creditHours')[0].firstChild.data
		_title = xml.getElementsByTagName('label')[0].firstChild.data
                _term = season + " " + year

		#print abbrev + _courseNum + ": " + _title + "  " + _description + " " +  _credits #test

		#convert credits to integer
		c = re.search(r'\d', _credits)
		_credits = c.group(0)
		query = "INSERT INTO Courses (Name, CourseCode, Descripton, Credits, Term) VALUES ('" +  _title + "', '" + abbrev + " " + _courseNum + "', '" + _description + "', " + _credits + ", '" + _term + "')"
		with con: 
			cur = con.cursor()
		cur.execute(query)




		CRNList = xml.getElementsByTagName('section')

		for i in CRNList:
			_CRN = i.getAttribute('id')
			courseXML_sub = urllib2.urlopen(i.getAttribute('href'))
			xml2 = minidom.parse(courseXML_sub)
			
			_time1 = xml2.getElementsByTagName('start')[0].firstChild.data
			if (_time1 != 'ARRANGED'):
				_time2 = ' - ' + xml2.getElementsByTagName('end')[0].firstChild.data
				t = re.search(r'\d\d:\d\d\s[A|P]M', _time2)
				_time2 = t.group(0)
				_days = xml2.getElementsByTagName('daysOfTheWeek')[0].firstChild.data
			else:
				_time2 = ''
				_days = 'N/A'
			try:
				_room = xml2.getElementsByTagName('roomNumber')[0].firstChild.data
			except IndexError:
				_room = 'N/A'
			try:
				_building = xml2.getElementsByTagName('buildingName')[0].firstChild.data
			except IndexError:
				_building = 'N/A'
			try:
				_startdate = xml2.getElementsByTagName('startDate')[0].firstChild.data
			except IndexError:
				_startdate = 'N/A'
			try:
				_enddate = xml2.getElementsByTagName('endDate')[0].firstChild.data
			except IndexError:
				_enddate = 'N/A'

			_days = re.sub(r'\s','',_days) 


			with con:
				cur = con.cursor()
				query = "INSERT INTO CourseMeetings VALUES ('" + abbrev + " " + _courseNum + "','" + _CRN + "','" + _time1 + "','" + _time2 + "','" + _days + "','" + _building + "'"
				cur.execute(query)


	






if con:
    print "Insert " + abbrev + " " + season + " " + year + " into db. Done."

    con.close()


