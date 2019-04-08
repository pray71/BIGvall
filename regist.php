<?php
require "./lib.php";
$reff = "ZM654CIID";
echo ">>>> : "; $mail = trim(fgets(STDIN));
echo RegisterBigToken($mail, $reff);
?>