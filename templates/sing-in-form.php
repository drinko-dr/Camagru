<?php

require_once ("includes/auth.php");
if ($_SESSION["log"] == true) {
	header("Location: /");
	exit();
}
get_header(); ?>
		<div class="main-pos">
			<form id="sing-in-form" method="post">

				<div class="form-group">
					<div class="col-sm-3 control-label">
						<label for="name">Имя пользователя

					</div>
					<div class="col-sm-8 controls">
						<input id="login" type="text" name="login">

					</div>
				</div>


				<div class="form-group">
					<div class="col-sm-3 control-label">
						<label for="password">Пароль

					</div>
					<div class="col-sm-8 controls">
						<input id="password" type="password" name="password">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-8 controls">
						<input name="sing-in" type="submit" id="sendForm" value="Sing In">
					</div>
				</div>
				<a href='reset-pwd'>Забыли пароль?</a>
			</form>

		</div>
	<?php get_footer();