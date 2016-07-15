import urllib, urllib2
import sys
import logging
import time
import MySQLdb
import re

# CONFIGURATION FIELD
checkFrequency = 180
dbhost = 'localhost'
dbname = 'steam'
dbuser = 'root'
dbpass = '123456'
geturl = 'https://steamcommunity.com/id/zzy8200/'
#check every k seconds
# STOP EDITING HERE

class HTTPClient:
    __req = urllib2.build_opener()
    __req.addheaders = [
        ('Accept', 'application/javascript, */*;q=0.8'),
        ('User-Agent', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Safari/537.36'),
        ('Accept-Language', 'en')
    ]
    urllib2.install_opener(__req)

    def Get(self, url):
        try:
            req = urllib2.Request(url)
            return urllib2.urlopen(req).read()
        except urllib2.HTTPError, e:
            return e.read()

def insertrec(dbcursor,lastgame,typet):
	dbcursor.execute('SELECT max(id) FROM steamdata')
	result = dbcursor.fetchone()
	mid = 1
	if result is not None:
		mid = int(result[0]) + 1
	dbcursor.execute("INSERT INTO steamdata VALUES (%s, %s, %s, CURRENT_TIMESTAMP)",(mid,typet,lastgame))

reload(sys)
sys.setdefaultencoding("utf-8")

logging.basicConfig(filename='log.log', level=logging.DEBUG, format='%(asctime)s  %(filename)s[line:%(lineno)d] %(levelname)s %(message)s', datefmt='%a, %d %b %Y %H:%M:%S')

dbconnect = MySQLdb.connect(host = dbhost,
                    user = dbuser,
                    passwd = dbpass,
                    db = dbname)

dbcursor = dbconnect.cursor()

dbcursor.execute('CREATE TABLE IF NOT EXISTS steamdata (`id` INT(11) NOT NULL, `type` INT(11) NOT NULL, `game` VARCHAR(50), `time` TIMESTAMP, PRIMARY KEY (`id`))')

dbconnect.commit()

laststat = 0
lastgame = ''

crawler = HTTPClient()

while True:
    html = crawler.Get(geturl)
    p = re.search('<div class="responsive_status_info">',html)
    if p is None:
        logging.error('cannot find online info' + html)
        time.sleep(checkFrequency)
        continue
    p = re.search('<div class="profile_in_game persona in-game">',html)
    if p is None and laststat == 1: # Not in game while previous in game. Need to insert an exit msg
        insertrec(dbcursor,lastgame,0)
        laststat = 0
        dbconnect.commit()
        logging.info('stop playing: '+lastgame)
    if p is not None: #In game
        g = re.search('<div class="profile_in_game_name">(.*?)<\/div>',html)
        if g is None:
            logging.error('inconsistent! ' + html)
            time.sleep(checkFrequency)
            continue
        g = g.group(1)
        if laststat == 0: #Start playing
            lastgame = g
            laststat = 1
            insertrec(dbcursor,lastgame,1)
            logging.info('start playing: '+lastgame)
            dbconnect.commit()
        elif lastgame != g: #Change game
            insertrec(dbcursor,lastgame,0) #stop last game
            insertrec(dbcursor,g,1) #start new game
            logging.info('switch game from '+lastgame+' to '+g)
            lastgame = g
            dbconnect.commit()

    time.sleep(checkFrequency)
