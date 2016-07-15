<?php
require_once("function/sqllink.php");
$link=sqllink();
if(!$link || !isset($_GET['id']) || $_GET['id']=='') {die("ERROR");}
$sql = "select game, type, UNIX_TIMESTAMP(time) as time from steamdata where id = ?";
$res = sqlexec($sql,array($_GET['id']),$link);
$res = $res->fetch(PDO::FETCH_ASSOC);
if(!$res) die("ID not exists");
$startt = '';
$endt = '';
$game = $res['game'];
$duration = 'Can\'t get duration. Either the game is still running or there\'s a record error';
if((int)$res['type']==0) //end game
{
	$sql = "select game, type, UNIX_TIMESTAMP(time) as time from steamdata where id = ?";
	$res1 = sqlexec($sql,array((int)$_GET['id']-1),$link);
	$res1 = $res1->fetch(PDO::FETCH_ASSOC);
	$startt = 'unavailable';
	$endt = $res['time'];
	if($res1 && $res1['game']==$res['game'] && (int)$res1['type']==1){
		$startt = $res1['time'];
		$tdiff = (int)$endt-(int)$startt;
		$duration = '';
		$flag = 0;
		if(floor($tdiff / 3600) > 0) {
			$duration=$duration.(string)((int)floor($tdiff / 3600)).'h ';
			$flag = 1;
			$tdiff = $tdiff % 3600;
		}
		if(floor($tdiff / 60)>0 || $flag == 1) {
			$duration=$duration.(string)((int)floor($tdiff / 60)).'m ';
			$flag = 1;
			$tdiff = $tdiff % 60;
		}
		$duration=$duration.(string)((int)$tdiff).'s';
	}
} else //start game
{
	$sql = "select game, type, UNIX_TIMESTAMP(time) as time from steamdata where id = ?";
	$res1 = sqlexec($sql,array((int)$_GET['id']+1),$link);
	$res1 = $res1->fetch(PDO::FETCH_ASSOC);
	$endt = 'unavailable';
	$startt = $res['time'];
	if($res1 && $res1['game']==$res['game'] && (int)$res1['type']==0){
		$endt = $res1['time'];
		$tdiff = (int)$endt-(int)$startt;
		$duration = '';
		$flag = 0;
		if(floor($tdiff / 3600) > 0) {
			$duration=$duration.(string)((int)floor($tdiff / 3600)).'h ';
			$flag = 1;
			$tdiff = $tdiff % 3600;
		}
		if(floor($tdiff / 60)>0 || $flag == 1) {
			$duration=$duration.(string)((int)floor($tdiff / 60)).'m ';
			$flag = 1;
			$tdiff = $tdiff % 60;
		}
		$duration=$duration.(string)((int)$tdiff).'s';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>SteamRecorder</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="SteamRecorder">
  <meta name="author" content="Jeffery Zhao">
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>

  <![endif]-->

  <!-- Fav and touch icons -->
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
<style>
.nodeco
{text-decoration:none; color:#666666;}
.nodeco:hover,active,link,visited
{text-decoration:none; color:#666666;}
</style>
</head>

<body style="color:#666666">
<div class="container theme-showcase">
      <div class="page-header">
        <h1>Detailed Play Record</h1>
	  </div>
      <div class="jumbotron">
        <p>Game Played: <?php echo $game;?></p>
        <p>Start Time: <span id="gst" datat="<?php echo $startt;?>"></span></p>
		<p>End Time: <span id="est" datat="<?php echo $endt;?>"></span></p>
        <p>Time Played: <?php echo $duration;?></p>
		</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var options = {
    weekday: "long", year: "numeric", month: "short",
    day: "numeric", hour: "2-digit", minute: "2-digit"
	};
	var sstamp=$("#gst").attr('datat');
	if (sstamp=='unavailable') $("#gst").html('unavailable');
	else {
		var date = new Date(parseInt(sstamp) * 1000);
		$("#gst").html(date.toLocaleTimeString("en-us", options));
	}
	var estamp=$("#est").attr('datat');
	if (estamp=='unavailable') $("#est").html('unavailable');
	else {
		var date = new Date(parseInt(estamp) * 1000);
		$("#est").html(date.toLocaleTimeString("en-us", options));
	}
		
});
</script>
<footer class="footer ">
      <p>&copy;2016 Jeffery Zhao</p>
</footer>
</body>
</html>