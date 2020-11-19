<?php
    if (isset($_POST['send'])) {
        $db = new DataBase();
        $username = htmlspecialchars( $_POST['login'] );
        $email = htmlspecialchars( $_POST['email'] );
        $password = htmlspecialchars( $_POST['password'] );
        $re_password = htmlspecialchars( $_POST['re-password'] );
        $_SESSION["login"] = $username;
        $_SESSION["email"] = $email;
        $error = false;
        $error_name = "";
        $error_email = "";
        $error_password = "";
        $error_re_password = "";
        if ($username == '') {
            $error = true;
            $error_name = "Введите имя пользователя";
        }else if ($_POST['login'] == $db->getUser($_POST['login'])[0]["login"]){
            $error = true;
            $error_name = "Такой пользователь уже существует";
        }

        if ($db->checkUserEmail($email)){
            $error = true;
            $error_email = "Эта почта уже используется";
        }

        if ($email == "" || !preg_match("/@/", $email)){
            $error = true;
            $error_email = "Введите корректный E-mail";
        }

        if ($password == "" || $re_password == ""){
            $error = true;
            $error_password = "Введите пароль";
        }

        if ($password != $re_password){
            $error = true;
            $error_re_password = "Пароли не совпадают";
        }

        if ($error != true){
            $res = $db->setUser($username, $email, $password);
            $db = NULL;
            $_SESSION['error'] = 1;
            $_SESSION['msg'] = "Спасибо за регистрацию. Теперь вы можете войти на сайт, используя логин и пароль, указанные при регистрации.";
            header('Location: /sing-in');
            exit();
        }
        $db = NULL;
    }
get_header();
?>

<div class="main-pos">
    <form action="/sing-up" method="post">

        <div class="form-group">
            <div class="col-sm-3 control-label">
                <label for="login">Имя пользователя</label>
            </div>
            <div class="col-sm-8 controls">
                <input id="login" type="text" name="login" value="<?php echo $_SESSION["login"]; ?>">
                <span style="color: red"><?php echo $error_name; ?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <label for="email">E-mail
            </div>
            <div class="col-sm-8 controls">
                <input id="email" type="text" name="email" value="<?php echo $_SESSION["email"]; ?>">
                <span style="color: red"><?php echo $error_email; ?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <label for="password">Пароль</label>
            </div>
            <div class="col-sm-8 controls">
                <input id="password" type="password" name="password">
                <span style="color: red"><?php echo $error_password; ?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <label for="re-password">Подтвердите пароль

            </div>
            <div class="col-sm-8 controls">
                <input id="re-password" type="password" name="re-password">
                <span style="color: red"><?php echo $error_re_password; ?></span>

            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-8 controls">
                <input name="send" type="submit" value="Sing Up">
            </div>
        </div>
    </form>
</div>
