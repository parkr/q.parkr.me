<?php

include "db.inc.php";
include "functions.inc.php";
$post_contents = "";
foreach ($_POST as $key => $value) { 
	$_POST[$key] = mysql_real_escape_string($value); 
	$post_contents .= "$key:$value\n";
}
@mysql_select_db("parkrm04_urls");

$user = $_POST['user'];
$comment = $_POST['comment_add'];
$image_id = $_POST['image_id'];
$image_spec_id = $_POST['image_spec_id'];
//print_r($_POST);

$to = "";
$subject = "Q.Parkr.Me: Comment Added";
$body = "Hello,\n\n\tAn image has been uploaded to http://q.parkr.me. View it here: http://q.parkr.me/comments/".$image_spec_id."/\n\nSincerely,\n\tYour Server\n\n\n".$post_contents;
//if (mail($to, $subject, $body)) {
	//echo("<p>Message successfully sent!</p>");
//}else{
	//echo("<p>Message delivery failed...</p>");
//}

$query = "INSERT INTO  `parkrm04_urls`.`ImageComments` (`cid` ,`image_id` ,`user` ,`added` ,`comment` ,`alive`) VALUES (NULL ,  $image_id,  '$user', CURRENT_TIMESTAMP , '$comment',  '1')";
//echo "<br>".$query."<br>";
mysql_query($query) or die(mysql_error());

header("Location:http://q.parkr.me/comments/$image_spec_id");

?>
