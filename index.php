<?php
    if (isset($_COOKIE['USER_IN'])) {
        header("Location: main.php");
    }
?>


<!DOCTYPE html>
<head lang="ua">
    <meta charset="UTF-8">
    <title>Збудуй майбутнє сам!</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" type="text/css" href="css/background.css" />
    <link rel="stylesheet" type="text/css" href="css/loginpage.css" />
    <!--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.css" />-->
    <!--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />-->
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="js/loginpage.js"></script>
    <script type="text/javascript" src="js/loginpageajax.js"></script>
    <script type="text/javascript" src="js/facebook.js"></script>
    <script type="text/javascript" src="js/googleplus.js"></script>
    <script type="text/javascript" src="//vk.com/js/api/openapi.js"></script>
    <script type="text/javascript">
        VK.init({
            apiId: 4827044
        });
    </script>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.js"></script>-->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>-->
</head>
<body>
<div id="fb-root"></div>
    <div id="header">

    </div>

    <div id="content">

        <div id="reg-form">
            <span class="login-text-header">Реєстрація</span>
            <div class="login-block" style="width: 100%">
                <span class="login-label-input" style="margin-left: 5%">* Поштова скринька</span>
                <br>
                <input id="reg-form-email" type="email" maxlength="50">
                <br>
                <br>
                <span class="login-label-input" style="margin-left: 5%">* Ім'я (можна і прізвище)</span>
                <br>
                <input id="reg-form-name" maxlength="50">
                <br>
                <br>
                <span class="login-label-input" style="margin-left: 5%">* Пароль</span>
                <br>
                <input id="reg-form-password" type="password" maxlength="20">
                <br>
                <br>
                <span class="login-label-input" style="margin-left: 5%">* Пароль ще раз</span>
                <br>
                <input id="reg-form-repeat-password" type="password" maxlength="20">
                <br>
                <br>
                <input id="reg-form-submit" type="button" value="OK">
                <input id="reg-form-cancel" type="button" value="Скасувати">
                <br>
                <span id="reg-label-error" class="login-label-error" style="margin-left: 5%"></span>
            </div>

        </div>

        <div id="login-form">
            <span class="login-text-header">Вхід</span>

            <div id="login-block-left" class="login-block">
                <br>
                <input id="login-facebook" type="button" value="f   Увійти з Facebook">
                <br>
                <br>
                <input id="login-google" type="button" value="G+   Увійти з Google">
                <br>
                <br>
                <input id="login-vkontakte" type="button" value="vk   Увійти з Vkontakte">
            </div>
            <div id="login-block-right" class="login-block">
                <span class="login-label-input">Поштова скринька</span>
                <br>
                <input id="login-form-email" type="email" maxlength="50">
                <br>
                <br>
                <span class="login-label-input">Пароль </span>
                <span class="login-label-link-pwd">(Забули пароль?)</span>
                <br>
                <input id="login-form-password" type="password" maxlength="20">
                <br>
                <br>
                <input type="submit" id="login-form-submit" value="Увійти">
                <br>
                <span id="login-label-error" class="login-label-error">Помилка входу. Спробуйте ще раз</span>
                <br>
                <br>
                <input id="login-form-reg" type="button" value="Реєстрація">
                <br>
                <br>
                <input id="login-form-rules-checkbox" type="checkbox">
                <span id="reg-rules" class="login-label-input" style="text-decoration: underline; cursor: pointer;">Згідний з правилами</span>
            </div>
        </div>

        <ul class="cb-slideshow">
            <li>
                <span>Image 01</span>
                <!--<div><h3>Свідомість</h3></div>-->
            </li>
            <li>
                <span>Image 02</span>
            </li>
            <li>
                <span>Image 03</span>
            </li>
            <li>
                <span>Image 04</span>
            </li>
            <li>
                <span>Image 05</span>
            </li>
            <li>
                <span>Image 06</span>
            </li>
        </ul>
    </div>
    <div id="footer">

    </div>
</body>
</html>