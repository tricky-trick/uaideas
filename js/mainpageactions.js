$(document).ready(function() {

    var expires = new Date();
    expires.setTime(expires.getTime() - (7 * 24 * 60 * 60 * 1000));
    document.cookie =  'USER_OFF=' + null + ';expires=' + expires.toUTCString();

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }


    var mail = readCookie("USER_IN")
    var is_confirmed;
    var loggedUserName;
    var user_id;
    var is_banned;
    var liked_ideas;
    var created_ideas;

    function getUser(userId, userMail) {
        var dataGetUserStatus = "user_id=" + userId + "&email=" + userMail + "&ban=0";

        var getUserStatus = $.ajax({
            type: "GET",
            url: "api/users.php",
            data: dataGetUserStatus,
            async: false,
            dataType: 'json',
            status: 200,
            statusText: "OK",
            cache: false
        });

        getUserStatus.done(function(datas) {
            var user = datas['0'];
            if(user[0]['is_confirmed'] == "1") {
                is_confirmed = true;
            }
            else {
                is_confirmed = false;
            }
            loggedUserName = user[0]['name'];
            user_id = user[0]['id'];
            liked_ideas = user[0]['liked_ideas'];
            created_ideas = user[0]['created_ideas'];
            var ban = user[0]['is_banned'];

            if(ban == 1){
                is_banned = true;
            }
            else{
                is_banned = false;
            }
        });
    }

    getUser("", mail);

    if (!is_confirmed) {
        $("#top-panel-menu td:nth-child(2)").append('<img src="img/alert.png" style="height: 7%; width: auto; display: inline-block; cursor: pointer"/>');
    }
    else if(is_banned){
        $("#top-panel-menu td:nth-child(2)").append('<img src="img/alert.png" style="height: 7%; width: auto; display: inline-block; cursor: pointer"/>');
    }

    /*
    Clear city on select of new region
     */
    var content = $("#list-ideas-content");
    var limitIndex = $("#load-another-content");
    var limitCount = 10;

    content.empty();
    limitIndex.attr("index", "0");
    searchIdeas(0, "");

    $("#region-select").change(function(){
        var cityName = $("#search-city");
        cityName.val("");
    });

    $("#top-panel-menu td img").click(function(){
        getUser("", mail);
        if(!is_confirmed){
            if(readCookie("demo") != null){
                showPopUp("Демонстраційна сесія. Увійдіть під своїм користувачем, щоб мати можливість додавати та редагувати інформацію.", $(this));
                dismissPopUp();
            }
            else {
                showPopUp("Ви не активували свій аккаунт. Будь ласка, перейдіть на свою електронну скирньку та клікніть на відповідне посилання у листі про реєстрацію", $(this));
                dismissPopUp();
            }
        }
        else if(is_banned){
            showPopUp("Даний користувач є заблокований через зволікання правилами ланого ресурсу", $(this));
            dismissPopUp();
        }
    });

    /*
    Logout
     */
    $("#logout-link").click(function () {
        window.location = "index.php";
        var expires = new Date();
        expires.setTime(expires.getTime() - (7 * 24 * 60 * 60 * 1000));
        document.cookie =  'USER_IN=' + null + ';expires=' + expires.toUTCString();
        document.cookie =  'demo=' + null + ';expires=' + expires.toUTCString();

        var expires = new Date();
        expires.setTime(expires.getTime() + (7 * 24 * 60 * 60 * 1000));
        document.cookie =  'USER_OFF=' + null + ';expires=' + expires.toUTCString();
    });

    /*
    Retrieve ideas
     */

    function searchIdeas(limit, userId, dataString){
        $("#spinner-ideas-load").css("display", "block");
        var regionId = $("#region-select").find(":selected").index();
        var cityName = $("#search-city").val();
        var categoryId = $("#category-select").find(":selected").index();
        var sortId = $("#sort-select").find(":selected").index();

        var dataGetCityId = "region_id=" + regionId + "&city_name=" + cityName;

        var getCity = $.ajax({
            type: "GET",
            url: "api/city.php",
            data: dataGetCityId,
            dataType: 'json',
            status: 200,
            statusText: "OK",
            cache: false
        });

        getCity.done(function (datas) {
            var cityId = datas['city_id'];
            if(cityId == null) {
                cityId = "";
            }
            var dataGetIdeas = "";
            if(dataString == undefined) {
                dataGetIdeas = "region_id=" + regionId + "&city_id=" + cityId + "&user_id=" + userId + "&category_id=" + categoryId + "&sort_by="
                    + sortId + "&limit=" + limitIndex.attr("index") + "," + limitCount;
            }
            else{
                dataGetIdeas = dataString;
            }
            limitIndex.attr("index", String(parseInt(limit) + parseInt(limitCount)));

            var getIdeas = $.ajax({
                type: "GET",
                url: "api/ideas.php",
                data: dataGetIdeas,
                dataType: 'json',
                status: 200,
                statusText: "OK",
                cache: false
            });
            getIdeas.done(function (datas) {
                if (datas['count'] == 0) {
                    $("#load-another-content").attr("disabled", "true");
                    $("#no-ideas-text").remove();
                    $("#ideas-content").append("<br><div id='no-ideas-text' style='width: 90%; margin-left: 5%; margin-top: 20px; text-align: center; font-family:  tahoma, arial, verdana, sans-serif, \"Lucida Sans\";'>Більше об'єктів не знайдено.<div>");
                }
                else {
                    $("#load-another-content").removeAttr("disabled");
                    var ideas = datas['0'];
                    for (var i = 0; i < ideas.length; i++) {
                        content.append(
                            "<li index=\"" + ideas[i]['id'] +"\">" +
                                "<div class=\"left-side-block\">" +
                                "<div class=\"image-ideas-item-" + ideas[i]['category'] + " image-ideas-item\">" +
                                "</div>" +
                                "<br>" +
                                "<span class=\"info-ideas-item\">" +
                                    "<img src=\"img/like1.png\" class=\"like-icon\"> " +
                                    "<span class=\"info-ideas-item-like\">" + ideas[i]['rating'] + "</span>" +
                                    "<br><br><span style=\"color: darkgrey; font-size: 12px\">від " + ideas[i]['date'] + "</span>" +
                                "</span>" +
                                "<span class=\"id-ideas-item\"></span>" +
                                "</div>" +
                                "<div class=\"right-side-block\">" +
                                "<span class=\"subject-ideas-item\"><br>" +
                                ideas[i]['subject'] +
                                "</span>" +
                                "<br> <br>" +
                                "<span class=\"description-ideas-item\">" +
                                ideas[i]['description'].replace(/\(AND\)/g,"&").substring(0,100) +
                                "...</span> </div> " +
                            "</li>");

                    }
                }

            });


        });
        $("#spinner-ideas-load").css("display", "none");
    }



    /*
    Perform search
     */

    $("#search-submit").click(function () {
        content.empty();
        limitIndex.attr("singleuser", "false");
        limitIndex.attr("index", "0");
        searchIdeas(0, "");
    });


    /*
    Search input
     */

    $("#search-input").keyup(function(){
        content.empty();
        limitIndex.attr("singleuser", "true");
        searchIdeas(0, "", "keyword=" + $(this).val());
    });



    /*
    Load content on scroll
     */
    var lastScrollTop = 200;
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() == $(document).height()) {
            if(limitIndex.attr("singleuser") == "false") {
                searchIdeas($("#load-another-content").attr("index"), "");
            }
            else{
                getUser("", mail);
                searchIdeas($("#load-another-content").attr("index"), user_id);
            }
        }
        var st = $(this).scrollTop();

        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            if (st > lastScrollTop) {
                $("#s-top-side-panel").css("top", "-20px");
                //addNewIdea.css("bottom", "-100px");

            } else {
                $("#s-top-side-panel").css("top", "50px");
                //addNewIdea.css("bottom", "20px");
            }
        }
        else {

            if (st > lastScrollTop) {
                $("#s-top-side-panel").css("top", "20px");
                addNewIdea.css("top", "45px");

            } else {
                $("#s-top-side-panel").css("top", "45px");
                addNewIdea.css("top", "70px");
            }
        }
        //lastScrollTop = st;
    });



    /*
    Get ID of clicked item. Get information about item
     */

    var layerBg = $("#layer-bg");
    var layerWrapper = $("#layer-wrapper");
    var layerWrapperNewIdea = $("#layer-wrapper-new-idea");
    var layerWrapperEditProfile = $("#layer-wrapper-edit-profile");
    var ideasSubject = $("#ideas-subject-layer");
    var ideasDescription = $("#ideas-description-layer>textarea");
    var ideasDate = $("#date-layer");
    var ideasLike = $("#like-layer");
    var ideasAuthor = $("#author-layer");
    var ideasPhotos = $("#idea-photo-area");
    var likeIcon = $("#like-icon-layer");
    var editIcon = $("#edit-icon-layer");
    var removeIcon = $("#remove-icon-layer");
    var commentsList = $("#ideas-comments-layer");
    var addNewIdea = $("#add-new-idea-button");
    var index;
    var showMapIdea = $("#idea-show-map");
    var coordGet = "";

    function getIdea(index){
        getUser("", mail);
        var dataGetIdea = "id=" + index;

        var getIdea = $.ajax({
            type: "GET",
            url: "api/ideas.php",
            async: false,
            data: dataGetIdea,
            dataType: 'json',
            status: 200,
            statusText: "OK",
            cache: false
        });

        getIdea.done(function(datas){
            $("#spinner-idea-load").css("display", "none");
            var idea = datas['0'][0];
            ideasSubject.text(idea['subject']);
            ideasDescription.val(idea['description'].replace(/\(AND\)/g,"&"));
            ideasDate.text("від " + idea['date']);
            ideasLike.text("Підтримало " + idea['rating'] + " людей");
            var photos = idea['files'];
            ideasPhotos.empty();
            if (photos.length > 0) {
                {
                    for (var i = 0; i < photos.split(' ').length; i++) {
                        ideasPhotos.append("<div style= \"background-image: url('uploaded_images/" + photos.split(' ')[i] + "')\"></div>");
                    }
                }
            }
            coordGet = idea['coord'];


            var dataGetAuthor = "user_id=" + idea['author'];

            var getAuthor = $.ajax({
                type: "GET",
                url: "api/users.php",
                data: dataGetAuthor,
                async: false,
                dataType: 'json',
                status: 200,
                statusText: "OK",
                cache: false
            });

            getAuthor.done(function(datas){
                var user = datas['0'];
                ideasAuthor.text("Автор: " + user[0]['name']);
                ideasAuthor.attr("index", user[0]['id']);
                ideasAuthor.attr("mail", user[0]['email']);

                var likedIdeas = liked_ideas.split(',');
                var createdIdeas = created_ideas.split(',');

                if(likedIdeas.indexOf(String(index)) >= 0){
                    likeIcon.css("opacity", "1");
                }
                else{
                    likeIcon.css("opacity", "0.3");
                }
                if(user[0]['id'] == user_id){
                    editIcon.css("display", "block");
                    removeIcon.css("display", "block");
                }
                else{
                    editIcon.css("display", "none");
                    removeIcon.css("display", "none");
                }

                if(coordGet.trim() == ""){
                    showMapIdea.css("display", "none");
                }
                else{
                    showMapIdea.css("display", "block");
                }

                var dataGetComments = "ideas_id=" + index + "&active=1"

                var getComments = $.ajax({
                    type: "GET",
                    url: "api/comments.php",
                    data: dataGetComments,
                    dataType: 'json',
                    async: false,
                    status: 200,
                    statusText: "OK",
                    cache: false
                });

                getComments.done(function(datas){
                    commentsList.empty();
                    var comments = datas['0'];
                    for(var i = 0; i < comments.length; i++) {
                        //var dataGetAuthor = "user_id=" + comments[i]['author'];
                        //
                        //var getAuthor = $.ajax({
                        //    type: "GET",
                        //    url: "api/users.php",
                        //    async: false,
                        //    data: dataGetAuthor,
                        //    dataType: 'json',
                        //    status: 200,
                        //    statusText: "OK",
                        //    cache: false
                        //});
                        //
                        //getAuthor.done(function(usersData){
                        //    var author = usersData['0'];
                        //
                        //});
                        commentsList.append(
                            '<li>' +
                            '<span class="ideas-comments-author" index="' + comments[i]['author'].split(';')[0] + "\">" + comments[i]['author'].split(';')[1] + "</span>" +
                            '<span class="ideas-comments-date" style="float: right; margin-right: 20px">додано ' + comments[i]['datetime'] +"</span>" +
                            '<div class="ideas-comments-text">' + comments[i]['text'] + "</div>" +
                            '</li>');
                    }
                });

            });

        });
    }

    showMapIdea.click(function(){
        if(showMapIdea.text() == "Показати на мапі") {
            $("#map-idea").css("height", "400px");
            showMapIdea.text("Сховати мапу");
            initialize(coordGet, 15, 'map-canvas-idea');
            var myLatlng = new google.maps.LatLng(parseFloat(coordGet.split(',')[0]), parseFloat(coordGet.split(',')[1]));
            marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                animation: google.maps.Animation.DROP
            });
            marker.setMap(map);
        }
        else{
            $("#map-idea").css("height", "0px");
            showMapIdea.text("Показати на мапі");
        }
    });

    function getLastComment(ideaIndex){
        var dataGetComments = "ideas_id=" + ideaIndex + "&active=1" + "&last=1";

        var getComments = $.ajax({
            type: "GET",
            url: "api/comments.php",
            data: dataGetComments,
            dataType: 'json',
            async: false,
            status: 200,
            statusText: "OK",
            cache: false
        });

        getComments.done(function(datas){
            var comments = datas['0'];
            for(var i = 0; i < comments.length; i++) {
                //var dataGetAuthor = "user_id=" + comments[i]['author'];
                //
                //var getAuthor = $.ajax({
                //    type: "GET",
                //    url: "api/users.php",
                //    async: false,
                //    data: dataGetAuthor,
                //    dataType: 'json',
                //    status: 200,
                //    statusText: "OK",
                //    cache: false
                //});
                //
                //getAuthor.done(function(usersData){
                //    var author = usersData['0'];
                //
                //});
                commentsList.append(
                    '<li>' +
                    '<span class="ideas-comments-author" index="' + comments[i]['author'].split(';')[0] + "\">" + comments[i]['author'].split(';')[1] + "</span>" +
                    '<span class="ideas-comments-date" style="float: right; margin-right: 20px">додано ' + comments[i]['datetime'] +"</span>" +
                    '<div class="ideas-comments-text">' + comments[i]['text'] + "</div>" +
                    '</li>');
            }
        });
    }

    $(document).on("click","#list-ideas-content li", function () {
        index = $(this).attr('index');
        $("body").css("overflow-y", "hidden");
        $("#spinner-idea-load").css("display", "block");
        layerBg.css("display", "block");
        layerWrapper.css("display", "block");
        profilePopup.css("display", "none");

        getIdea(index);

        var description = ideasDescription.val(),
            matches = description.match(/\n|\r/g),
            breaks = matches ? matches.length : 2;

        var val = ideasDescription.val().length / (ideasDescription.width()/10);


        ideasDescription.attr('rows',val);

        //var styleBigPhoto = $("#idea-big-photo-area").attr("style");
        //if(styleBigPhoto == undefined)
        //    $("#idea-big-photo-area").css("height", "0px");

    });

    $(document).on("click","#idea-photo-area div", function () {
        var srcImg = $(this).css("background-image");
        srcImg = srcImg.replace("url(", "").replace(")", "");
        $("#idea-big-photo-area").empty();
        $("#idea-big-photo-area").append("<img src=" + decodeURIComponent(srcImg) + " style='width: 100%; height auto' />");
    });

    /*
    Close layer window
     */

    function closeLayerWindow(){
        layerBg.css("display", "none");
        layerWrapper.css("display", "none");
        layerWrapperNewIdea.css("display", "none");
        layerWrapperEditProfile.css("display", "none");
        $("#map").css("height", "0px");
        $("#map-idea").css("height", "0px");
        $("body").css("overflow-y", "auto");
        $("#idea-big-photo-area").empty();
        showMapIdea.text("Показати на мапі");
    }

    $(".close-layer").click(function(){
        closeLayerWindow();
    });

    layerBg.click(function(){
        closeLayerWindow();
    });

    /*
    Add new comment
     */

    var commentsArea = $("#editable-comments-area>textarea");
    var addComment = $("#add-comments");
    var cancelComment = $("#cancel-comments");

    commentsArea.click(function(){
        if(commentsArea.val() == "Коментувати...") {
            commentsArea.val("");
            commentsArea.css("min-height", "50px");
            commentsArea.css("color", "black");
        }
    });


    cancelComment.click(function(){
        commentsArea.css("min-height", "30px");
        commentsArea.css("color", "grey");
        commentsArea.val("Коментувати...");
    });

    commentsList.click(function(){
        commentsArea.css("min-height", "30px");
        commentsArea.css("color", "grey");
        commentsArea.val("Коментувати...");
    });


    addComment.click(function(){
        if(commentsArea.val() != "Коментувати..." && commentsArea.val().trim() != "") {
            getUser("", mail);
            if (is_confirmed && !is_banned) {
                var postCommentData = "user_id=" + user_id + ";" + loggedUserName + "&text=" + commentsArea.val().replace(/(?:\r\n|\r|\n)/g, '<br />') + "&ideas_id=" + index;

                var postNewComment = $.ajax({
                    type: "POST",
                    url: "api/comments.php",
                    data: postCommentData,
                    dataType: 'json',
                    status: 200,
                    statusText: "OK",
                    cache: false
                });

                postNewComment.done(function (datas) {
                    var isAddedComment = datas['is_added'];
                    if (isAddedComment == "true") {
                        getLastComment(index);
                        commentsArea.css("min-height", "30px");
                        commentsArea.css("color", "grey");
                        commentsArea.val("Коментувати...");
                    }
                });
            }
            else {
                if(!is_confirmed){
                    if(readCookie("demo") != null){
                        showPopUp("Демонстраційна сесія. Увійдіть під своїм користувачем, щоб мати можливість додавати та редагувати інформацію.", $(this));
                        dismissPopUp();
                    }
                    else {
                        showPopUp("Будь ласка, пітвердіть Ваш аккаунт", $(this));
                        dismissPopUp();
                    }
                }
                else if(is_banned){
                    showPopUp("Користувач є заблований через порушення правил даного ресурсу.", $(this));
                    dismissPopUp();
                }
            }
        }
    });

    function showPopUp(text, el){
        $("#popup").css("display", "block");
        $("#popup").fadeIn(300);
        $("#popup").text(text);
        var pos = el.offset();
        $('#popup').offset({ top: pos.top + el.height() - 25, left: pos.left - 25 });
    }

    function dismissPopUp(){
        setTimeout(function(){
            $("#popup").fadeOut(300);
        }, 3000);
    }


    /*
    Add like
     */

    likeIcon.click(function(){
        var likeCount = parseInt(ideasLike.text().split(' ')[1]);
        getUser(user_id, "");
        var likedIdeas = liked_ideas.split(',');
        var likeIndex = likedIdeas.indexOf(String(index));
        if(is_confirmed && !is_banned) {
            // Reduce
            if (likeIndex >= 0) {
                likeCount--;
                likeIcon.css("opacity", "0.3");
                ideasLike.text("Підтримало " + likeCount + " людей");
                $("li[index='" + index + "'] .info-ideas-item-like").html(likeCount);
                likedIdeas.splice(likeIndex, 1);

                var putLikedIdeas = $.ajax({
                    type: "PUT",
                    url: "api/users.php",
                    data: "id=" + user_id + "&liked_ideas=" + String(likedIdeas),
                    dataType: 'json',
                    async: false,
                    status: 200,
                    statusText: "OK",
                    cache: false
                });

                putLikedIdeas.done(function (datas) {
                    var isUpdated = datas['is_updated'];
                    if (isUpdated == "true") {

                        var getRatingIdea = $.ajax({
                            type: "GET",
                            url: "api/ideas.php",
                            data: "id=" + index,
                            dataType: 'json',
                            async: false,
                            status: 200,
                            statusText: "OK",
                            cache: false
                        });

                        getRatingIdea.done(function (datas) {
                            var rating = datas['0'][0]['rating'];
                            var newRating = String(parseInt(rating) - 1);

                            var putLikedIdeas = $.ajax({
                                type: "PUT",
                                url: "api/ideas.php",
                                data: "id=" + index + "&rating_val=" + newRating,
                                dataType: 'json',
                                async: false,
                                status: 200,
                                statusText: "OK",
                                cache: false
                            });

                            putLikedIdeas.done(function (datas) {
                                var isUpdated = datas['is_updated'];
                                if (isUpdated == "true") {
                                    //likeIcon.css("opacity", "0.3");
                                    //ideasLike.text("Підтримало  " + newRating + " людей");
                                    //$("li[index='" + index + "'] .info-ideas-item-like").html(newRating);
                                }
                            });

                        });
                    }

                });
            }

            //Increase
            else {
                likeCount++;
                likeIcon.css("opacity", "1");
                ideasLike.text("Підтримало " + likeCount + " людей");
                $("li[index='" + index + "'] .info-ideas-item-like").text(likeCount);
                likedIdeas.push(index);
                var newArrayLikedIdeas = likedIdeas.join();
                var putLikedIdeas = $.ajax({
                    type: "PUT",
                    url: "api/users.php",
                    data: "id=" + user_id + "&liked_ideas=" + newArrayLikedIdeas,
                    dataType: 'json',
                    status: 200,
                    statusText: "OK",
                    cache: false
                });

                putLikedIdeas.done(function (datas) {
                    var isUpdated = datas['is_updated'];
                    if (isUpdated == "true") {

                        var getRatingIdea = $.ajax({
                            type: "GET",
                            url: "api/ideas.php",
                            data: "id=" + index,
                            dataType: 'json',
                            async: false,
                            status: 200,
                            statusText: "OK",
                            cache: false
                        });

                        getRatingIdea.done(function (datas) {
                            var rating = datas['0'][0]['rating'];
                            var newRating = String(parseInt(rating) + 1);

                            var putLikedIdeas = $.ajax({
                                type: "PUT",
                                url: "api/ideas.php",
                                data: "id=" + index + "&rating_val=" + newRating,
                                dataType: 'json',
                                async: false,
                                status: 200,
                                statusText: "OK",
                                cache: false
                            });

                            putLikedIdeas.done(function (datas) {
                                var isUpdated = datas['is_updated'];
                                if (isUpdated == "true") {
                                    //likeIcon.css("opacity", "1");
                                    //ideasLike.text("Підтримало  " + newRating + " людей");
                                    //$("li[index='" + index + "'] .info-ideas-item-like").text(newRating);
                                }
                            });

                        });
                    }

                });
            }
        }
    });

    /*
    Add new idea
     */

    addNewIdea.click(function(){
        profilePopup.css("display", "none");
        getUser("", mail);
        if (is_confirmed && !is_banned) {
            layerBg.css("display", "block");
            layerWrapperNewIdea.css("display", "block");
            $("body").css("overflow-y", "hidden");
        }
        else{
            if(!is_confirmed){
                if(readCookie("demo") != null){
                    showPopUp("Демонстраційна сесія. Увійдіть під своїм користувачем, щоб мати можливість додавати та редагувати інформацію.", $(this));
                    dismissPopUp();
                }
                else {
                    showPopUp("Будь ласка, пітвердіть Ваш аккаунт", $(this));
                    dismissPopUp();
                }
            }
            else if(is_banned){
                showPopUp("Користувач є заблований через порушення правил даного ресурсу.", $(this));
                dismissPopUp();
            }
        }

    });



    var newIdeaSubject = $("#editable-subject-area>input");
    var newIdeaBody = $("#editable-body-area>textarea");
    var newIdeaRegion = $("#new-idea-region-select");
    var newIdeaCity = $("#new-idea-search-city");
    var newIdeaCategory = $("#new-idea-category-select");
    var newIdeaAddButton = $("#add-new-idea");
    var newIdeaCancel = $("#cancel-new-idea");
    var newIdeaCoordinate = $("#new-idea-show-map");

    var coord = "";

    newIdeaSubject.click(function(){
        if(newIdeaSubject.val() == "Назва...") {
            newIdeaSubject.val("");
            newIdeaSubject.css("color", "black");
        }
    });

    newIdeaBody.click(function(){
        if(newIdeaBody.val() == "Опис...") {
            newIdeaBody.val("");
            newIdeaBody.css("min-height", "100px");
            newIdeaBody.css("color", "black");
        }
    });

    newIdeaCoordinate.click(function(){
        $("#map").css("height", "400px");
        initialize("49.508577, 31.336679", 6, 'map-canvas');
        function clearOverlays() {
            for (var i = 0; i < markersArray.length; i++ ) {
                markersArray[i].setMap(null);
            }
        }

        google.maps.event.addListener(map, 'click', function(event) {
            clearOverlays();
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map,
                draggable:true,
                animation: google.maps.Animation.DROP});
            markersArray.push(marker);
            marker.setMap(map);
            coord = String(event.latLng).replace("(","").replace(")","");
        });
    });

    newIdeaCancel.click(function(){;
        newIdeaSubject.val("Назва...");
        newIdeaBody.val("Опис...");
        newIdeaBody.css("min-height", "90px");
        newIdeaBody.css("color", "grey");
        newIdeaSubject.css("color", "grey");
        $("#ideas-subject-counter").text("");
        $("#ideas-body-counter").text("");
        $("#map").css("height", "0px");
        $("#spinner-icon").css("display", "none");
    });

    function guid() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
    }

    newIdeaAddButton.click(function(){
        var regionId = newIdeaRegion.find(":selected").index();
        var categoryId = newIdeaCategory.find(":selected").index();
        var cityName = newIdeaCity.val();
        var dataGetCityId = "region_id=" + regionId + "&city_name=" + cityName;

        if (is_confirmed && !is_banned) {
            if (newIdeaSubject.val().trim() == "" || newIdeaSubject.val().trim() == "Назва..." || newIdeaBody.val().trim() == "" || newIdeaBody.val().trim() == "Опис...") {
                showPopUp("Введіть назву та опис ідеї.", $(this));
                dismissPopUp();
            }
            else{
                if(newIdeaSubject.val().trim().length < 5 || newIdeaBody.val().trim().length < 50){
                    showPopUp("Назва ідеї або опис є надто короткими", $(this));
                    dismissPopUp();
                }
                else {
                    var getCity = $.ajax({
                        type: "GET",
                        url: "api/city.php",
                        data: dataGetCityId,
                        dataType: 'json',
                        status: 200,
                        statusText: "OK",
                        cache: false
                    });

                    getCity.done(function (datas) {
                        var cityId = datas['city_id'];
                        if (cityId == null) {
                            cityId = 0;
                        }
                        $("#spinner-icon").css("display", "inline-block");
                        var newIdeaUploadFile = document.getElementById('new-idea-image-upload');

                        if(newIdeaUploadFile.files.length > 10){
                            showPopUp("Ви можете додати не більше 10 зображень", newIdeaAddButton);
                            dismissPopUp();
                        }
                        else {
                            var matchError = ""
                            var match= ["image/jpeg","image/png","image/jpg"];
                            for (var i = 0; i < newIdeaUploadFile.files.length; i++) {
                                var imagefile = newIdeaUploadFile.files[i].type;
                                if ((imagefile != match[0]) && (imagefile != match[1]) && (imagefile != match[2])) {
                                    matchError += "e";
                                }
                            }

                            if (matchError.length > 0){
                                showPopUp("Формат файлів може бути: png, jpeg, jpg", newIdeaAddButton);
                                dismissPopUp();
                            }
                            else {
                                var newIdeaUploadFile = document.getElementById('new-idea-image-upload');
                                var form = document.getElementById('uploadimage');
                                var formData = new FormData(form);
                                var files = "";
                                var uiid = guid();

                                for (var j = 0; j < newIdeaUploadFile.files.length; j++) {
                                    var name = uiid + "_" + newIdeaUploadFile.files.item(j).name.replace(/ /g, "_");
                                    //newIdeaUploadFile.files.item(j).name = name.replace(/ /g, "_");
                                    files += name + " ";
                                }

                                formData.append("uiid", uiid);

                                var uploadImages =  $.ajax({
                                    url: "upload_images.php",
                                    type: "POST",
                                    data: formData,
                                    dataType: 'json',
                                    contentType: false,
                                    cache: false,
                                    processData: false
                                });

                                uploadImages.done(function(datas){
                                    var uploadedF = datas['uploaded_files'];
                                    if(files.length <= 0){
                                        getUser("", mail);
                                        var dataPostIdea = "region_id=" + regionId + "&city_id=" + cityId + "&coordinates=" + coord + "&user_id=" + user_id + "&category=" + categoryId +
                                            "&subject_text=" + newIdeaSubject.val() + "&description_text=" + newIdeaBody.val().replace(new RegExp("&","g"),"(AND)") + "&files=" + files.trim();

                                        var postIdea = $.ajax({
                                            type: "POST",
                                            url: "api/ideas.php",
                                            data: dataPostIdea,
                                            dataType: 'json',
                                            status: 200,
                                            statusText: "OK",
                                            cache: false
                                        });

                                        postIdea.done(function (datas) {
                                            var isCreated = datas['is_created'];
                                            if (isCreated == "true") {
                                                newIdeaSubject.val("Назва...");
                                                newIdeaBody.val("Опис...");
                                                $("#ideas-subject-counter").text("");
                                                $("#ideas-body-counter").text("");
                                                $("new-idea-image-upload").val("Завантажити фото");
                                                newIdeaBody.css("min-height", "60px");
                                                newIdeaBody.css("color", "grey");
                                                newIdeaSubject.css("color", "grey");
                                                $("#spinner-icon").css("display", "none");
                                                $("#done-icon").css("opacity", "1");

                                                setTimeout(function () {
                                                    $("#done-icon").css("opacity", "0");
                                                    closeLayerWindow();

                                                }, 1000);

                                            }

                                        });
                                    }
                                    else{
                                        if(uploadedF.replace(/й|і/g, "и").replace(/̆/g, "") == files.replace(/й|і/g, "и").replace(/̆/g, "")) {

                                            getUser("", mail);
                                            var dataPostIdea = "region_id=" + regionId + "&city_id=" + cityId + "&coordinates=" + coord + "&user_id=" + user_id + "&category=" + categoryId +
                                                "&subject_text=" + newIdeaSubject.val() + "&description_text=" + newIdeaBody.val().replace(new RegExp("&","g"),"(AND)") + "&files=" + files.trim();

                                            var postIdea = $.ajax({
                                                type: "POST",
                                                url: "api/ideas.php",
                                                data: dataPostIdea,
                                                dataType: 'json',
                                                status: 200,
                                                statusText: "OK",
                                                cache: false
                                            });

                                            postIdea.done(function (datas) {
                                                var isCreated = datas['is_created'];
                                                if (isCreated == "true") {
                                                    newIdeaSubject.val("Назва...");
                                                    newIdeaBody.val("Опис...");
                                                    $("new-idea-image-upload").val("Завантажити фото");
                                                    newIdeaBody.css("min-height", "60px");
                                                    newIdeaBody.css("color", "grey");
                                                    newIdeaSubject.css("color", "grey");
                                                    $("#spinner-icon").css("display", "none");
                                                    $("#done-icon").css("opacity", "1");
                                                    setTimeout(function () {
                                                        $("#done-icon").css("opacity", "0");
                                                        closeLayerWindow();

                                                    }, 1000);
                                                    $("#ideas-subject-counter").text("");
                                                    $("#ideas-body-counter").text("");
                                                    $("#uploaded-imgs").text("Завантажити фото");

                                                }

                                            });
                                        }
                                    }

                                });
                            }
                        }

                    });
                }
            }

        }
        else {
            if (!is_confirmed) {
                showPopUp("Будь ласка, пітвердіть Ваш аккаунт", $(this));
                dismissPopUp();
            }
            else if (is_banned) {
                showPopUp("Користувач є заблований через порушення правил даного ресурсу.", $(this));
                dismissPopUp();
            }
        }
    });

    var profilePopup = $("#my-profile");

    $("#profile-link").click(function(){
        if(readCookie("demo") == null) {
            if (profilePopup.css("display") == "none") {
                profilePopup.css("display", "block");
                profilePopup.fadeIn(300);
                var pos = $(this).offset();
                profilePopup.offset({top: pos.top + $(this).height() + 10, left: pos.left});
            }

            else {
                profilePopup.css("display", "none");
            }
        }
        else{
            showPopUp("Демонстраційна сесія. Увійдіть під своїм користувачем, щоб мати можливість додавати та редагувати інформацію.", $(this));
            dismissPopUp();
        }
    });

    $("#my-ideas").click(function(){
        limitIndex.attr("singleuser", "true");
        content.empty();
        limitIndex.attr("index", "0");
        getUser("", mail);
        searchIdeas(0, user_id, "user_id=" + user_id + "&sort_by=0&limit=" + limitIndex.attr("index") + "," + limitCount);
        setTimeout(function(){
            profilePopup.css("display", "none");
        },200);
    });

    $("#edit-profile").click(function(){
        layerBg.css("display", "block");
        layerWrapperEditProfile.css("display", "block");
        $("body").css("overflow-y", "hidden");
        setTimeout(function(){
            profilePopup.css("display", "none");
        },200);
    });

    editIcon.click(function(){
        ideasDescription.removeAttr("readonly");
        ideasDescription.focus();
        ideasDescription.css("background-color","#FDFDFD");
        ideasDescription.css("border","1px solid lightgray");
    });

    function replaceStyleAttr (str) {
        return str.replace(/(<[\w\W]*?)(style)([\w\W]*?>)/g, function (a, b, c, d) {
            return b + 'style_replace' + d;
        });
    }

    function removeTagsExcludeA (str) {
        return str.replace(/<\/?((?!a)(\w+))\s*[\w\W]*?>/g, '');
    }

    function removeAllTags (str) {
        return str.replace(/<\/?(\w+)\s*[\w\W]*?>/g, '');
    }


    ideasDescription.focusout(function(){
        getUser("", mail);

        var dataGetIdeasByAuthrId = "user_id=" + user_id;

        var getIdeas = $.ajax({
            type: "GET",
            url: "api/ideas.php",
            data: dataGetIdeasByAuthrId,
            dataType: 'json',
            async: false,
            status: 200,
            statusText: "OK",
            cache: false
        });

        getIdeas.done(function (datas) {
            var ideas = datas['0'];
            var isCreated = false;
            for (var i = 0; i < ideas.length; i++) {
                if (ideas[i]['author'] == user_id) {
                    isCreated = true;
                }
            }

            if (isCreated == true) {
                if(ideasDescription.attr("readonly") != "readonly"){
                //TODO
                var description = ideasDescription.val().replace(new RegExp("&","g"),"(AND)");

                //.replace(new RegExp("&lt;", "g"),"<")
                //.replace(new RegExp("&gt;", "g"),">")
                //.replace(/(<[^>]*>)/g, "<br>")
                //.replace(/&lt*;.*&gt*;/g, "");
                //.replace(new RegExp("<br><br>", "g"),"<br>");

                if (description.trim() == "") {
                    showPopUp("Додайте опис", ideasDescription);
                    dismissPopUp();
                }
                else {
                    if (description.length < 50) {
                        showPopUp("Опис повинен містити хоча б 50 символів", ideasDescription);
                        dismissPopUp();
                    }
                    else {
                        var putUpdatedIdeas = $.ajax({
                            type: "PUT",
                            url: "api/ideas.php",
                            data: "id=" + index + "&description_text=" + description,
                            dataType: 'json',
                            async: false,
                            status: 200,
                            statusText: "OK",
                            cache: false
                        });

                        putUpdatedIdeas.done(function (datas) {
                            var isUpdated = datas['is_updated'];
                            if (isUpdated == "true") {
                                ideasDescription.css("background-color", "#C6FCD9");
                                setTimeout(function () {
                                    ideasDescription.css("background-color", "#FFFFFF");
                                }, 1000);
                                ideasDescription.attr("readonly", "readonly");
                                ideasDescription.css("border","none");

                                var text = description,
                                    matches = text.match(/\n/g),
                                    breaks = matches ? matches.length : 2;

                                ideasDescription.attr('rows',breaks + 2);
                            }
                        });
                    }
                }
            }
        }
        });
    });

    removeIcon.click(function(){
        getUser("", mail);
        var dataGetIdeasByAuthrId = "user_id=" + user_id;

        var getIdeas = $.ajax({
            type: "GET",
            url: "api/ideas.php",
            data: dataGetIdeasByAuthrId,
            dataType: 'json',
            async: false,
            status: 200,
            statusText: "OK",
            cache: false
        });

        getIdeas.done(function (datas) {
            var ideas = datas['0'];
            var isCreated = false;
            for (var i = 0; i < ideas.length; i++) {
                if (ideas[i]['author'] == user_id) {
                    isCreated = true;
                }
            }

            if (isCreated == true) {
                $("#dialog>p").text("Ви справді бажаєте видалити цю ідею?");
                $( "#dialog" ).dialog({
                    dialogClass: "no-close",
                    buttons: [
                        {
                            text: "OK",
                            click: function() {
                                var putUpdatedIdeas = $.ajax({
                                    type: "PUT",
                                    url: "api/ideas.php",
                                    data: "id=" + index + "&delete=1",
                                    dataType: 'json',
                                    async: false,
                                    status: 200,
                                    statusText: "OK",
                                    cache: false
                                });

                                putUpdatedIdeas.done(function (datas) {
                                    var isUpdated = datas['is_updated'];
                                    if (isUpdated == "true") {
                                        closeLayerWindow();
                                        $("li[index='" + index + "']").remove();
                                    }
                                });
                                $( this ).dialog( "close" );
                                $("#dialog>p").text("");
                            }
                        }
                    ]
                });
            }
        });
    });

    var editProfileUserName = $("#user-name");
    var editProfileUserPassword = $("#user-password");
    var editProfileUserNewPassword = $("#user-new-password");
    var editProfileUserRepeatPassword = $("#user-new-password-repeat");
    var editProfileError = $("#edit-profile-error");

    $("#submit-change-profile").click(function(){
        editProfileError.css("color", "red");
        if (editProfileUserName.val().trim() == ""
            && editProfileUserPassword.val().trim() == ""
            && editProfileUserNewPassword.val().trim() == ""
            && editProfileUserRepeatPassword.val().trim() == ""){
            editProfileError.text("Усі поля є пусті");
            editProfileError.css("display", "block");
        }
        else{
            editProfileError.css("display", "none");
            if (editProfileUserPassword.val().trim() == ""){
                editProfileError.text("Введіть поточний пароль для будь-яких змін");
                editProfileError.css("display", "block");
            }
            else{
                editProfileError.css("display", "none");
                if (editProfileUserNewPassword.val().trim() != editProfileUserRepeatPassword.val().trim()) {
                    editProfileError.text("Паролі не співпадають");
                    editProfileError.css("display", "block");
                }
                else {
                    editProfileError.css("display", "none");
                    if(editProfileUserNewPassword.val().trim() != ""
                        && editProfileUserRepeatPassword.val().trim() != ""
                        && editProfileUserNewPassword.val().length < 6){
                        editProfileError.text("Довжина паролю не повинна бути меншою 6-ти симвлоів");
                        editProfileError.css("display", "block");
                    }
                    else {
                        var data = "email=" + mail + "&password=" + editProfileUserPassword.val().trim();
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
                                var updateData = "mail=" + mail + "&name=" + editProfileUserName.val().trim() + "&liked_ideas=no" + "&password=" + editProfileUserNewPassword.val().trim();

                                var updateData = $.ajax({
                                    type: "PUT",
                                    url: "api/users.php",
                                    data: updateData,
                                    async:false,
                                    dataType: 'json',
                                    status: 200,
                                    statusText: "OK",
                                    cache: false
                                });

                                updateData.done(function(datas){
                                    var is_updated = datas['is_updated'];
                                    if(is_updated == "true") {
                                        editProfileError.css("color", "green");
                                        editProfileError.text("Дані успішно змінені");
                                        editProfileError.css("display", "block");
                                        editProfileUserName.val("");
                                        editProfileUserPassword.val("");
                                        editProfileUserNewPassword.val("");
                                        editProfileUserRepeatPassword.val("");
                                        setTimeout(function(){
                                            closeLayerWindow();
                                        }, 1000);
                                    }
                                });
                            }
                            else{
                                editProfileError.text("Поточний пароль є невірним");
                                editProfileError.css("display", "block");
                            }
                        });
                    }
                }
            }
        }
    });

    $("#new-idea-image-upload").on('change',function() {
        var arr = [];
        var err;
        if($(this).get(0).files.length > 10) {
            showPopUp("Ви можете додати не більше 10 зображень", newIdeaAddButton);
            dismissPopUp();
        }
        else {
            for (var i = 0; i < $(this).get(0).files.length; ++i) {
                var name = $(this).get(0).files.item(i).name;
                var type = $(this).get(0).files.item(i).type;
                var match = ["image/jpeg", "image/png", "image/jpg"];

                if ((type == match[0]) || (type == match[1]) || (type == match[2])) {
                    arr.push(name);
                }
                else {
                    err = "error";
                }

            }

            if(err == "error"){
                showPopUp("Формат файлів може бути: png, jpeg, jpg", $("#uploaded-imgs"));
                dismissPopUp();
            }
            else {
                $("#uploaded-imgs").text(arr);
            }
        }
    });
});

