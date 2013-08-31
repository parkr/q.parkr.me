<?php
include "db.inc.php";
include "functions.inc.php";
foreach ($_GET as $key => $value) { 
	$_GET[$key] = mysql_real_escape_string($value); 
}
if (isset($_GET['id'])){ 
	$id = $_GET['id'];
	$check = mysql_query("SELECT * FROM Images where id = '".$id."'",$db);
	$check = mysql_fetch_row($check); 
	if ($check) { 
		header("Accept-Ranges: bytes");
		header("Content-Length: ". filesize("imageupload/".$check[3]) );
		header("Content-type:".$check[4]);
		readfile("imageupload/".$check[3]);
	}else{
		header("Location: http://q.parkr.me/blah");
	}
}elseif(isset($_GET['page'])){
	$id = $_GET['page'];
	$check = mysql_query("SELECT * FROM Images where id = '".$id."'",$db);
	$check = mysql_fetch_row($check); 
	if ($check){
		if(strstr($_SERVER['HTTP_USER_AGENT'], "iPhone")){
			$suffix="-iphone";
			$nameE = explode(".", $check[3]);
			$name = $nameE[0] . $suffix .".". $nameE[1];
			$iphone = true;
		}else{
			$name = $check[3];
			$iphone = false;
		}
		$image = "imageupload/".$name;
		$info = getimagesize($image); 
		$user = array();
		$uid = $check[0];
	}else{
		header("Location: http://q.parkr.me/blah");
	}
	$user = array();
	if($check[1] == "" || $check[1] == null){
		$user['name'] = "unknown";
		$user['link'] = "<a>@".$user['name']."</a>";
	}else{
		$user['name'] = $check[1];
		$user['link'] = "<a href='http://q.parkr.me/user/".$user['name']."'>@".$user['name']."</a>";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>@<?php echo $user['name']; ?>'s Images: Q.Parkr.Me</title>
	<?php if($iphone):?><meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<link media="handheld, screen and (max-device-width: 480px)" href="/iphone.css" type="text/css" rel="stylesheet" />
	<link rel="stylesheet" media="only screen and (-webkit-min-device-pixel-ratio: 2)" type="text/css" href="/iphone.css" /><? endif; ?>
	<link media="screen" href="/qparkrme.css" type="text/css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery-1.4.4.js" type="text/javascript"></script>
	<script type="text/javascript" src="/qparkrme.js"></script>
</head>
<body<?php if($iphone){ echo ' onorientationchange="resize(); return false;"';} ?> oncontextmenu="return false;">
	<div id="container" style="width:<?php echo $info[0] ?>px;">
		<?php if(!$iphone){ ?><div id="header"><a href="http://q.parkr.me/">Q.Parkr.Me</a></div><?}?>
		<div id="image-container">
			<img id="image" <?php echo 'src="/'. $image . '" ';//. $info[3]; ?> />
			<!--<a href="<?php echo "/".$image ?>">View the image</a> -->
		</div>
		<?php if(!$iphone){?><div id="caption"><?php echo twitterify($check[5]); ?></div><?}?>
		<div id="footer">Uploaded by <?php echo $user['link'] ?><br /><?php
				//Comments
				$query = "SELECT * FROM `ImageComments` WHERE `image_id` = $uid";
				$result = mysql_query($query);
				if($result){
					$num = mysql_num_rows($result);
					if($num == 1){
						$nummacomments = "comment";
					}else{
						$nummacomments = "comments";
					}
					if(!$iphone){echo "<a href='/comments/".$id."/'>". $num . " ". $nummacomments ."</a><br />";}
				}else{
					echo '<a href="/comments/'.$id.'/">0 comments</a><br />';
				}
				//Views
				$query = "SELECT `count` FROM Images WHERE `id` = '$id'";
				$count = mysql_result(mysql_query($query), 0, 'count');
				$count++;
				mysql_query("UPDATE `Images` SET `count` = $count WHERE `id` = '$id'");
				echo $count . ' ';
			?>views<br>
			<?php if($debug){ echo "Rotate 90 <a href='/rotate/left/$id/'>Left</a>/<a href='/rotate/right/$id/'>Right</a><br>"; } ?>
			<?php if($debug){ echo "<a href='/thumb/150/$id/'>Create Thumbnail</a>"; } ?>
			</div>
			<?php if(!$iphone){ echo "<div id=\"bgimg\" onclick=\"sendhome();\">&nbsp;</div>\n"; } ?>
	</div>
	<script type="text/javascript">
		<?php if($iphone){ echo "resize();\n"; }else{ echo "repositionBg();\n"; } ?>
	</script>
</body>
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-20422511-1']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
</html>
<?php
}elseif(isset($_GET['user'])){
	$user = $_GET['user'];
	if(strstr($_SERVER['HTTP_USER_AGENT'], "iPhone")){$iphone = true;}else{$iphone = false;}
	$query = "SELECT * FROM `Images` WHERE `alive`=1 AND `user` = '$user' ORDER BY `uid` DESC LIMIT 0,9";
	$result = mysql_query($query);
	$output = array();
	for($i=0; $i<mysql_num_rows($result); $i++){
		//Build output
		$name = mysql_result($result, $i, 'name');
		$uid = mysql_result($result, $i, 'uid');
		$user = mysql_result($result, $i, 'user');
		$count = mysql_result($result, $i, 'count');
		$id = mysql_result($result, $i, 'id');
		$thumbE = explode(".", $name);
		$thumb = $thumbE[0] . "-thumb." . $thumbE[1];
		if(file_exists("imageupload/$thumb")){
			$name = $thumb;
		}
		$queryIC = "SELECT * FROM `ImageComments` WHERE `image_id` = $uid";
		$resultIC = mysql_query($queryIC);
		if($resultIC){
			$num = mysql_num_rows($resultIC);
			if($num == 1){
				$nummacomments = "comment";
			}else{
				$nummacomments = "comments";
			}
			$comments = "<a href='/comments/".$id."/'>". $num . " ". $nummacomments ."</a><br />";
		}else{
			$comments = '<a href="/comments/'.$id.'/">0 comments</a><br />';
		}
		$output[$i] = "<a href='/view/$id/'><img src='/imageupload/$name' width='175' height='175' alt='Image $uid'></a><br>
		Uploaded by <a href='http://twitter.com/$user'>@$user</a><br>
		$comments
		$count views";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		<title>Q.Parkr.Me</title>
		<?php if($iphone):?><meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
		<link media="handheld, screen and (max-device-width: 480px)" href="/iphone.css" type="text/css" rel="stylesheet" />
		<link rel="stylesheet" media="only screen and (-webkit-min-device-pixel-ratio: 2)" type="text/css" href="iphone.css" /><? endif; ?>
		<link media="screen" href="/qparkrme.css" type="text/css" rel="stylesheet">
		<script src="http://code.jquery.com/jquery-1.4.4.js" type="text/javascript"></script>
		<script type="text/javascript" src="/qparkrme.js"></script>
	</head>
	<body<?php if($iphone){ echo ' onorientationchange="resize(); return false;"';} ?> oncontextmenu="return false;">
		<div id="container" style="width:700px;">
			<div id="header"><a href="http://q.parkr.me/">Recent: Q.Parkr.Me</a></div>
			<?php if($blah): ?><div id="blah"><?php echo stripslashes(urldecode($_GET['m'])); ?></div><?endif;?>
			<div id="grid">
				<?php
					for($i=0; $i<mysql_num_rows($result); $i++){
						$numrows = 3;
						$numcols = 3;
						$row = $i/$numcols;
						//print "$row has " . strlen(substr(strrchr($row, "."), 1)) . "decimal numbers<br>";
						if(strlen(substr(strrchr($row, "."), 1)) == 0){
							if($i==0){
								echo "<div class='row'>\n";
							}elseif($i<mysql_num_rows($result)-2){
								echo "\t\t\t\t\t</div>\n\t\t\t\t\t<div class='row'>\n";
							}
						}
						echo "\t\t\t\t\t\t<div id='$i' class='thumb'>$output[$i]</div>\n";
						if($i==mysql_num_rows($result)-1){
							echo "\t\t\t\t\t</div>";
						}
					}
				?>
			</div>
			<div id="footer">All photos by <a href="http://twitter.com/parkr" target="_blank">@parkr</a> and <a href="http://twitter.com/deparkr" target="_blank">@deparkr</a>
				<?php if($debug){ echo "<div id=\"debug\"></div><div><a onclick='repositionBg()'>RepositionBg()</a></div>\n"; } ?>
				<?php if(!$iphone){ echo "<div id=\"bgimg\" onclick=\"sendhome();\">&nbsp;</div>\n"; } ?>
		</div>
		<script type="text/javascript">
			<?php if($iphone){ echo "resize();\n"; }else{ echo "repositionBg();\n"; } ?>
		</script>
	</body>
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-20422511-1']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</html>
<?php
}else{
	if(strstr($_SERVER['HTTP_USER_AGENT'], "iPhone")){$iphone = true;}else{$iphone = false;}
	$query = "SELECT * FROM `Images` WHERE `alive`=1 AND `user` = 'parkr' ORDER BY `uid` DESC LIMIT 0,9";
	$result = mysql_query($query);
	$output = array();
	for($i=0; $i<mysql_num_rows($result); $i++){
		//Build output
		$name = mysql_result($result, $i, 'name');
		$uid = mysql_result($result, $i, 'uid');
		$user = mysql_result($result, $i, 'user');
		$count = mysql_result($result, $i, 'count');
		$id = mysql_result($result, $i, 'id');
		$thumbE = explode(".", $name);
		$thumb = $thumbE[0] . "-thumb." . $thumbE[1];
		if(file_exists("imageupload/$thumb")){
			$name = $thumb;
		}
		$queryIC = "SELECT * FROM `ImageComments` WHERE `image_id` = $uid";
		$resultIC = mysql_query($queryIC);
		if($resultIC){
			$num = mysql_num_rows($resultIC);
			if($num == 1){
				$nummacomments = "comment";
			}else{
				$nummacomments = "comments";
			}
			$comments = "<a href='/comments/".$id."/'>". $num . " ". $nummacomments ."</a><br />";
		}else{
			$comments = '<a href="/comments/'.$id.'/">0 comments</a><br />';
		}
		$output[$i] = "<a href='/view/$id/'><img src='imageupload/$name' width='175' height='175' alt='Image $uid'></a><br>
		Uploaded by <a href='http://www.twitter.com/$user'>@$user</a><br>
		$comments
		$count views";
	}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		<title>Q.Parkr.Me</title>
		<?php if($iphone):?><meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
		<link media="handheld, screen and (max-device-width: 480px)" href="/iphone.css" type="text/css" rel="stylesheet" />
		<link rel="stylesheet" media="only screen and (-webkit-min-device-pixel-ratio: 2)" type="text/css" href="iphone.css" /><? endif; ?>
		<link media="screen" href="/qparkrme.css" type="text/css" rel="stylesheet">
		<script src="http://code.jquery.com/jquery-1.4.4.js" type="text/javascript"></script>
		<script type="text/javascript" src="/qparkrme.js"></script>
	</head>
	<body<?php if($iphone){ echo ' onorientationchange="resize(); return false;"';} ?> oncontextmenu="return false;">
		<div id="container" style="width:700px;">
			<div id="header"><a href="http://q.parkr.me/">Recent: Q.Parkr.Me</a></div>
			<?php if($blah): ?><div id="blah"><?php echo stripslashes(urldecode($_GET['m'])); ?></div><?endif;?>
			<div id="grid">
				<?php
					for($i=0; $i<mysql_num_rows($result); $i++){
						$numrows = 3;
						$numcols = 3;
						$row = $i/$numcols;
						//print "$row has " . strlen(substr(strrchr($row, "."), 1)) . "decimal numbers<br>";
						if(strlen(substr(strrchr($row, "."), 1)) == 0){
							if($i==0){
								echo "<div class='row'>\n";
							}elseif($i<mysql_num_rows($result)-2){
								echo "\t\t\t\t\t</div>\n\t\t\t\t\t<div class='row'>\n";
							}
						}
						echo "\t\t\t\t\t\t<div id='$i' class='thumb'>$output[$i]</div>\n";
						if($i==mysql_num_rows($result)-1){
							echo "\t\t\t\t\t</div>";
						}
					}
				?>
			</div>
			<div id="footer">All photos by <a href="http://q.parkr.me/user/parkr" target="_blank">@parkr</a> and <a href="http://q.parkr.me/user/deparkr" target="_blank">@deparkr</a>
				<?php if($debug){ echo "<div id=\"debug\"></div><div><a onclick='repositionBg()'>RepositionBg()</a></div>\n"; } ?>
				<?php if(!$iphone){ echo "<div id=\"bgimg\">&nbsp;</div>\n"; } ?>
		</div>
		<script type="text/javascript">
			<?php if($iphone){ echo "resize();\n"; }else{ echo "repositionBg();\n"; } ?>
		</script>
	</body>
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-20422511-1']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</html>
<?php } ?>