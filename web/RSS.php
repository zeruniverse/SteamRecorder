<?php
header('Content-type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8" ?>';
require_once("function/sqllink.php");
$link=sqllink();
if(!$link) {die("can't connect to database");}
?>
<rss version="2.0">

<channel>
  <title><?php echo $RSS_TITLE;?></title>
  <link><?php echo $RSS_LINK;?></link>
  <description>Track Steam Play Time. Track How Life Is Ruined.</description>
<?php
	$sql = "SELECT * FROM `steamdata` order by id desc limit 30;";
	$res = sqlquery($sql,$link);
	while ($i = $res->fetch(PDO::FETCH_ASSOC)){ 
		echo '<item>';
		if(int($i['type'])==0) {
			$asql = "select time from steamdata where id = ?";
			$ares = sqlexec($asql,array((int)$i['id']-1),$link);
			$oldt = $ares->fetch(PDO::FETCH_ASSOC);
			$tdiff = (int)$i['time']-(int)$oldt['time'];
			$tstring = '';
			$flag = 0;
			if(floor($tdiff / 3600) > 0) {
				$tstring=$tstring.(string)((int)floor($tdiff / 3600)).'h ';
				$flag = 1;
				$tdiff = $tdiff % 3600;
			}
			if(floor($tdiff / 60)>0 || $flag == 1) {
				$tstring=$tstring.(string)((int)floor($tdiff / 60)).'m ';
				$flag = 1;
				$tdiff = $tdiff % 60;
			}
			$tstring=$tstring.(string)((int)$tdiff).'s';
			$title = str_replace("%s", $i['game'], $STOP_C);
			$title = str_replace("%h", $tstring, $title);
		} else
		{
			$title = str_replace("%s", $i['game'], $START_C);
		}
		echo '<title>'.$title.'</title>';
		echo '<link>'.$RSS_LINK.'</link>';
		echo '<pubDate>'.gmdate(DATE_RSS, (int)$i['time']).'</pubDate>';
		echo'</item>';
	}
?>
	
</channel>

</rss>