<?php
if (!isset($_COOKIE['USER_IN'])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head lang="ua">
    <meta charset="UTF-8">
    <title>Пізнай Україну</title>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content="Україна,замки,фортеці,подорожі,відпочинок,туризм,подорожувати,провести час,ресторани,кафе,актиний відпочинок">
    <link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/mainbackground.css" />
    <script>
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            link=document.createElement('link');
            link.href='css/mmain.css';
            link.rel='stylesheet';
        }
        else{
            link=document.createElement('link');
            link.href='css/main.css';
            link.rel='stylesheet';
        }
        document.getElementsByTagName('head')[0].appendChild(link);
    </script>
    <link rel="stylesheet" type="text/css" href="css/categories.css" />
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
    <script type="text/javascript" src="js/mainpageactions.js"></script>
    <script type="text/javascript" src="js/mainpageloaddata.js"></script>
    <script type="text/javascript" src="js/facebook.js"></script>
    <script type="application/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDMP96Hq8T2oMbVZV2PYOvPxE9FZEyyU7k"></script>
    <script type="text/javascript">
        var map;
        var marker;
        var markersArray = [];
        function initialize(coord, zoom, idOfMap) {
            var mapOptions = {
                center: { lat: parseFloat(coord.split(',')[0]), lng: parseFloat(coord.split(',')[1])},
                zoom: zoom,
                scrollwheel: false
            };
            map = new google.maps.Map(document.getElementById(idOfMap),
                mapOptions);
        }
    </script>
    <script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>
    <script type="text/javascript">
        VK.init({apiId: 4827044, onlyWidgets: true});
    </script>
</head>
<body>
<div id="fb-root"></div>
    <div id="main-content">

        <div id="top-side-panel">
            <img id = "main-logo" src="img/logo_see.png">
            <table id="top-panel-menu">
                <tbody>
                <tr>
                    <td>
                        <input type="text" id="search-input" placeholder="Пошук" autocomplete="off" style="padding: 4px 40px 5px 21px;">
                    </td>
                    <td>
                        <span id="profile-link" class="top-panel-menu-items">Мій профіль</span>
                    </td>
                    <td>
                        <a id="help-link" class="top-panel-menu-items" href="terms.php" target="_blank" style="text-decoration: none">Допомога</a>
                    </td>
                    <td>
                        <span id="logout-link" class="top-panel-menu-items">Вийти</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div id="s-top-side-panel">
            <table id="s-top-panel-menu">
                <tbody>
                <tr>
                    <td width="22%">
                        <select id="region-select">
                            <option>Оберіть область</option>
                        </select>
                    </td>
                    <td width="22%">
                        <input type="text" id="search-city" value="" autocomplete="on" onkeypress="validateName(event, 'search-city', 'region-select')" placeholder="Введіть назву міста">
                    </td>
                    <td width="22%">
                        <select id="category-select">
                            <option>Оберіть категорію</option>
                        </select>
                    </td>
                    <td width="22%">
                        <select id="sort-select">
                            <option index="0">Сортувати за датою</option>
                            <option index="1">Сортувати за рейтингом</option>
                        </select>
                    </td>
                    <td width="12%">
                        <input type="submit" id="search-submit" value="Пошук">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="ideas-content">
            <img id="spinner-ideas-load" src="img/spinner_big.gif" style="width: 20%; height: auto; margin-left: 40%; display: none" />
            <ul id="list-ideas-content">
            </ul>
            <input type="button" id="load-another-content" index="0" singleuser="false" value="Завантажити ще" style="display: none;">
        </div>
    </div>
    <div id="layer-bg"></div>
    <div id="layer-wrapper">
        <span class="close-layer">Закрити</span>
        <br>
        <br>
        <div style="overflow-y: auto; overflow-x: hidden; height: 100%; width: 100%">
            <br>
            <br>
            <div id="ideas-subject-layer"></div>
            <br>
            <br>
            <div id="ideas-description-layer" contenteditable="false">
                <textarea maxlength="5000" readonly></textarea>
            </div>
            <img id="spinner-idea-load" src="img/spinner_big.gif" style="width: 20%; height: auto; margin-left: 40%; display: none" />
            <div id="idea-photo-area">
            </div>
            <div id="idea-big-photo-area">
            </div>

            <div id="idea-show-map" coord="">Показати на мапі</div>
            <div id="map-idea">
                <div id="map-canvas-idea" style="width: 100%; height: 100%;">
                </div>
            </div>
            <br>
            <br>
            <span id="date-layer"></span>
            <span id="like-layer"></span>
            <br>
            <br>
            <span id="author-layer"></span>
            <img src="img/like1.png" id="like-icon-layer"/>
            <br>
            <br>
            <img src="img/icon-edit.png" id="edit-icon-layer"/>
            <br>
            <br>
            <img src="img/trash-ico.png" id="remove-icon-layer"/>
            <br>
            <br>
            <br>
            <div id="comments-layer">Коментарі</div>
            <ul id="ideas-comments-layer">
            </ul>
            <br>
            <br>
            <div id="editable-comments-area">
                <textarea maxlength="500">Коментувати...</textarea>
            </div>
            <br>
            <span id="add-comments">Додати коментар</span>
            <span id="cancel-comments">Скасувати</span>
        </div>
    </div>
    <div id="layer-wrapper-new-idea">
        <span class="close-layer">Закрити</span>
        <br>
        <br>
        <div style="overflow-y: auto; overflow-x: hidden; height: 100%; width: 100%">
            <br>
            <br>
            <div id="new-idea-subject-layer">Нова ідея</div>
            <br>
            <br>
            <div id="editable-subject-area"><input type="text" maxlength="100" value="Назва..." onkeyup="validateAmount('#editable-subject-area>input', 100, 20)"></div>
            <span id="ideas-subject-counter"></span>
            <br>
            <br>
            <div id="editable-body-area">
                <textarea maxlength="5000" onkeyup="validateAmount('#editable-body-area>textarea', 5000, 50)">Опис...</textarea>
            </div>
            <span id="ideas-body-counter"></span>
            <br>
            <table id="dropdown-new-idea">
                <tbody>
                <tr>
                    <td width="33%">
                        <select id="new-idea-region-select">
                            <option>Оберіть область</option>
                        </select>
                    </td>
                    <td width="33%">
                        <input type="text" id="new-idea-search-city" value="" autocomplete="on" onkeyup="validateName(event, 'new-idea-search-city', 'new-idea-region-select')" placeholder="Введіть назву міста">
                    </td>

                    <td width="33%">
                        <select id="new-idea-category-select">
                            <option>Оберіть категорію</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
            <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
