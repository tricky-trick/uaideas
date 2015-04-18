<?php
    if (isset($_COOKIE['USER_IN'])) {
        header("Location: main.php");
    }
?>


<!DOCTYPE html>
<head lang="ua">
    <meta charset="UTF-8">
    <title>Пізнай Україну</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content="Україна,замки,фортеці,подорожі,відпочинок,туризм,подорожувати,провести час,ресторани,кафе,актиний відпочинок">
    <link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32">
    <link rel="stylesheet" type="text/css" href="css/background.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<!--    <link rel="stylesheet" href="css/reset.css">-->
<!--    <link rel="stylesheet" href="css/style.css">-->
<!--    <script src="js/modernizr.js"></script>-->
    <script>
        var height = window.innerHeight;
        var width = window.innerWidth;
        if(width < height){
            link=document.createElement('link');
            link.href='css/mloginpage.css';
            link.rel='stylesheet';
        }
        else{
            link=document.createElement('link');
            link.href='css/loginpage.css';
            link.rel='stylesheet';
        }
        document.getElementsByTagName('head')[0].appendChild(link);
    </script>

    <!--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.css" />-->
    <!--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />-->
<!--    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script type="text/javascript" src="js/loginpage.js"></script>
    <script type="text/javascript" src="js/loginpageajax.js"></script>
    <script type="text/javascript" src="js/facebook.js"></script>
    <script type="text/javascript" src="js/googleplus.js"></script>
<!--    <script type="text/javascript" src="//vk.com/js/api/openapi.js"></script>-->
<!--    <script type="text/javascript">-->
<!--        VK.init({-->
<!--            apiId: 4827044-->
<!--        });-->
<!--    </script>-->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.js"></script>-->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>-->
</head>
<body style="overflow-y: auto; overflow-x: hidden; font-family:  tahoma, arial, verdana, sans-serif, 'Lucida Sans';">
<!--<div class="cd-background-wrapper">-->
<!--    <figure class="cd-floating-background">-->
<!--        <img src="img/sunflower_field.jpg" alt="image-1">-->
<!--        <img src="img/sunflowers.png" alt="image-2">-->
<!--        <img src="img/sunflower_2.png" alt="image-3">-->
<!--    </figure>-->
<!--</div>-->
    <div id="bg-0"></div>
    <div id="bg-1"></div>
    <div id="bg-2"></div>
    <div id="bg-3"></div>
    <div id="bg-4"></div>
    <div id="bg-5"></div>
    <div id="bg-6"></div>
    <div id="bg-7"></div>
    <div id="bg-8"></div>
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
                <input id="reg-form-name" maxlength="50" onkeypress="escapeSpecialSymbols('reg-form-name', event)">
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
                <input id="about-us-link" type="button" value="Про проект">
<!--                <input id="login-vkontakte" type="button" value="vk   Увійти з Vkontakte">-->
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
                <a id="reg-rules" class="login-label-input" style="text-decoration: underline; cursor: pointer;" href="terms.php" target="_blank">Згідний з правилами</a>
            </div>
        </div>
        <div style="color: white; width:10%; text-align: center; margin-left: 45%; font-size: 12px;top:95%; position: absolute;">
            SeeUA © 2015
        </div>

    </div>
    <div id="footer">
<!--        <img src="img/logo_see.png" id="logo">-->

    </div>
    <div id="dialog">
        <p></p>
    </div>
<script src="js/main.js"></script> <!-- Resource jQuery -->
</body>
</html>