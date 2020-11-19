<?php
defined('INDEX') OR die('Прямой доступ к странице запрещён!');


    if (isset($_POST["exit"])){
        $_SESSION["log"] = false;
        session_destroy();
        header("Location: /");
        exit();
    }
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
		$ajax = true;
	}else
		$ajax = false;

    if (isset($_POST["sing-in"])) {
		$db = new DataBase();
		$user = $db->getUser(trim(htmlspecialchars($_POST["login"])));

		if ($user == NULL) {
			if (!$ajax) {
				$_SESSION['error'] = 3;
				$_SESSION['msg'] = "Неверный имя пользователя или пароль!";
			}else
				echo "false";
			return ;
		}

		$passDB = $user[0]["password"];
		$pass = hash("whirlpool", htmlspecialchars($_POST["password"]));
		if ($passDB == $pass) {
			$_SESSION["log"] = true;
			$_SESSION["name"] = $user[0]['name'];
			$_SESSION["login"] = $user[0]['login'];
			$_SESSION['error'] = 0;
		} else {
			if (!$ajax) {
				$_SESSION['error'] = 3;
				$_SESSION['msg'] = "Неверный имя пользователя или пароль!";
			}else
				echo "false";
			return ;
		}
	}
