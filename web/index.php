<?php
require_once("function/sqllink.php");
$link=sqllink();
if(!$link) {die("can't connect to database");}
if(!isset($_GET['s'])||$_GET['s']=='') $start = 946684800; else $start=(int)$_GET['s'];
if(!isset($_GET['e'])||$_GET['e']=='') $end = 1893456000; else $end=(int)$_GET['e'];
if(!isset($_GET['g'])||$_GET['g']=='') $uri=''; else $uri=urldecode($_GET['g']);
if(!isset($_GET['d'])||(int)($_GET['d'])<=0) $d=1; else $d=(int)($_GET['d']);
$rs=($uri=='')?sqlexec("select count(*) from `steamdata` WHERE UNIX_TIMESTAMP(`time`)>=? AND UNIX_TIMESTAMP(`time`)<=?", array($start,$end),$link):sqlexec("select count(*) from `steamdata` WHERE UNIX_TIMESTAMP(`time`)>=? AND UNIX_TIMESTAMP(`time`)<=? AND `game` LIKE LOWER(?)",array($start,$end,'%'.strtolower($uri).'%'),$link);
$page=$d;
$myrow = $rs->fetch(PDO::FETCH_NUM);
$numrows=$myrow[0];
$pagesize=10;
$pages=intval($numrows/$pagesize);
if ($numrows%$pagesize) $pages++;
$offset=$pagesize*($page - 1);
$sql=($uri=='')?"select id,UNIX_TIMESTAMP(time) as time,type,game from `steamdata` WHERE UNIX_TIMESTAMP(`time`)>=? AND UNIX_TIMESTAMP(`time`)<=? order by `id` DESC limit ?,?":"select id,UNIX_TIMESTAMP(time) as time,type,game from `steamdata` WHERE UNIX_TIMESTAMP(`time`)>=? AND UNIX_TIMESTAMP(`time`)<=? AND `game` LIKE LOWER(?) order by `id` DESC limit ?,?";
$rs=($uri=='')?sqlexec($sql,array($start,$end,$offset,$pagesize),$link):sqlexec($sql,array($start,$end,'%'.strtolower($uri).'%',$offset,$pagesize),$link);
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
        <h1>Play Record</h1>
	  </div>
      <p>Start Date：<input style="width:100px;" type="text" id="dates" class="tcal" value="2016-07-15" readonly/>&nbsp;&nbsp;|&nbsp;&nbsp;
	  End Date：<input style="width:100px;" type="text" id="datee" class="tcal" value="2016-07-15" readonly/>&nbsp;&nbsp;|&nbsp;&nbsp;
	  Game：<input style="width:280px;" type="text" id="pname"  value="<?php echo $uri;?>" placeholder="Leave blank to search for all games" />&nbsp;&nbsp;|&nbsp;&nbsp;
	  <button onClick="searchd();" class="btn btn-primary btn-sm" role="button">Search</button></p>
      <div class=""></div>
	  <div class="jumbotron">
	  <p><span style="color:green">Green</span> means start playing.</p>
	  <p><span style="color:red">Red</span> means stop playing.</p>
      </div>
	  <div>
      <table class="table">
      <tr><th>Game Played</th><th>Time</th><th>View Session</th></tr>
	  <?php 
	  while ($i = $rs->fetch(PDO::FETCH_ASSOC))
	  {
		$color=((int)$i['type']==1)?'green':'red';
		echo '<tr><td style="color:'.$color.'">'.$i['game'].'</td><td class="timeslot" datat="'.$i['time'].'"></td><td><a href="details.php?id='.$i['id'].'" target="_blank">View Session</a></td></tr>';
	  }
	  ?>
      </table>	
      </div>
      <div style="float:right"><ul id="pagination-demo"class="pagination-sm"></ul></div>
</div>
<link rel="stylesheet" type="text/css" href="css/tcal.css" />
<script type="text/javascript" src="js/tcal.js"></script>
<script type="text/javascript" src="js/pagination.js"></script>

<script type="text/javascript">
function dformat(date){
	var y=date.getFullYear();
	var s=String(y)+'-';
	var m=date.getMonth()+1;
	if(m<10) s=s+'0';
	s=s+String(m)+'-';
	var d=date.getDate();
	if(d<10) s=s+'0';
	s=s+String(d);
	return s;
}
$(document).ready(function() {
	var options = {
    weekday: "long", year: "numeric", month: "short",
    day: "numeric", hour: "2-digit", minute: "2-digit"
	};
	$( ".timeslot" ).each(function() {
		var date = new Date(parseInt($(this).attr('datat'))*1000);
		$(this).html(date.toLocaleTimeString("en-us", options));
	});
	var times = new Date(<?php echo $start;?>*1000);
	var timee = new Date(<?php echo $end;?>*1000);
	$("#dates").val(dformat(times));
	$("#datee").val(dformat(timee));
});
$('#pagination-demo').twbsPagination({
        totalPages: <?php echo $pages;?>,
        visiblePages: 9,
		startPage: <?php echo $d;?>,
		first: '<<<',
		last: '>>>',
		prev: '<',
		next: '>',
        onPageClick: function (event, page) {
            window.location.href="./index.php?s=<?php echo $start;?>&g=<?php echo urlencode($uri);?>&e=<?php echo $end;?>&d="+page;
        }
    });
function searchd()
{
	var s=new Date($("#dates").val()+'T00:00:00');
	var e=new Date($("#datee").val()+'T23:59:59');
	var start=s.getTime() / 1000;
	var end=e.getTime() / 1000;
	window.location.href='./index.php?s='+start+'&e='+end+'&g='+encodeURI($('#pname').val());
}
</script>
<footer class="footer ">
      <p>&copy;2016 Jeffery Zhao</p>
</footer>
</body>
</html>