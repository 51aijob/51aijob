
import pymysql
import argparse
import math
import subprocess
import re

#function, setup connection with mysql
def setupConnection(dbName):
	global cur,conn

	#connect mysql server
	conn=pymysql.connect(host="localhost",user="root",charset="utf8")
	cur = conn.cursor()

	#use resumeDB as current db
	cur.execute("use %s"%dbName)

#function, zoom input value
def zoom(v):
	#return -math.log(1-float(v))
	#return math.exp(math.sqrt(1/(1-v)))
	return float(v)

#function, create table
def createmsemeTable(tbName):
	global cur,conn

	#create mse,me table for ee
	createTbSQLee = "create table %s (id int(100) not null primary key auto_increment,file_location char(100), mse char(100), me char(100))"
	cur.execute(createTbSQLee%tbName)

#function, create table without auto incremental ID, since it will impact the result of 'order by' and 'limit'
def createmsemeTablewoID(tbName):
	global cur,conn

	#create mse,me table for ee
	createTbSQLee = "create table %s (file_location char(100), mse char(100), me char(100))"
	cur.execute(createTbSQLee%tbName)

#function mse
def mse(requireDict, candidateDict):
	squareSum = 0
	for k,v in list(requireDict.items()):
		squareSum += (zoom(candidateDict[k])-zoom(v))**2
	count = len(list(requireDict.keys()))
	return  squareSum/count

#function me, no square
def me(requireDict, candidateDict):
	subtractSum = 0
	for k,v in list(requireDict.items()):
		subtractSum += (zoom(v)-zoom(candidateDict[k]))
	count = len(list(requireDict.keys()))
	return subtractSum/count

#function, seperate original table into two parts, positive and negative
def seperateTable(dbName,tbName,tbNameP,tbNameN):
	#conditionPositive = 'OPC > 0.9 and CalCM > 0.9'
	conditionPositive = ""
	for key in list(requireDict.keys()):
		if conditionPositive == "":
			conditionPositive=key+'>='+str(requireDict[key])
		else:
			conditionPositive=conditionPositive+' and '+key+'>='+str(requireDict[key])
	conditionPositive_=conditionPositive
	conditionPositive=conditionPositive+' and status=1'+' and expect_place='+'"'+location+'"'+' and shield_company NOT LIKE '+'"'+'%'+shieldmail+'%'+'"'+' and degree>='+'"'+degree+'"'
	conditionNegative = '!'+'('+conditionPositive_+')'
	conditionNegative=conditionNegative+' and status=1'+' and expect_place='+'"'+location+'"'+' and shield_company NOT LIKE '+'"'+'%'+shieldmail+'%'+'"'+' and degree>='+'"'+degree+'"'
	print(conditionPositive)
	print(conditionNegative)
	#exit();

	createTableConditional = 'create table %s select * from %s where %s'
	paramP = (tbNameP,tbName,conditionPositive)
	paramN = (tbNameN,tbName,conditionNegative)
	cur.execute(createTableConditional%paramP)
	cur.execute(createTableConditional%paramN)

#function, create mseme table for positvie and negative
def createMseMeTable(dbName,tbNamePmseme,tbNameNmseme):
        createmsemeTable(tbNamePmseme)
        createmsemeTable(tbNameNmseme)

#function,
def filterIntoMseMeTable(selectSQL,insertSQL,condition,tbNamePN,tbNamePNMseMe): 
	#select score based on requirement
	param = (condition,tbNamePN)
	count = cur.execute(selectSQL%param)

	#create a new connection, avoid fetched data lose if reuse cur
	cur1=conn.cursor()

	candidateDict = {}
	for i in range(count):
		data = cur.fetchone()
		j = 2
		for key in list(requireDict.keys()):
			candidateDict[key] = data[j]
			j+=1
		id = int(data[0])
		file_location = str(data[1])
		#print(file_location)
		mseValue = float(mse(requireDict,candidateDict))
		meValue = float(me(requireDict,candidateDict))
		param = (tbNamePNMseMe,id,file_location,mseValue,meValue)
		#print(insertSQL%param)
		cur1.execute(insertSQL%param)
		conn.commit()

		#function, for sort with me and save into another table
def sortbyme(tbNamemsemeSortmseme,tbNamemsemeSortmse):
	global cur,conn
	createmsemeTablewoID(tbNamemsemeSortmseme)
	insertSortSQL = 'insert into %s (select * from (select * from (select file_location,mse,me from %s limit %s,%s) tmp where me < 0) tmp1  order by %s desc)'
	insertSortSQL_ = 'insert into %s (select * from (select * from (select file_location,mse,me from %s limit %s,%s) tmp where me >= 0) tmp1  order by %s asc)'
	orderField = 'me'

	count = cur.execute('select * from %s'%tbNamemsemeSortmse)
	if count == 0:
		return
	ratio = 5/count
	step = math.ceil(count * ratio)
	stepCount = math.ceil(count/step)
	#print(count,ratio,step,stepCount)
	for i in range(stepCount):
		#print(i)
		startNo = i*step
		#print(startNo,step)
		param = (tbNamemsemeSortmseme,tbNamemsemeSortmse,startNo,step,orderField)
		#print(insertSortSQL%param)
		cur.execute(insertSortSQL%param)
		conn.commit()
		cur.execute(insertSortSQL_%param)
		conn.commit()

