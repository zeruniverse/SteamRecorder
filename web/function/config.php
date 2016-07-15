<?php
//****************************************
//PLEASE SPECIFY THE VARIABLES BELOW
//****************************************

//Your database host, e.g. localhost
$DB_HOST='localhost';
//Make sure MySQL is running at default port 3306. Or you have to edit sqllink.php

//You can use existing database for this program as long as there's no table called steamdata in the database.
//The python code will automatically generate the table.
//in order to make PHP work, you should first run the python crawler
$DB_NAME='steam';

//Database Username
$DB_USER='steam';

//Database Password
$DB_PASSWORD='123456';

//RSS start playing string, %s as placeholder for game name
$START_C='Jeffery begins to disappoint his parents by playing %s on steam!';

//RSS stop playing string, %s as placeholder for game name, %h for time played
$STOP_C='Filled up with the sense of guilty, Jeffery stops playing %s, he should have studied for the past %h';

//RSS TITLE
$RSS_TITLE='Jeffery\'s Steam Recorder';

//RSS link for channel (Feed URL)
$RSS_LINK = 'http://steamcommunity.com/id/zzy8200/';

//RSS DETAIL LINK path (Path to details.php)
$RSS_DLINK = 'http://rsssteam.jeffery.cc/details.php';
?>
