#SteamRecorder  
Record hours I wasted on Steam. So I know how my life is ruined.

##Version  
v0.1  
   
##Release  
Download [Release](https://github.com/zeruniverse/SteamRecorder/releases/download/v0.1)  
  
##Features  
+ A python web crawler to crawl real-time data from steam.    
+ A RSS feeder. You can do a lot with [IFTTT](https://ifttt.com/).    
+ Web interface to search for history data.  
  
##Usage  
+ Download from Release.  
+ Create a database for SteamRecorder.  
+ Write database info into `crawler/crawler.py, LN 10-13` and `web/function/config.php LN 7-16`.
+ Find the link to your personal steam page, write it into `crawler/crawler.py, LN 14`, your steam profile has to be public.
+ Start `crawler.py` by `nohup python crawler.py >/dev/null 2>&1` (You need `MySQLdb-python`)
+ Config rest of `web/function/config.php` with your own settings.
+ Put the `web` folder into your web server.
+ The RSS feeder should be accessible via `http://yourdomain.com/path/to/web/RSS.php`

##Copyright  
Jeffery Zhao  
License: GNU GPL v3.0 or later  
Copyrights of all JS libraries used in `web` folder are reserved by their authors.
