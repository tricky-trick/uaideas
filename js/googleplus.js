(function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();

function onLoadCallback()
{
    gapi.client.setApiKey('AIzaSyDMP96Hq8T2oMbVZV2PYOvPxE9FZEyyU7k'); //set your API KEY
    gapi.client.load('plus', 'v1',function(){});//Load Google + API
}

function loginGoogle()
{
    var myParams = {
        'clientid' : '633127361600-5dagka1jnhjesmihf29ucso7u0789gp7.apps.googleusercontent.com', //You need to set client id
        'cookiepolicy' : 'single_host_origin',
        'callback' : 'loginCallback', //callback function
        'approvalprompt':'force',
        'scope' : 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
    };
    gapi.auth.signIn(myParams);
}

function loginCallback(result){
    if(result['status']['signed_in'])
    {
        var request = gapi.client.plus.people.get(
            {
                'userId': 'me'
            });
        request.execute(function (resp)
        {
            var mail = '';
            if(resp['emails'])
            {
                for(i = 0; i < resp['emails'].length; i++)
                {
                    if(resp['emails'][i]['type'] == 'account')
                    {
                        mail = resp['emails'][i]['value'];
                    }
                }
            }
            var name = resp['displayName'];

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

        });
    }
}

