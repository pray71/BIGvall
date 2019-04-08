<?php
/**
 * @author fb.com/www.zeldin.go.id
 * @package B1GT0ken
**/
require "./lib.php";
$reff = "17OC16O7O"; // REFF
$serverMail = 4; // Server Generate Mail select 1 or 0
echo "[!] STARTED... GENERATING MAIL\n";
switch($serverMail){
	case '1':
		require "./v1.func.php";
		include("./v1.php");
		break;
	case '2':
		require "./v2.func.php";
		include("./v2.php");
		break;
	case '3':
		require "./v3.func.php";
		include("./v3.php");
		break;
	case '4':
		require "./v4.func.php";
		include("./v4.php");
		break;
	default :
		require "./v2.func.php";
		include("./v2.php");
		break;
}
?>
