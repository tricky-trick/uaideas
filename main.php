<?php
if (!isset($_COOKIE['USER_IN'])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head lang="ua">
    <meta charset="UTF-8">
    <title>Збудуй майбутнє сам!</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/mainbackground.css" />
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
    <script type="text/javascript" src="js/mainpageactions.js"></script>
    <script type="text/javascript" src="js/mainpageloaddata.js"></script>
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
</head>
<body>
    <div id="main-content">

        <div id="top-side-panel">
            <table id="top-panel-menu">
                <tbody>
                <tr>
                    <td>
<!--                        <input type="text" id="search-input" placeholder="Пошук" autocomplete="off" style="padding: 4px 40px 5px 21px;">-->
                    </td>
                    <td>
                        <span id="profile-link" class="top-panel-menu-items">Мій профіль</span>
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
                        <input type="text" id="search-city" value="" autocomplete="on" onkeypress="validateName(event, 'search-city', 'region-select')" placeholder="Введіть назву міста" style="height: 22px;">
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
            <ul id="list-ideas-content">
<!--                <li>-->
<!--                    <div class="left-side-block">-->
<!--                        <div class="image-ideas-item">-->
<!---->
<!--                        </div>-->
<!--                        <span class="info-ideas-item">Рейтинг: 100 <br><br> Від 12 січня 2015 року</span>-->
<!--                        <span class="id-ideas-item"></span>-->
<!---->
<!--                    </div>-->
<!--                    <div class="right-side-block">-->
<!--                        <span class="subject-ideas-item">-->
<!--                            Ремонт дороги на вулиці Липинського-->
<!--                        </span>-->
<!--                        <br>-->
<!--                        <br>-->
<!--                        <span class="description-ideas-item">-->
<!--                            Ремонт дороги на вулиці Липинського необхідно провести ремонту вулиці, котрає в жахливому стані.-->
<!--                            Для цього прооную зробити шось корисне. І т.д....-->
<!--                        </span>-->
<!--                    </div>-->
<!--                </li>-->
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
            <br>
            <br>
            <div id="idea-show-map" coord="">Показати на карті</div>
            <div id="map-idea" style="width: 100%; height: 0px;">
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
    <!--            <li>-->
    <!--                <span class="ideas-comments-author">Микола Головай</span>-->
    <!--                <span class="ideas-comments-date">додано 28 Лютого 2015</span>-->
    <!--                <div class="ideas-comments-text">Підтримую ідею! Готовий братися до виконання. Пишіть в приват для контакту.</div>-->
    <!--            </li>-->
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
            <div id="editable-subject-area"><input type="text" maxlength="100" value="Назва ідеї..."></div>
            <br>
            <br>
            <div id="editable-body-area">
                <textarea maxlength="5000">Введіть опис ідеї...</textarea>
            </div>
            <br>
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
                        <input type="text" id="new-idea-search-city" value="" autocomplete="on" onkeypress="validateName(event, 'new-idea-search-city', 'new-idea-region-select')" placeholder="Введіть назву міста" style="height: 22px;">
                    </td>
                    <td width="34%">
                        <select id="new-idea-category-select">
                            <option>Оберіть категорію</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
            <br>
            <br>
            <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
                <input type="file" name="file[]" id="new-idea-image-upload" value="Завантажити фото" multiple />
            </form>
            <div id="new-idea-show-map">Додати координати на карті</div>
            <div id="map" style="width: 100%; height: 0px;">
                <div id="map-canvas" style="width: 100%; height: 100%;">
                </div>
            </div>
            <br>
            <span id="add-new-idea">Додати ідею</span>
            <img src="img/done-icon.png" id="done-icon"/>
            <span id="cancel-new-idea">Скасувати</span>
        </div>
    </div>
    <div id="add-new-idea-button">+</div>
    <div id="popup"></div>
    <div id="my-profile">
        <span id="my-ideas">Мої ідеї</span>
        <br>
        <br>
        <span id="edit-profile">Редагувати профіль</span>
    </div>
    <div id="dialog">
        <p>Ви справді бажаєте видалити цю ідею?</p>
    </div>
</body>
</html>