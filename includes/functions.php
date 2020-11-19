<?php
defined('INDEX') OR die('Прямой доступ к странице запрещён!');
    function get_header(){
        include './templates/header.php';
    }

/**
 *	Include file footer.php
 */
	function get_footer(){
        include './templates/footer.php';
    }

    function get_sing_in(){
        include './templates/sing-in-form.php';
    }

    function get_sing_up(){
        include './includes/sing-up.php';
    }


    function msg_alert($error, $msg){
		switch ($error){
			case 1:
				$style = "alert-message";
				$alert_heading = "Сообщение";
				break;
			case 2:
				$style = "alert-notice";
				$alert_heading = "Внимание";
				break;
			case 3:
				$style = "alert-error";
				$alert_heading = "Ошибка";
				break;
			default:
				return;
		}

		echo "<div id=\"system-message-container\">
					<div id=\"system-message\">
						<div class=\"alert ". $style ."\">
							<a class=\"close\"></a>
							<h4 class=\"alert-heading\">". $alert_heading ."</h4>
							<div>
								<p>". $msg. "</p>
							</div>
						</div>
					</div>
				</div>";
	$_SESSION['error'] = 0;
	$_SESSION['msg'] = '';
	}

	function check_email($email){
		$db = new DataBase();
		$error_email = false;
		if (!$db->checkUserEmail($email))
			$error_email = true;

		if ($email == "" || !preg_match("/@/", $email))
			$error_email = true;
		$db = null;
		return $error_email;
	}