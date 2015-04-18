$(document).ready(function(){
    var menuItemUsers = $("#menu-item-users");
    var menuItemObjects = $("#menu-item-objects");
    var menuItemComments = $("#menu-item-comments");
    var mainContent = $("#admin-content");

    /*
    Get users
     */

    function getUsers(dataString, node){
        var data = "";

        if(dataString != ""){
            data = dataString;
        }

        var getUsers = $.ajax({
            type: "GET",
            url: "api/users.php",
            data: data,
            dataType: 'json',
            async: false,
            status: 200,
            statusText: "OK",
            cache: false
        });

        getUsers.done(function (datas) {
            var users = datas[0];
            for (var i = 0; i < users.length; i ++){
                var buttonValue = "Забанити";
                var isBanned = users[i]["is_banned"];
                if (isBanned == "1"){
                    buttonValue = "Розбанити";
                }

                node.append("<tr><td>" + users[i]['id'] +
                            "</td><td>" + users[i]['name'] +
                            "</td><td>" + users[i]['email'] +
                            "</td><td>" + users[i]['is_confirmed'] +
                            "</td><td>  <input type='button' class='button-ban' value='" + buttonValue + "' index='" + users[i]['id'] + "'/> </td></tr>");
            }
        });
    }

    menuItemUsers.click(function(){
        mainContent.empty();
        mainContent.append("<table>"
        + "<thead>"
        + "<th>"
        + "<input type='text' id='users-id' placeholder='ID користувача'/>"
        + "</th>"
        + "<th>"
        + "<input type='text' id='users-name' placeholder='Імя користувача'/>"
        + "</th>"
        + "<th>"
        + "<input type='text' id='users-email' placeholder='Email користувача'/>"
        + "</th>"
        + "<th>"
        + "<input type='text' id='users-confirmed' placeholder='Підтверджений: 0 або 1'/>"
        + "</th>"
        + "<th>Дія</th>"
        + "</thead>"
        + "<tbody></tbody>");

        getUsers("", $("#admin-content tbody"));
    });

    $(document).on("click",".button-ban", function () {
        var id = $(this).attr("index");
        var val = $(this).attr("value");
        var ban = "0";
        var isUpdated = false;

        if(val == "Забанити"){
            ban = "1";
        }
        else{
            ban = "0";
        }

        var getUsers = $.ajax({
            type: "PUT",
            url: "api/users.php",
            data: "id=" + id + "&ban=" + ban + "&liked_ideas=no",
            dataType: 'json',
            async: false,
            status: 200,
            statusText: "OK",
            cache: false
        });

        getUsers.done(function(datas){
            var is_updated = datas['is_updated'];
            if(is_updated == "true") {
                isUpdated = true;
            }
        });

        if (isUpdated == true){
            if(val == "Забанити"){
                val = "Розбанити";
            }
            else{
                val = "Забанити";
            }

            $(this).val(val);
        }
    });

   $(document).on("keyup", "#users-id", function(){
        $("#admin-content tbody").empty();
        getUsers("user_id=" + $(this).val(), $("#admin-content tbody"));
    });

    $(document).on("keyup", "#users-email", function(){
        $("#admin-content tbody").empty();
        getUsers("email=" + $(this).val(), $("#admin-content tbody"));
    });

    $(document).on("keyup", "#users-name", function(){
        $("#admin-content tbody").empty();
        getUsers("name=" + $(this).val(), $("#admin-content tbody"));
    });

    $(document).on("keyup", "#users-confirmed", function(){
        $("#admin-content tbody").empty();
        getUsers("confirm=" + $(this).val(), $("#admin-content tbody"));
    });

    /*
    Get ideas
     */

    function getIdeas(dataString, node){
        var data = "";

        if(dataString != ""){
            data = dataString;
        }

        var getIdeas = $.ajax({
            type: "GET",
            url: "api/ideas.php",
            data: data,
            dataType: 'json',
            async: false,
            status: 200,
            statusText: "OK",
            cache: false
        });

        getIdeas.done(function (datas) {
            var ideas = datas[0];
            for (var i = 0; i < ideas.length; i ++){
                var buttonValue = "Видалити";
                var isDeleted = ideas[i]["is_deleted"];
                if (isDeleted == "1"){
                    buttonValue = "Поновити";
                }

                node.append("<tr><td>" + ideas[i]['id'] +
                "</td><td>" + ideas[i]['author'] +
                "</td><td>" + ideas[i]['subject'] +
                "</td><td>" + ideas[i]['description'] +
                "</td><td>" + ideas[i]['is_deleted'] +
                "</td><td>  <input type='button' class='button-delete' value='" + buttonValue + "' index='" + ideas[i]['id'] + "'/> </td></tr>");
            }
        });
    }

    menuItemObjects.click(function(){
        mainContent.empty();
        mainContent.append("<table>"
        + "<thead>"
        + "<th>"
        + "<input type='text' id='ideas-id' placeholder='ID ідеї'/>"
        + "</th>"
        + "<th>"
        + "<input type='text' id='ideas-author' placeholder='ID автора'/>"
        + "</th>"
        + "<th>"
        + "<input type='text' id='ideas-subject' placeholder='Назва'/>"
        + "</th>"
        + "<th>"
        + "<input type='text' id='ideas-description' placeholder='Опис'/>"
        + "</th>"
        + "<th>"
        + "<input type='text' id='ideas-deleted' placeholder='Видалена: 0 або 1'/>"
        + "</th>"
        + "<th>Дія</th>"
        + "</thead>"
        + "<tbody></tbody>");

        getIdeas("all=all", $("#admin-content tbody"));
    });

    $(document).on("click",".button-delete", function () {
        var id = $(this).attr("index");
        var val = $(this).attr("value");
        var del = "0";
        var isUpdated = false;

        if(val == "Видалити"){
            del = "1";
        }
        else{
            del = "0";
        }

        var putIdeas = $.ajax({
            type: "PUT",
            url: "api/ideas.php",
            data: "id=" + id + "&delete=" + del,
            dataType: 'json',
            async: false,
            status: 200,
            statusText: "OK",
            cache: false
        });

        putIdeas.done(function(datas){
            var is_updated = datas['is_updated'];
            if(is_updated == "true") {
                isUpdated = true;
            }
        });

        if (isUpdated == true){
            if(val == "Видалити"){
                val = "Поновити";
            }
            else{
                val = "Видалити";
            }

            $(this).val(val);
        }
    });

    $(document).on("keyup", "#ideas-id", function(){
        $("#admin-content tbody").empty();
        getIdeas("all=all&id=" + $(this).val(), $("#admin-content tbody"));
    });

    $(document).on("keyup", "#ideas-author", function(){
        $("#admin-content tbody").empty();
        getIdeas("all=all&user_id=" + $(this).val(), $("#admin-content tbody"));
    });

    $(document).on("keyup", "#ideas-subject", function(){
        $("#admin-content tbody").empty();
        getIdeas("all=all&keyword=" + $(this).val(), $("#admin-content tbody"));
    });

    $(document).on("keyup", "#ideas-description", function(){
        $("#admin-content tbody").empty();
        getIdeas("all=all&keyword=" + $(this).val(), $("#admin-content tbody"));
    });

    $(document).on("keyup", "#ideas-deleted", function(){
        $("#admin-content tbody").empty();
        getIdeas("all=all&deleted=" + $(this).val(), $("#admin-content tbody"));
    });

});
