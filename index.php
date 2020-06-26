<?php
session_start();
define("INDEX", "");
include "config/database.php";
include './includes/setup.php';
//include './templates/index.php';
//include './includes/sing-in.php';
//include './includes/sing-up.php';
include './includes/functions.php';
//$db = new DataBase();
switch ($_GET[option]){
	case if_user($_GET[option]):
			include ("./templates/page.php");
		break;

	case "":
	case "home":
		include("./templates/home.php");
		break;

    case "sing-up":
        include ("./includes/sing-up.php");
        break;

	case "sing-in":
		include ("./includes/sing-in.php");
		break;

	default:
		include("./templates/error.php");
        break;
}

	function if_user($login){
		$db = new DataBase();
		$user = $db->getUser($login);
		if ( $user[0]["login"] == $login ){
			$db = NULL;
			return true;
		}
		else{
			$db = NULL;
			return false;
		}
	}
?>