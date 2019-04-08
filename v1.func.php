<?php
function getMail(){
	$curl = curl("https://faketempmail.com/");
	return fetchCookies($curl[0]);
}
function checkMsg($mail){
	$curl = curl("https://faketempmail.com/ajax/getmails.php", "usermail=".$mail);
	return $curl[1];
}
function readMail($mail, $mailid){
	$curl = curl("https://faketempmail.com/ajax/readmail.php", "usermail=".$mail."&mailid=".$mailid);
	return $curl[1];
}
?>
