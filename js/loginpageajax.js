$(document).ready(function() {

    var expires = new Date();
    expires.setTime(expires.getTime() + (7 * 24 * 60 * 60 * 1000));
    document.cookie =  'USER_OFF=true;expires=' + expires.toUTCString();

    /*
    Registration of new user
     */
    $("#reg-form-submit").click(function()
    {
        var mail = $("#reg-form-email").val();
        var name = $("#reg-form-name").val();
        var password = $("#reg-form-password").val();
        var repeatPassword = $("#reg-form-repeat-password").val();
        var checkbox = $("#login-form-rules-checkbox");
        var errorMessage = $("#reg-label-error");

        if(!checkbox.is(":checked")) {
            errorMessage.text("Погодьтеся з правилами реєстрації");
            errorMessage.css("display", "inline");
            checkbox.fadeOut(200);
            checkbox.fadeIn(200);
            $("#reg-rules").fadeOut(200);
            $("#reg-rules").fadeIn(200);
        }
        else{
            errorMessage.css("color", "red");
            errorMessage.css("display", "none");
            if(mail.replace(/^\s+|\s+$/g, "") == "" ||
                name.replace(/^\s+|\s+$/g, "") == "" ||
                password.replace(/^\s+|\s+$/g, "") == "" ||
                repeatPassword.replace(/^\s+|\s+$/g, "") == ""){
                errorMessage.text("Заповніть усі поля з зірочкою *");
                errorMessage.css("display", "inline");
            }
            else{
                errorMessage.css("display", "none");
                if (password.length < 6){
                    errorMessage.text("Пароль не менше 6 символів");
                    errorMessage.css("display", "inline");
                }
                else{
                    errorMessage.css("display", "none");
                    if (password != repeatPassword){
                        errorMessage.text("Паролі не співпадають");
                        errorMessage.css("display", "inline");
                    }
                    else{
                        errorMessage.css("display", "none");
                        var reg = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
                        if(!reg.test(mail))
                        {
                            errorMessage.text("Введіть поштову скриньку коректно");
                            errorMessage.css("display", "inline");
                        }
                        else {
                            var data = "email=" + mail + "&ban=0";
                            var resget = $.ajax({
                                type: "GET",
                                url: "api/users.php",
                                data: data,
                                dataType: 'json',
                                status: 200,
                                statusText: "OK",
                                cache: false
                            });
                            resget.done(function (datas) {
                                var count = datas['count'];
                                if(count == "0"){
                                    data = "email=" + mail + "&name=" +name + "&password=" + password;
                                    var respost = $.ajax({
                                        type: "POST",
                                        url: "api/users.php",
                                        data: data,
                                        dataType: 'json',
                                        status: 200,
                                        statusText: "OK",
                                        cache: false
                                    });

                                    respost.done(function (datas) {
                                       var isCreated = datas['is_created'];
                                       if(isCreated == "true"){
                                           errorMessage.text("Вітаємо з реєстрацією!");
                                           errorMessage.css("display", "inline");
                                           errorMessage.css("color", "green");
                                           setTimeout(function(){
                                               var expires = new Date();
                                               expires.setTime(expires.getTime() + (7 * 24 * 60 * 60 * 1000));
                                               document.cookie =  'USER_IN=' + mail + ';expires=' + expires.toUTCString();
                                               window.location = "main.php";}, 1000);
                                       }
                                        else{
                                           errorMessage.text("Сталася помилка. Спробуйте ще раз");
                                           errorMessage.css("display", "inline");
                                       }
                                    });
                                }
                                else{
                                    errorMessage.text("Такий користувач вже існує");
                                    errorMessage.css("display", "inline");
                                }
                            });
                        }
                    }
                }
            }

        }
    });

    /*
    Login
     */

    function login(){
        var mail = $("#login-form-email").val();
        var password = $("#login-form-password").val();
        var errorMessage = $("#login-label-error");

        errorMessage.css("color", "red");
        errorMessage.css("display", "none");

        if(mail.replace(/^\s+|\s+$/g, "") == "" ||
            password.replace(/^\s+|\s+$/g, "") == ""){
            errorMessage.text("Заповніть поля для входу");
            errorMessage.css("display", "inline");
        }
        else{
            var data = "email=" + mail + "&password=" + password;
            var respost = $.ajax({
                type: "POST",
                url: "api/login.php",
                data: data,
                dataType: 'json',
                status: 200,
                statusText: "OK",
                cache: false
            });
            respost.done(function (datas) {
                var isLoggedin = datas['is_logged_in'];
                if (isLoggedin == "true"){
                    errorMessage.css("display", "none");
                    var expires = new Date();
                    expires.setTime(expires.getTime() + (7 * 24 * 60 * 60 * 1000));
                    document.cookie =  'USER_IN=' + mail + ';expires=' + expires.toUTCString();
                    window.location = "main.php";

                }
                else{
                    errorMessage.text("Ім'я або пароль є неправильні");
                    errorMessage.css("display", "inline");
                }
            });
        }
    }

    $("#login-form-submit").click(function()
    {
     login();
    });

    $("#login-form").keypress(function (e) {
        if ((e.which && e.which == 13)) {
            login();
            return false;
        } else {
            return true;
        }
    });

    function facebookVKLogin(mail, name){
        var data = "email=" + mail + "&ban=0&confirm=1";
        var resget = $.ajax({
            type: "GET",
            url: "api/users.php",
            data: data,
            async:false,
            dataType: 'json',
            status: 200,
            statusText: "OK",
            cache: false
        });
        resget.done(function (datas) {
            var count = datas['count'];
            if(count == "0"){
                data = "email=" + mail + "&name=" +name + "&password=temp";
                var respost = $.ajax({
                    type: "POST",
                    url: "api/users.php",
                    data: data,
                    async:false,
                    dataType: 'json',
                    status: 200,
                    statusText: "OK",
                    cache: false
                });

                respost.done(function (datas) {
                    var isCreated = datas['is_created'];
                    if(isCreated == "true") {
                        var updateUserData = "mail=" + mail + "&confirm=1&liked_ideas=no";
                        var resput = $.ajax({
                            type: "PUT",
                            url: "api/users.php",
                            data: updateUserData,
                            async:false,
                            dataType: 'json',
                            status: 200,
                            statusText: "OK",
                            cache: false
                        });

                        resput.done(function(datas){
                            if (datas['is_updated'] == "true"){
                                var expires = new Date();
                                expires.setTime(expires.getTime() + (7 * 24 * 60 * 60 * 1000));
                                document.cookie =  'USER_IN=' + mail + ';expires=' + expires.toUTCString();
                                window.location = "main.php";
                            }
                        });
                    }
                });
            }
            {
                var expires = new Date();
                expires.setTime(expires.getTime() + (7 * 24 * 60 * 60 * 1000));
                document.cookie =  'USER_IN=' + mail + ';expires=' + expires.toUTCString();
                window.location = "main.php";
            }
        });
    }

    $("#login-facebook").click(function(){
        FB.login(function (response) {
            if (response.authResponse) {
                console.log('Welcome!  Fetching your information.... ');
                FB.api('/me', function (response) {
                    console.log('Good to see you, ' + response.email + '.');
                    var mail = response.email;
                    var name = response.name;

                    facebookVKLogin(mail, name);

                });
            } else {
                console.log('User cancelled login or did not fully authorize.');
            }
        }, {scope: 'email,user_likes'});
    });

    $("#login-google").click(function() {
        loginGoogle();
    });

    $("#login-vkontakte").click(function(){
        VK.Auth.login(function(response) {
            if (response.session) {
                var mail = response.session.user.href;
                var name = response.session.user.first_name + " " + response.session.user.last_name;
                facebookVKLogin(mail, name);
                if (response.settings) {
                }
            } else {
            }
        });
    });
});