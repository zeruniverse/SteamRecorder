SteamRecorder
========================
[![Build Status](https://travis-ci.org/zeruniverse/SteamRecorder.svg?branch=master)](https://travis-ci.org/zeruniverse/SteamRecorder)
![Release](https://img.shields.io/github/release/zeruniverse/SteamRecorder.svg)
![Environment](https://img.shields.io/badge/python-2.6, 2.7-blue.svg)
![Environment](https://img.shields.io/badge/PHP-5.2+-blue.svg)
![License](https://img.shields.io/github/license/zeruniverse/SteamRecorder.svg)  
I use this to record hours I wasted on Steam, so I know how my life is ruined.

# Version  
v0.1  
   
# Release  
Download [Release](https://github.com/zeruniverse/SteamRecorder/releases/latest)  
  
# Features  
+ A Python web crawler to crawl real-time data from steam.    
+ An RSS feeder. You can do a lot with [IFTTT](https://ifttt.com/).    
  For example:  
  ![capture](https://cloud.githubusercontent.com/assets/4648756/16863169/e991d118-4a04-11e6-8c3f-afae04e7cb1b.PNG)
  
+ Web interface to search for history data.
  
# Usage  
+ Download from Release.  
+ Create a database for SteamRecorder.  
+ Write database info into `crawler/crawler.py, LN 10-13` and `web/function/config.php LN 7-16`.
+ Find the link to your personal Steam page and write it into `crawler/crawler.py, LN 14`, your steam profile has to be public.
  *After login, put the mouse cursor on your username on the navbar and click 'PROFILE' in the drop-down menu. The URL (***don't forget https://***) of the profile page is what you need.* The profile page should look like the following image.  
  ![capture](https://cloud.githubusercontent.com/assets/4648756/16862837/0921df26-4a02-11e6-9a66-2ef2bcdb291a.PNG)
  
+ Start `crawler.py` by `nohup python crawler.py >/dev/null 2>&1` (You need `MySQLdb-python`)
+ Config the rest of `web/function/config.php` with your own settings.
+ Put the `web` folder into your web server.
+ The RSS feeder should be accessible via `http://yourdomain.com/path/to/web/RSS.php`

# Copyright  
Jeffery Zhao  
License: GNU GPL v3.0 or later  
Copyrights of all JS libraries used in `web` folder are reserved by their authors.
