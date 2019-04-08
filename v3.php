<?php
$i = 1;
while(1){
    $mail = getMail();
    echo "[".$i++."]>> ".$mail['currentmail']."\n";
    echo "\t".RegisterBigToken($mail['currentmail'], $reff)."\n";
    echo "\t{!} GETTING INFORMATION..\n";
    sleep(3);
    $chkMsg = json_decode(checkMsg($mail), true);
    if(!$chkMsg['count'] == 0){
        $eXt = explode("@", $mail['currentmail']);
    		$read = readMail($mail);
    		preg_match('#href="https://gobigtoken.page.link/(.*?)"#si', $read, $data);
    	    isset($data[1]) ? $dataz = "https://gobigtoken.page.link/".$data[1] : $dataz = false;
    	    if(preg_match("/consumer.bigtoken.com/i", $dataz)){
    			$bigToken = get_string(urldecode($dataz), "&amp;ofl=", "@".$eXt[1])."@".$eXt[1];
    			$parseUrl = ParseUrl($bigToken);
    	    }else{
    	    	$bigToken = getOriginalURL($dataz);
    	    	$parseUrl = ParseUrl($bigToken);
    	    }
    	    if(isset($parseUrl['code'])){
    	    	echo "\t".$mail['currentmail']." => ".Verif($bigToken, $parseUrl['code'], $parseUrl['email'])."\n";
    	    }else{
    	    	echo "\tNo Code, Skipping Verif\n";
    	    }
    	
    }else{
    	echo "\tno inbox maybe not yet\n";
    }
}
?>
