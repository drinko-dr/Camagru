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
            $color = "green";
            if ($res == "error"){
                $color = "red";
            }
            ?>
            <div style="border: solid 3px <?php echo $color ?>">
                <span><?php echo $res ?></span>
            </div>
            <div>
                <a href="/page">Sing IN</a>
            </div>
            <?php
            exit();
        }
        $db = NULL;
    }
?>
<style>
    form{
        display: table-caption;
    }
    label{
        display: block;
        margin: 10px 0px;
    }
    .main-pos{
        width: 10%;
        margin: auto;
    }
</style>
<div class="main-pos">
    <form action="/sing-up" method="post">
        <label for="login">Имя пользователя
            <input id="login" type="text" name="login" value="<?php echo $_SESSION["login"]; ?>">
            <span style="color: red"><?php echo $error_name; ?></span>
        </label>
        <label for="email">E-mail
            <input id="email" type="text" name="email" value="<?php echo $_SESSION["email"]; ?>">
            <span style="color: red"><?php echo $error_email; ?></span>
        </label>
        <label for="password">Пароль
            <input id="password" type="password" name="password">
            <span style="color: red"><?php echo $error_password; ?></span>
        </label>
        <label for="re-password">Подтвердите пароль
            <input id="re-password" type="password" name="re-password">
            <span style="color: red"><?php echo $error_re_password; ?></span>
        </label>
        <input name="send" type="submit" value="Sing Up">
    </form>
</div>
