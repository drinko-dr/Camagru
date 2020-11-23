<?php

use config\DataBase;

defined('INDEX') OR die('Прямой доступ к странице запрещён!');


if ( empty($db) )
	$db = new DataBase();

$user = $db->getCurrentUser();
$login = $user[0]['login'];
$name = $user[0]['name'];
if ($_GET['option'] != $name)
	header("Location: /$name/settings");
$email = $user[0]['email'];
$old_pwd =  null;
$new_pwd =  null;

if ( isset( $_POST['name'] ) && isset( $_POST['email'] ) ){
	$name = htmlspecialchars( $_POST['name'] );
	$email = htmlspecialchars( $_POST['email'] );
	$error = false;
	$error_name = "";
	$error_email = "";
	$error_old_pwd = "";

	if ($name == '') {
		$error = true;
		$error_name = "Nick-name не может быть пустым";
	}

	if ($name != $user[0]['name'] && $name == $db->getUserByNickName($name)[0]['name']){
		$error = true;
		$error_name = "Пользователь с таким ником уже существует.";
	}
	if ($email != $user[0]['email'] && $db->checkUserEmail($email)){
		$error = true;
		$error_email = "Эта почта уже используется";
	}

	if ($email == "" || !preg_match("/@/", $email)){
		$error = true;
		$error_email = "Введите корректный E-mail";
	}

	if ( !empty( $_POST['old-password'] ) && empty( $_POST['new-password'] )  ) {
		$error = true;
		$error_new_pwd = "Поле не может быть пустым";
	}

	if ( empty( $_POST['old-password'] ) && !empty( $_POST['new-password'] ) ){
		$error = true;
		$error_old_pwd = "Поле не может быть пустым";

	}

	if ( !empty( $_POST['old-password'] ) && !empty( $_POST['new-password'] ) ) {
		$old_pwd = htmlspecialchars( $_POST['old-password'] );
		$new_pwd = htmlspecialchars( $_POST['new-password'] );

		if (hash('whirlpool',$old_pwd ) != $user[0]['password']){
			$error = true;
			$error_old_pwd = "Старый пароль не верный";
		}
	}

	if (!$error){
		if ($new_pwd != null)
			$db->upDateUser($name, $email, hash('whirlpool', $new_pwd ) );
		else
			$db->upDateUser($name, $email, null );
		$_SESSION["name"] = $name;
		$_SESSION['error'] = 1;
		$_SESSION['msg'] = "Сохранено успешно.";
	}

}

$db = null;
