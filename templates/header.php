<?php
/**
 * Header file
 * @since 1.0.0
 */
?><!DOCTYPE html>

<html class="no-js" lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title>Camaguru</title>
    <link rel="stylesheet" href="../accets/css/style.css">
    <script type="text/javascript" src="../accets/js/main.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Muli%7CRoboto:400,300,500,700,900" rel="stylesheet">
<!--    --><?php //wp_head(); ?>

</head>
    <body>
    <!-- #site-header -->
        <header id="site-header" class="header-group">
            <a id="overlay" class="overlay" style=""></a>
            <a id="overlayMenu" class="overlay-prof-menu" style=""></a>
                <div id="pop-up" class="pop-up">
                    <a id="close-popUp" class="close" title="Закрыть" ></a>
                </div>
                <div class="header-container all-content">
                    <div class="logo">
                        <img src="../images/logo.png">
                    </div>
                    <div class="menu">
                        <ul>
                            <li>
                                <a href="home">Home</a>
                            </li>
                            <li>
                                <a href="home">Gallery</a>
                            </li>
                            <li>
                                <a href="home">About</a>
                            </li>
                        </ul>
                    </div>
                    <div class="search">
                        <form action="/search" method="post">
                            <input type="text" name="search" placeholder="Search...">
                            <button type="submit" name="sendSearch">Найти</button>
                        </form>
                    </div>
                    <div id="login" class="sing-group">
                        <?php if ($_SESSION["log"] === true) : ?>
                        <div id="my-account" class="my-account">
                            <div class="account-name"><?php echo $_SESSION["login"] ?></div>
                            <img src="../images/login.png" alt="<?php echo $_SESSION["login"] ?>">
                            <div class="account-arrow"></div>
                        </div>
                        <div id="profile-menu" class="profile-menu">
                            <a href="/<?php echo $_SESSION["login"] ?>">Моя страница</a>
                            <a href="/<?php echo $_SESSION["login"] ?>/settings">Настройки</a>
                            <form action="/sing-in" method="post">
                                <input type="submit" class="exit" name="exit" value="Выйти">
                            </form>
                        </div>
                        <?php else :?>
                        <div class="sing-in">
                            <a id="sing-in">Sing-in</a>
                        </div>
                        <div class="sing-up">
                            <a href="/sing-up" id="sing-up">Sing-up</a>
                        </div>
						<?php endif; ?>
                    </div>
                </div>
        </header>