<!--                <input type="file" name="file[]" id="new-idea-image-upload" multiple />-->
                <div id="upimage-container">
                    <span id="uploaded-imgs">Завантажити фото</span><input type="file" name="file[]" id="new-idea-image-upload" multiple  />
                    <br>
                </div>
            </form>
            <br>
            <div id="new-idea-show-map">Додати координати</div>
            <br>
            <div id="map">
                <div id="map-canvas" style="width: 100%; height: 100%;">
                </div>
            </div>
            <br>
            <span id="add-new-idea">Додати ідею</span>
            <img id="spinner-icon" src="img/spinner.gif" style="display: none;"/>
            <img id="done-icon" src="img/done-icon.png"/>
            <span id="cancel-new-idea">Скасувати</span>
        </div>
    </div>
    <div id="layer-wrapper-edit-profile">
        <span class="close-layer">Закрити</span>
        <br>
        <br>
        <div style="overflow-y: auto; overflow-x: hidden; height: 100%; width: 100%">
            <br>
            <br>
            <br>
            <span class="edit-profile-label">Ім'я користувача</span>
            <br>
            <input class="edit-profile-input" id="user-name" value="" maxlength="50"/>
            <br>
            <br>
            <br>
            <span class="edit-profile-label">Поточний пароль *</span>
            <br>
            <input class="edit-profile-input" id="user-password" type="password" value="" maxlength="20"/>
            <br>
            <br>
            <br>
            <span class="edit-profile-label">Новий пароль</span>
            <br>
            <input class="edit-profile-input" id="user-new-password" type="password" value="" maxlength="20"/>
            <br>
            <br>
            <br>
            <span class="edit-profile-label">Новий пароль ще раз</span>
            <br>
            <input class="edit-profile-input" id="user-new-password-repeat" type="password" value="" maxlength="20"/>
            <br>
            <br>
            <br>
            <span id="submit-change-profile">Підтвердити</span>
            <br>
            <br>
            <span id="edit-profile-error"></span>
        </div>
    </div>
    <div id="add-new-idea-button"></div>
    <div id="popup"></div>
    <div id="my-profile">
        <span id="my-ideas">Мої ідеї</span>
        <br>
        <br>
        <span id="edit-profile">Редагувати профіль</span>
    </div>
    <div id="dialog">
        <p></p>
    </div>
    <div id="social-block">
        <div class="fb-like" data-href="http://test31415.seeua.com/main.php" data-layout="box_count" data-action="like" data-show-faces="true" data-share="true"></div>
        <br>
        <br>
        <div id="vk_like"></div>
        <script type="text/javascript">
            VK.Widgets.Like("vk_like", {type: "vertical", height: 24});
        </script>
        <br>
        <script src="https://apis.google.com/js/platform.js" async defer></script>
        <g:plusone size="tall" annotation="bubble"></g:plusone>
        <br>
        <a href="https://twitter.com/share" class="twitter-share-button" data-via="DenysZaiats" data-count="vertical">Tweet</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </div>
<!--    <div id="weather-block">-->
<!--        <a href="http://www.accuweather.com/uk/ua/kyiv/324505/weather-forecast/324505" class="aw-widget-legal">-->
<!--            <!---->
<!--            By accessing and/or using this code snippet, you agree to AccuWeather’s terms and conditions (in English) which can be found at http://www.accuweather.com/en/free-weather-widgets/terms and AccuWeather’s Privacy Statement (in English) which can be found at http://www.accuweather.com/en/privacy.-->
<!--            -->-->
<!--        </a><div id="awcc1428339351369" class="aw-widget-current"  data-locationkey="324505" data-unit="c" data-language="uk" data-useip="false" data-uid="awcc1428339351369"></div><script type="text/javascript" src="http://oap.accuweather.com/launch.js"></script>-->
<!--    </div>-->
</body>
</html>