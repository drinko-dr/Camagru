<?php
    if ( !$_POST ){
		header("Location: /");
    }
    if (isset($_POST["exit"])){
        $_SESSION["log"] = false;
        session_destroy();
        header("Location: /");
    }
    if (isset($_POST["sing-in"])){
        $db = new DataBase();
        $user = $db->getUser($_POST["login"]);
        if ($user == NULL){
           echo "false";
           return ;
        }
        $passDB = $user[0]["password"];
        $pass = MD5($_POST["password"]);
        if ($passDB == $pass){
            $_SESSION["log"] = true;
            $_SESSION["login"] = $_POST["login"];
        }else{
            echo "false";
            return ;
        }
    }