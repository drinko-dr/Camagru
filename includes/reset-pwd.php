<?php
	if ( isset( $_POST["email"] ) && isset( $_POST["reset"] ) ){

		if ( check_email( $_POST['email'] ) ){
		    $_SESSION['error'] = 2;
		    $_SESSION['msg'] = "Ошибка при восстановлении пароля: Недопустимый адрес электронной почты";
			print_out();
			return ;
		}else{
			$_SESSION['error'] = 1;
			$_SESSION['msg'] = "На ваш адрес электронной почты было отправлено письмо, содержащее проверочный код. Активируйте ссылку для сброса пароля.";
		}

		$db = new DataBase();

		$user = $db->getUserByEmail( htmlspecialchars( $_POST["email"] ) );

		$to = $user[0]["email"];
        $token = hash("md5", $to.time() );
		$login = $user[0]["login"];
		$msg = "На сайте Camagru был сделан запрос на восстановление пароля к вашей учётной записи. Чтобы восстановить пароль вам потребуется перейти на страницу по ссылке ниже.
				<br/>
				<br/>
				http://camaguru/index.php?option=reset-pwd&login=".$login."&token=".$token."
				<br/>
				<br/>
				Спасибо";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$db->setUserMeta($user[0]["id"],"token_time_out", time());
		$db->setUserMeta($user[0]["id"],"token", $token);

		mail($to, "Запрос сброса пароля на сайте Camagru", $msg, $headers);

		$db = null;
		print_out();
	}else if ( isset( $_GET["login"] ) && isset( $_GET["token"] ) ){

		$db = new DataBase();

		$user = $db->getUser( htmlspecialchars( $_GET["login"] ) );

		clear_token($db, $user[0]['id']);

		if ( $db->getUserMeta( $user[0]['id'], "token") != $_GET["token"]) {
			header('Location: /error');
			return;
		}

        $to = $user[0]["email"];
        $login = $user[0]["login"];

        $user_pass = pass_generator();
        $new_pwd = hash("whirlpool", $user_pass);
        $db->upDatePass($login, $new_pwd);

        $msg = "<p>Восстановление пароля завершено. Ваши данные учетной записи:</p><p>Login: ".$login."</p><p>Password: ".$user_pass."</p><p><a href='http://camaguru/sing-in'>Sing-in</a></p>";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($to, "Запрос сброса пароля на сайте Camagru", $msg, $headers);

		$db = null;

		$_SESSION['error'] = 1;
		$_SESSION['msg'] = "Пароль восстановлен.<br />На ваш адрес электронной почты было отправлено письмо, содержащее данные для входа к вашей учётной записи.";
		header('Location: /sing-in');
	}else
		print_out();


	function print_out()
	{
		get_header(); ?>
			<div class="reset">
				<form METHOD="post">
					<p>Пожалуйста, введите адрес электронной почты, указанный в параметрах вашей учётной записи. На него
						будет отправлен специальный проверочный код. После его получения вы сможете ввести новый пароль
						для вашей учётной записи.</p>
                    <div class="form-group">
                        <div class="col-sm-3 control-label">
                            <label for="email">Адрес электронной почты:

                        </div>
                        <div class="col-sm-8 controls">
                            <input type="text" name="email"/>

                        </div>
                        <div class="col-sm-8 controls">
                            <input type="submit" name="reset" value="OK"/>
                        </div>
                    </div>
				</form>
			</div>

		<?php get_footer();
	}

	function clear_token($db, $id){
	    $time = (time() - $db->getUserMeta($id, "token_time_out"));
	    if ($time > 3600)
	        $db->delUserMeta($id,"token");
    }

function pass_generator(){

	$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";

	$max=10;

	$size=StrLen($chars)-1;

	$password=null;

	while($max--)
		$password.=$chars[rand(0,$size)];

	return $password;
}