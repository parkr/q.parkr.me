<?php

include('image.php');
include('db.inc.php');
$query = "SELECT * FROM `Images` ORDER BY `uid`";
$result = mysql_query($query);

for($i=0; $i<mysql_num_rows($result); $i++){
	$imagename = mysql_result($result, $i, 'name');
	$path = "imageupload/";
	$suffix = "-iphone";
	$imageE = explode(".", $imagename);
	//echo $path.$imageE[0].$suffix.".".$imageE[1];
	if(!file_exists($path.$imageE[0].$suffix.".".$imageE[1])){
		echo "Doesn't exist<br>";
		resizeImage($imagename, $path, $suffix, 1.5, 100);
	}else{
		echo "Exists<br>";
	}
}

?>
