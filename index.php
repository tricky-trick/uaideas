<?php
    if (isset($_COOKIE['USER_IN'])) {
        header("Location: main.php");
    }
?>


<!DOCTYPE html>
<head lang="ua">
    <meta charset="UTF-8">
    <title>Пізнай Україну</title>
    <meta name=viewport content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no">
    <meta name="HandheldFriendly" content="true">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content="Україна,замки,фортеці,подорожі,відпочинок,туризм,подорожувати,провести час,ресторани,кафе,актиний відпочинок">
    <link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<!--    <link rel="stylesheet" href="css/reset.css">-->
<!--    <link rel="stylesheet" href="css/style.css">-->
<!--    <script src="js/modernizr.js"></script>-->
    <script>
        var mloginpage_css;
        var mbackground_css;
        var mloginpage_js;

        mloginpage_css=document.createElement('link');
        mloginpage_css.rel='stylesheet';

        mbackground_css=document.createElement('link');
        mbackground_css.rel='stylesheet';

        mloginpage_js=document.createElement('script');
        mloginpage_js.rel='text/javascript';

        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            mloginpage_css.href='css/mloginpage.css';
            mbackground_css.href='css/mbackground.css';
            mloginpage_js.src='js/mloginpage.js';
        }
        else{
            mloginpage_css.href='css/loginpage.css';
            mbackground_css.href='css/background.css';
            mloginpage_js.src='js/loginpage.js';
        }
        document.getElementsByTagName('head')[0].appendChild(mloginpage_css);
        document.getElementsByTagName('head')[0].appendChild(mbackground_css);
        document.getElementsByTagName('head')[0].appendChild(mloginpage_js);
    </script>

    <!--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.css" />-->
    <!--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />-->
<!--    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>-->
<!--    <script type="text/javascript" src="js/loginpage.js"></script>-->
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
    <div id="bg-0"></div>
    <div id="fb-root"></div>
    <div id="header">
    </div>

    <div id="content">

        <div id="reg-form">
            <span class="login-text-header">Реєстрація</span>
            <table>
                <tbody>
                    <tr>
                        <td align="center"><input id="reg-form-email" type="email" maxlength="50" placeholder="* Поштова скринька"></td>
                    </tr>
                    <tr>
                        <td align="center"><input id="reg-form-name" maxlength="50" onkeypress="escapeSpecialSymbols(event)" placeholder="* Ім'я (можна і прізвище)"></td>
                    </tr>
                    <tr>
                        <td align="center"><input id="reg-form-password" type="password" maxlength="20" placeholder="* Пароль"></td>
                    </tr>
                    <tr>
                        <td align="center"><input id="reg-form-repeat-password" type="password" maxlength="20" placeholder="* Пароль ще раз"></td>
                    </tr>
                    <tr>
                        <td align="center">
                            <input id="reg-form-submit" type="button" value="OK">
                            <input id="reg-form-cancel" type="button" value="Скасувати">
                        </td>
                    </tr>
                    <tr>
                        <td align="center"><span id="reg-label-error" class="login-label-error" style="margin-left: 5%"></span></td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div id="login-form">
            <span class="login-text-header">Вхід</span>
            <table>
                <tbody>
                    <tr>
                        <td align="center"><input id="login-facebook" type="button" value="f   Увійти з Facebook"></td>
                        <td align="center"><input id="login-form-email" type="email" maxlength="50" placeholder="Поштова скринька..."></td>
                    </tr>
                    <tr>
                        <td align="center">
                            <input id="login-google" type="button" value="G+   Увійти з Google">
                        </td>
                        <td align="center">
                            <input id="login-form-password" type="password" maxlength="20" placeholder="Пароль...">
                        </td>
                    </tr>
                    <tr>
                    <td align="center">
                        <input id="login-demo" type="button" value="Демо вхід">
                    </td>
                    <td align="center">
                        <input type="submit" id="login-form-submit" value="Увійти">
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <input id="about-us" type="button" value="Про проект">
                    </td>
                    <td align="center">
                        <input id="login-form-reg" type="button" value="Реєстрація">

                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <img id="spinner-ideas-load" src="img/spinner_big.gif" style="width: 10%; height: auto; display: none" />
                    </td>
                    <td align="center">
                        <span id="login-label-error" class="login-label-error">Помилка входу. Спробуйте ще раз</span>
                    </td>
                </tr>
                <tr>
                    <td align="center"></td>
                    <td align="center">
                        <span class="login-label-link-pwd">Забули пароль?</span>
                        <br>
                        <input id="login-form-rules-checkbox" type="checkbox">
                        <a id="reg-rules" class="login-label-input" style="text-decoration: underline; cursor: pointer;" href="terms.php" target="_blank">Згідний з правилами</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="footer">
            SeeUA © 2015
        </div>

    </div>
    <div id="footer">
<!--        <img src="img/logo_see.png" id="logo">-->

    </div>
    <div id="dialog">
        <p></p>
    </div>
</body>
</html>