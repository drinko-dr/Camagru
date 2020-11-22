<?php
session_start();
define("INDEX", "");
require_once "config/database.php";
require_once './includes/setup.php';
//include './templates/index.php';
//include './includes/auth.php';
//include './includes/sing-up.php';
require_once './includes/functions.php';
//$db = new DataBase();
switch ($_GET['option']){
	case if_user($_GET['option']):
		require_once ("./templates/page.php");
		break;

	case "":
	case "home":
		require_once("./templates/home.php");
		break;

    case "sing-up":
		get_sing_up();
        break;

	case "sing-in":
		get_sing_in();
		break;

	case "save_img":
		require_once ("./includes/save_img.php");
		break;

	case "auth":
		require_once ("./includes/auth.php");
		break;

	case "reset-pwd":
		require_once ("./includes/reset-pwd.php");
		break;

	default:
		require_once("./templates/error.php");
        break;
}
	function if_user($name){
		$db = new DataBase();
		$user = $db->getUserByNickName($name);
		if ( $user[0]["name"] == $name ){
			$db = NULL;
			return true;
		}
		else{
			$db = NULL;
			return false;
		}
	}
?>