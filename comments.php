<?php
include "db.inc.php";
include "functions.inc.php";
foreach ($_GET as $key => $value) { 
	$_GET[$key] = mysql_real_escape_string($value); 
}

@mysql_select_db("parkrm04_urls");
$queryI = "SELECT * FROM `Images` WHERE `id` = '".$_GET['pid']."'";
$resultI = mysql_query($queryI);
$image_id = mysql_result($resultI, 0, 'uid');
$image = mysql_result($resultI, 0, 'name');
$user['name'] = mysql_result($resultI, 0, 'user');
if($user['name']!="")$user['link']='<a href="http://q.parkr.me/user/'.$user['name'].'">@'.$user['name'].'</a>';
$suffix = "-iphone";
$nameE = explode(".", $image);
$name = $nameE[0] . $suffix .".". $nameE[1];
$image = "imageupload/".$name;
$info = getimagesize($image);

if(strstr($_SERVER['HTTP_USER_AGENT'], "iPhone")){$iphone = true;}else{$iphone = false;}
$query = "SELECT * FROM `ImageComments` WHERE `alive`=1 AND `image_id` = ".$image_id." ORDER BY `added` DESC";
$result = mysql_query($query);
$comments = "";
for($i=0; $i<mysql_num_rows($result); $i++){
	//Build output
	$name = mysql_result($result, $i, 'user');
	$added = mysql_result($result, $i, 'added');
	$comment = mysql_result($result, $i, 'comment');
	
	$comments .= '<div class="comment_">
		<span class="name">'.$name.'</span>
		<span class="other"> wrote this comment at </span>
		<span class="added">'.$added.'</span><br /><br />
		<span class="comment">'.$comment.'</span>
	</div>';
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
	<script type="text/javascript">
	<!--
	function post(){
		var image_id = <?php echo $image_id; ?>;
		$.ajax({
		   type: "POST",
		   url: "add.php",
		   data: "",
		   success: function(msg){
		     alert( "Data Saved: " + msg );
		   }
		 });
	}
	
	//"id="+image_id+"&user="+$(".user").html+"&text="+$(".comment_add")-->
	</script>
</head>
<body<?php if($iphone){ echo ' onorientationchange="resize(); return false;"';} ?>><!--  oncontextmenu="return false;" -->
	<div id="container" style="width:<?php echo ($info[0]+600); ?>px;">
		<div id="imagee" style="width:<?php echo $info[0] ?>px;float:left;">
			<div id="header"><a href="http://q.parkr.me/">Q.Parkr.Me</a></div>
			<div id="image-container">
				<a href="<?php echo "/view/".$_GET['pid']."/" ?>"><img id="image" <?php echo 'src="/'. $image . '" '; echo $info[3]; ?> /></a>
				<!--<a href="<?php echo "/".$image ?>">View the image</a> -->
			</div>
			<div id="caption"><?php echo $check[5]; ?></div>
			<div id="footer">Uploaded by <?php echo $user['link'] ?><br>
			</div>
				<?php if(!$iphone){ echo "<div id=\"bgimg\">&nbsp;</div>\n"; } ?>
		</div>
		<div id="comments" style="float:left;width:600px;">
			<!-- Time for AJAX! -->
			<form id="form1" class="comment_" name="form1" method="post" action="/add.php"><!-- action="/add.php" -->
				Add a comment:<br /><br />
				<input type="hidden" name="image_id" id="image_id" value="<?php echo $image_id ?>">
				<input type="hidden" name="image_spec_id" id="image_spec_id" value="<?php echo $_GET['pid']; ?>">
				<table width="180" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>User:</td>
						<td><input type="text" name="user" id="user" /></td>
					</tr>
					<tr>
						<td>Comment:</td>
						<td><textarea name="comment_add" id="comment_add" cols="45" rows="5"></textarea></td>
 					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="submit" id="submit" value="Submit" onclick="post();" /></td>
					</tr>
				</table>
			</form>
			<!-- Output comments -->
			<?php echo $comments; ?>
		</div>
	</div>
	<script type="text/javascript">
		<?php if($iphone){ echo "resize();\n"; }else{ echo "repositionBg();\n"; } ?>
	</script>
</body>
</html>