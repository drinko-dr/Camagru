<?php
    get_header();
    global $login, $name, $error_name, $email, $error_email, $error_old_pwd, $error_new_pwd;
?>
<script src="/accets/js/webCam.js"></script>

<div class="profile-edit">
	<div class="col-sm-3">
		<div class="avatar">
			<a>
				<img usemap="#workmap" width="200" height="200" src="/images/login.png">
                <map name="workmap">
                    <area coords="10,10,20,20" alt="Computer" href="/">
                </map>
			</a>
		</div>
		<div class="photo-edit">
            <div class="select">
                <span>Редактировать</span>
                <span class="account-arrow"></span>
                <div class="selected">
                    <button id="new-photo">Сделать фото</button>
                    <form method="post">
                        <label for="photo" class="chous">Загрузить фото</label>
                        <input type="file" class="load" id="photo" name="photo"/>
                    </form>
                </div>
            </div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="title">Настройки профиля</div>
		<form method="post" action="/<?php echo $name; ?>/settings">
			<div class="form-group">
				<div class="col-sm-3 control-label">
					<label for="login">Логин:</label>
				</div>
				<div class="col-sm-8 controls">
					<input type="text" name="login" readonly aria-invalid="false" id="login" value="<?php echo $login; ?>">
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-3 control-label">
					<label for="name">Nick-name:</label>
				</div>
				<div class="col-sm-8 controls">
					<input type="text" name="name" required  id="name" value="<?php echo $name; ?>">
					<span style="color: red;display: table;"><?php echo $error_name; ?></span>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-3 control-label">
					<label for="email">E-mail:</label>
				</div>
				<div class="col-sm-8 controls">
					<input type="text" name="email" required id="email" value="<?php echo $email; ?>">
					<span style="color: red; display: table;"><?php echo $error_email; ?></span>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-3 control-label">
					<label for="old-password">Старый пароль:
				</div>
				<div class="col-sm-8 controls">
					<input type="text" name="old-password" id="old-password" value="">
					<span style="color: red;display: table;"><?php echo $error_old_pwd; ?></span>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-3 control-label">
					<label for="new-password">Новый пароль:
				</div>
				<div class="col-sm-8 controls">
					<input type="text" name="new-password" id="new-password" value="">
                    <span style="color: red;display: table;"><?php echo $error_new_pwd; ?></span>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-8 controls">
					<input name="submit" id="submit" type="submit" value="Сохранить">
				</div>
			</div>
		</form>
	</div>
</div>