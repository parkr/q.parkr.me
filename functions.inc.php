<?php
function string2dec ($string) 
{ 
    global $error; 
    $decimal = 0; 

    $charset = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
    $charset = substr($charset, 0, 62); 

    do {

       $char   = substr($string, 0, 1); 
       $string = substr($string, 1); 

       $pos = strpos($charset, $char); 
       if ($pos === false) { 
          $error[] = "Illegal character ($char) in INPUT string"; 
          return false; 
       } // if 

       $decimal = ($decimal * 36) + $pos; 

    } while($string <> null); 
 
    return $decimal; 
     
} // string2dec 

function dec2string ($decimal) 
{
    global $error; 
    $string = null; 

    $charset = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_-'; 
    $charset = substr($charset, 0, 62); 

    if (!ereg('(^[0-9]{1,16}$)', trim($decimal))) { 
       $error['dec_input'] = 'Value must be a positive integer'; 
       return false; 
    } // if
    $decimal = (int)$decimal;  

    do {

       $remainder = ($decimal % 36); 

       $char   = substr($charset, $remainder, 1); 
       $string = "$char$string"; 

       $decimal   = ($decimal - $remainder) / 36; 

    } while ($decimal > 0); 

    return $string; 

} // dec2string 

function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}

function twitterify($ret) {
  $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
  $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
  $ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
  $ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
return $ret;
}

function generateShort($image_name){
	do{
		include_once('db.inc.php');
		$salt = md5("1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
		$short = hash('MD5', $salt.$image_name);
		$short = $short . $salt;
		$rand = rand(1, 256)%4;
		for ( $i = 0; $i < $rand; $i++ ) {
		  $short = hash('md5',$short );
		}
	}while(mysql_num_rows(mysql_query("SELECT `id` FROM `parkrm04_urls`.`Images` WHERE `id` = '".substr($short,4,3)."'")) > 0);
	return substr($short, 4, 3);
}

?>