#main
def main():
	global cur,conn
	global tail
	setupConnection('resumeDB')

	sql="select column_name from information_schema.columns where table_name = 'resumeinfo' and table_schema = 'resumedb'"
	cur.execute(sql)
	fields_=cur.fetchall()
	fields = str(fields_)
	conditionPositive = ""
	for key in list(requireDict.keys()):
		flag =  re.search(key, fields) 
		if not flag:
			print(key+' is deleted')
			del requireDict[key]
	if (requireDict.keys()==""):
		print("all conditions are not met!")
		exit()
	print(requireDict)

	#drop, for debug
	cur.execute('drop table if exists resumeinfoNegativetail')
	cur.execute('drop table if exists resumeinfoNegativeMseMetail')
	cur.execute('drop table if exists resumeinfoPositivetail')
	cur.execute('drop table if exists resumeinfoPositiveMseMetail')
	cur.execute('drop table if exists resumeinfoPositiveMseMESortMsetail')
	cur.execute('drop table if exists resumeinfoNegativeMseMESortMsetail')
	cur.execute('drop table if exists resumeinfoPositiveMseMESortMseMetail')
	cur.execute('drop table if exists resumeinfoNegativeMseMESortMseMetail')

	#seperation
	dbName = 'resumeDB'
	tbName = 'resumeinfo'
	tbNameP = 'resumeinfoPositive'+tail
	tbNameN = 'resumeinfoNegative'+tail
	seperateTable(dbName,tbName,tbNameP,tbNameN)

	#create tbNamePmseme and tbNameNmseme
	tbNamePmseme = 'resumeinfoPositiveMseME'+tail
	tbNameNmseme = 'resumeinfoNegativeMseME'+tail
	createMseMeTable(dbName,tbNamePmseme,tbNameNmseme)

	#select score based on requirement
	selectSQL = 'select %s from %s'
	insertSQL = "insert into %s values (%s,'%s',%s,%s)"
	requireFields = 'id,file_location'
	for key in list(requireDict.keys()):
		requireFields=requireFields+','+key
	condition = requireFields

	#create mseme table for positive part
	tbNamePN = tbNameP
	tbNamePNMseMe = tbNamePmseme
	filterIntoMseMeTable(selectSQL,insertSQL,condition,tbNamePN,tbNamePNMseMe)

	#create mseme table for negative part
	tbNamePN = tbNameN
	tbNamePNMseMe = tbNameNmseme
	filterIntoMseMeTable(selectSQL,insertSQL,condition,tbNamePN,tbNamePNMseMe)

	#sort mseme table with mse
	tbNamePmsemeSortmse = 'resumeinfoPositiveMseMESortMse'+tail
	tbNameNmsemeSortmse = 'resumeinfoNegativeMseMESortMse'+tail
	createSortSQL = 'create table %s select file_location,mse,me from %s order by %s'
	param = (tbNamePmsemeSortmse,tbNamePmseme,'mse')
	cur.execute(createSortSQL%param)
	param = (tbNameNmsemeSortmse,tbNameNmseme,'mse')
	cur.execute(createSortSQL%param)

	#sort with me
	tbNamemsemeSortmsemeP = 'resumeinfoPositiveMseMESortMseMe'+tail
	tbNamemsemeSortmseP = 'resumeinfoPositiveMseMESortMse'+tail
	sortbyme(tbNamemsemeSortmsemeP,tbNamemsemeSortmseP)

	tbNamemsemeSortmsemeN = 'resumeinfoNegativeMseMESortMseMe'+tail
	tbNamemsemeSortmseN = 'resumeinfoNegativeMseMESortMse'+tail
	sortbyme(tbNamemsemeSortmsemeN,tbNamemsemeSortmseN)

	#call first 20 candidates for positive and negative
	cur.execute("select * from %s limit 0,20"%tbNamemsemeSortmsemeP)
	rows = cur.fetchall()
	print("Experts:")
	print("Resume Name,	mse,	me")
	for row in rows:
		print(row)
	cur.execute("select * from %s limit 0,20"%tbNamemsemeSortmsemeN)
	rows = cur.fetchall()
	print('\n')
	print("Propers:")
	print("Resume Name,	mse,	me")
	for row in rows:
		print(row)


if __name__ == "__main__":
	parser = argparse.ArgumentParser()
	parser.add_argument('-r','-requiredict',action='store',dest='requiredict',help='input requiredict')
	parser.add_argument('-t','-tail',action='store',dest='tail',help='tail')
	parser.add_argument('-l','-location',action='store',dest='location',help='job location')
	parser.add_argument('-m','-shieldmail',action='store',dest='shieldmail',help='shield mail')
	parser.add_argument('-d','-degree',action='store',dest='degree',help='degree')
	args = parser.parse_args()
	requireDict_ = eval(args.requiredict)
	requireDict = {}
	for k,v in requireDict_.items():
		requireDict[k.lower()]=v
	tail = args.tail
	location = args.location
	shieldmail = args.shieldmail
	degree = args.degree
	main()
	conn.commit()
	cur.close()
	conn.close()
