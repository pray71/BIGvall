<?php
date_default_timezone_set("Asia/Jakarta");
function CreateMail(){
    $baseUrl = "https://waste.email";
    $get = curl($baseUrl."/main.php");
    preg_match('#<b id="my-address">(.*?)</b>#si', $get[1], $mail);
    preg_match('#/json-api.php?(.*?)"#si', $get[1], $update);
    return array("url" => $baseUrl."/main.php?".$mail[1], "mail" => $mail[1], "updatelink" => $baseUrl."/json-api.php".$update[1]);
}
function Save($title, $text){
        $fopen = fopen($title, "a");
        fwrite($fopen, $text);
        fclose($fopen);
    }
function CheckInbox(array $updateLink){
    echo "please wait 15-25 Sec\n";
    $timeout = 50;
    $i = 1;
    do {
        $recheck = curl($updateLink['updatelink']);
        if($i++ == $timeout) echo "skipping no email..\n"; break;
    }while(strpos($recheck[1], "0") !== FALSE);
    $curl = curl("https://waste.email/main.php?".$updateLink['mail']);
    $eXt = explode("@", $updateLink['mail']);
    preg_match('#href="https://gobigtoken.page.link/(.*?)"#si', $curl[1], $data);
    isset($data[1]) ? $dataz = "https://gobigtoken.page.link/".$data[1] : $dataz = false;
    $bigToken = get_string(urldecode($dataz), "&amp;ofl=", "@".$eXt[1])."@".$eXt[1];
    $parseUrl = ParseUrl($bigToken);
    echo "Verifing...\n";
    echo Verif($bigToken, $parseUrl['code'], $parseUrl['email']);
}
function Verif($referer, $code, $mail){
    $headers = array();
        $headers[] = "Host: api.bigtoken.com";
        $headers[] = "User-Agent: Redmi 5A_9_".rand(1,100).".0.".rand(1,50);
        $headers[] = "Accept: application/json";
        $headers[] = "Accept-Language: id,en-US;q=0.7,en;q=0.3";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Content-Type: application/json";
        $headers[] = "X-Requested-With: XMLHttpRequest";
        $headers[] = "X-Srax-Big-Api-Version: 2";
        $headers[] = "Referer: ".$referer;
        $headers[] = "Origin: https://my.bigtoken.com";
        $headers[] = "Connection: keep-alive";
        $payload = array(
            "email" => $mail,
            "verification_code" => $code
        );
        $getVerif = curl("https://api.bigtoken.com/signup/email-verification", json_encode($payload), $headers);
        $res = json_decode($getVerif[1], true);
        if(isset($res['reward_data']['msg'])){
            return "Success Verif\n";
        }else{
            return "Failed Verif\n";
        }
}
function get_string($string, $start, $end){
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];
}
function ParseUrl($url){
    $parts = parse_url($url);
    parse_str($parts['query'], $query);
    return array(
        "code" => $query['code'],
        "email" => $query['email']
    );
}
function curl ($url, $post = 0, $httpheader = 0, $customHeader = 0){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if($post){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    if($httpheader){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    }
    if($customHeader){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $customHeader);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:66.0) Gecko/20100101 Firefox/".rand(1,200).".0");
    curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch);
    if(!$httpcode) return "Curl Error : ".curl_error($ch); else{
        $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        curl_close($ch);
        return array($header, $body);
    }
}
function arrayToCookies($source){
    if(!is_array($source)){
        return "NOT ARRAY!";
    }else{
        return str_replace(array('{"', '"}', '":"', '","'), array('', '', '=', '; '), json_encode($source));
    }
}
function fetchCookies($source) {
    preg_match_all('/^Set-Cookie:\s*([^;\r\n]*)/mi', $source, $matches); 
    $cookies = array(); 
    foreach($matches[1] as $item) { 
        parse_str($item, $cookie); 
        $cookies = array_merge($cookies, $cookie); 
    }
    return $cookies;
}
function getOriginalURL($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // if it's not a redirection (3XX), move along
    if ($httpStatus < 300 || $httpStatus >= 400)
        return $url;

    // look for a location: header to find the target URL
    if(preg_match('/location: (.*)/i', $result, $r)) {
        $location = trim($r[1]);

        // if the location is a relative URL, attempt to make it absolute
        if (preg_match('/^\/(.*)/', $location)) {
            $urlParts = parse_url($url);
            if ($urlParts['scheme'])
                $baseURL = $urlParts['scheme'].'://';

            if ($urlParts['host'])
                $baseURL .= $urlParts['host'];

            if ($urlParts['port'])
                $baseURL .= ':'.$urlParts['port'];

            return $baseURL.$location;
        }

        return $location;
    }
    return $url;
}
function RegisterBigToken($mail, $reff){
    $body = http_build_query(array(
        "password" => "Mazterin312~",
        "monetize" => "1",
        "referral_id" => $reff,
        "email" => $mail
    ));
    $headers = array();
    $headers[] = "Accept: application/json";
    $headers[] = "User-Agent: Redmi 5A_9_".rand(1,100).".0.".rand(1,50);
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    $headers[] = "Host: api.bigtoken.com";
    $headers[] = "Connection: Keep-Alive";
    $headers[] = "Accept-Encoding: gzip";

    $post = curl("https://api.bigtoken.com/signup", $body, $headers);
    $decode = json_decode($post[1], true);
    if(isset($decode['data']['user_id'])){
        echo "Success register | User id : ".$decode['data']['user_id']." | BigId : ".$decode['data']['bigid'];
    }else{
        echo "Failed Register | ".print $post[1];
    }
}
?>
