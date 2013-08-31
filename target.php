<?php
include "image.php";
include "db.inc.php";
include "functions.inc.php";
$post = " ";
foreach ($_POST as $key => $value) { 
    $_POST[$key] = mysql_real_escape_string($value); 
	$post .= "$key:$value\n";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>posted</title>
</head>
<body>
<?php
$filename=basename($_FILES['media']['name']);
$extension = getExtension($filename);
$extension = strtolower($extension);
if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
	{
	//print error message
	echo '<h1>Unknown extension!</h1>';
	}
else
	{
	if ($extension == "jpg") { $mime = "image/jpeg"; }
	if ($extension == "jpeg") { $mime = "image/jpeg"; }
	if ($extension == "png") { $mime = "image/png"; }
	if ($extension == "gif") { $mime = "image/gif"; }
	
	$image_name=time(). '_'. rand(0,9). rand(0,9). rand(0,9). rand(0,9). '.'.$extension;
	$target = $_SERVER['DOCUMENT_ROOT'] . "/imageupload/". $image_name; 
	
	
	// WHO POSTED THIS?!
	$username = "";
	if($_POST['username'] && $_POST['username'] != ""){
		$username = $_POST['username'];
	}else{
		if(stristr($_POST['message'], "#iparkr") || stristr($_POST['message'], "#parkrsimgs")){
			$username = 'parkr';
		}elseif(stristr($_POST['message'], "#idp")){
			$username = 'deparkr';
		}else{
			$username = 'parkr';
		}
	}
	/*
	// Open the original image.
    $original = imagecreatefromjpeg($_FILES['media']['tmp_name']) or die("Error Opening original (<em>".$_FILES['media']['tmp_name']."</em>)");
    list($width, $height, $type, $attr) = getimagesize($_FILES['media']['tmp_name']);
    // Determine new width and height.
    $newWidth = ($width/1.5);
    $newHeight = ($height/1.5); 
    // Resample the image.
    $tempImg = imagecreatetruecolor($newWidth, $newHeight) or die("Cant create temp image");
    imagecopyresized($tempImg, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) or die("Cant resize copy");
    // Save the image.
    imagejpeg($_FILES['media']['tmp_name'], $target, 100) or die("Cant save image");
    // Clean up.
    imagedestroy($original);
    imagedestroy($tempImg); */

	$ok=1; 
	if(move_uploaded_file($_FILES['media']['tmp_name'], $target)) 
	{
		$next = generateShort($image_name);
		
		mysql_query("INSERT into Images (id, name, user, mime, caption) values ('$next', '$image_name', '$username', '$mime', '". trim($_POST['message']) ."')",$db);
		
		if($mime=="image/jpeg"){
			resizeImage($image_name, "imageupload/", "-iphone", 1.5, 100);
		}
		
		if (isset($_GET['slash'])) { $next .= "/"; }
		
		 $to = "me@parkr.me";
		 $subject = "Q.Parkr.Me: Image Uploaded";
		 $body = "Hello,\n\n\tAn image has been uploaded to http://q.parkr.me. View it here: http://q.parkr.me/view/".$next."\n\nSincerely,\n\tYour Server\n\n\n".$post;
		 if (mail($to, $subject, $body)) {
			//echo("<p>Message successfully sent!</p>");
		 }else {
			//echo("<p>Message delivery failed...</p>");
		 }
		
		echo "<mediaurl>http://q.parkr.me/view/". $next. "</mediaurl>";
		echo "<br />Your file has been uploaded as http://q.parkr.me/view/". $next;
	} 
	else {
	echo "Sorry, there was a problem uploading your file.";
	}
}
?>
</body>
</html>
