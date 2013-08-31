<?php

include "image.php";
include "db.inc.php";
include "functions.inc.php";

function exists($dir, $imgname, $suffix){
	$nameE = explode(".", $imgname);
	$newname = $nameE[0] . $suffix . "." . $nameE[1];
	return file_exists($dir.$newname);
}

foreach ($_GET as $key => $value) { 
	$_GET[$key] = mysql_real_escape_string($value); 
}
print_r($_GET);
if(isset($_GET['id'])){
	$id = $_GET['id'];
	if($id=="all"){
		$result = mysql_query("SELECT * FROM Images",$db);
		if($action == "thumb"){
			for($i=0; $i<mysql_num_rows($result); $i++){
				if(mysql_result($result, $i, 'mime')=="image/jpeg"){
					$img = mysql_result($result, $i, 'name');
					$imgPath = "imageupload/";
					$suffix = "-thumb";
					if(!exists($imgPath, $img, $suffix)){
						$newWidth = $newHeight = $_GET['side'];
						$quality = 100;
						echo "<br>createThumbnail($img, $imgPath, $suffix, $newWidth, $newHeight, $quality)";
						createThumbnail($img, $imgPath, $suffix, $newWidth, $newHeight, $quality);
					}else{
						echo "<br>$img's thumbnail exists.";
					}
				}
			}
		}elseif($action == "rotate"){
			for($i=0; $i<mysql_num_rows($result); $i++){
				if(mysql_result($result, $i, 'mime')=="image/jpeg"){
					$img = mysql_result($result, $i, 'name');
					$imgPath = "imageupload/";
					$suffix = "";
					if($_GET['dir'] == "right"){
						$degrees = 90;
					}elseif($_GET['dir'] == "left"){
							$degrees = -90;
					}
					$quality = 100;
					$save = true;
					echo "rotateImage($img, $imgPath, $suffix, $degrees, $quality, $save)\n";
					rotateImage($img, $imgPath, $suffix, $degrees, $quality, $save);
				}
			}
		}else{
			header("Location:http://q.parkr.me/");
		}
	}else{
		$check = mysql_query("SELECT * FROM Images where id = '".$id."'",$db);
		$check = mysql_fetch_row($check);
		if($action == "thumb"){
			if($check[4]=="image/jpeg"){
				$img = $check[3];
				$imgPath = "imageupload/";
				$suffix = "-thumb";
				if(!exists($imgPath, $img, $suffix)){
					$newWidth = $newHeight = $_GET['side'];
					$quality = 100;
					echo "createThumbnail($img, $imgPath, $suffix, $newWidth, $newHeight, $quality)";
					createThumbnail($img, $imgPath, $suffix, $newWidth, $newHeight, $quality);
					header("Location:http://q.parkr.me/view/$id/");
				}else{
					echo "<br>$img's thumbnail exists.";
				}
			}
		}elseif($action == "rotate"){
			if($check[4]=="image/jpeg"){
				$img = $check[3];
				$imgPath = "imageupload/";
				$suffix = "";
				if($_GET['dir'] == "right"){
					$degrees = 90;
				}elseif($_GET['dir'] == "left"){
					$degrees = -90;
				}
				$quality = 100;
				$save = true;
				echo "rotateImage($img, $imgPath, $suffix, $degrees, $quality, $save)";
				rotateImage($img, $imgPath, $suffix, $degrees, $quality, $save);
				header("Location:http://q.parkr.me/view/$id/");
			}
		}else{
			header("Location:http://q.parkr.me/");
		}
	}
}else{
	echo "No id.";
}

?>