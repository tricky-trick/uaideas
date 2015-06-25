$(document).ready(function() {

    /*
    Load regions
     */
    var selectRegion = $("#region-select, #new-idea-region-select");
    var getRegions = $.ajax({
        type: "GET",
        url: "api/region.php",
        dataType: 'json',
        status: 200,
        statusText: "OK",
        cache: false
    });

    getRegions.done(function (datas) {
        for(var i = 0; i < datas.length; i++){
            selectRegion.append("<option index=\"" + datas[i]['region_id'] + "\">" + datas[i]['name'] + "</option>");
        }
    });

    /*
     Load categories
     */
    var selectCategory = $("#category-select, #new-idea-category-select");
    var getCategory = $.ajax({
        type: "GET",
        url: "api/categories.php",
        dataType: 'json',
        status: 200,
        statusText: "OK",
        cache: false
    });

    getCategory.done(function (datas) {
        for(var i = 0; i < datas.length; i++){
            selectCategory.append("<option index=\"" + datas[i]['category_id'] + "\">" + datas[i]['name'] + "</option>");
        }
    });

    /*
    Auto complete load cities
     */
        var regionId = $("#region-select").find(":selected").index();
        $("#search-city").autocomplete({source: 'auto.php?region_id=' + regionId, minLength: 2});

        $("#search-city").focusout(function () {
            if ($(".ui-menu-item").size() == 1) {
                var val = $(".ui-menu-item").text();
                $(this).val(val);
                $(".ui-menu-item").remove();
            }
        });

    var regionIdNewIdea = $("#new-idea-region-select").find(":selected").index();
    $("#new-idea-search-city").autocomplete({source: 'auto.php?region_id=' + regionIdNewIdea, minLength: 2});

    $("#new-idea-search-city").focusout(function () {
        if ($(".ui-menu-item").size() == 1) {
            var val = $(".ui-menu-item").text();
            $(this).val(val);
            $(".ui-menu-item").remove();
        }
    });

    /*
    Search everything
     */

    //$("#search-input").autocomplete({source: 'autosearch.php', minLength: 2});
    //
    //$("#search-input").focusout(function () {
    //    if ($(".ui-menu-item").size() == 1) {
    //        var val = $(".ui-menu-item").text();
    //        $(this).val(val);
    //        $(".ui-menu-item").remove();
    //    }
    //});
});

//function search(id){
//    $("#" + id).autocomplete({source: 'autosearch.php', minLength: 2, open: function(){
//        setTimeout(function () {
//            $('.ui-autocomplete').css('z-index', 99999999999999);
//        }, 10);
//    }});
//
//    $("#" + id).focusout(function () {
//        if ($(".ui-menu-item").size() == 1) {
//            var val = $(".ui-menu-item").text();
//            $(this).val(val);
//            $(".ui-menu-item").remove();
//        }
//    });
//}

function validateName(evt, id, regionIdSelector) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode( key );
    var regex_error = /[A-Z]|[a-z]|\{|\}|\[|\]|\:|\;|\"|\'|\<|\,|\>|\.|\||\\/;
    var regex = /[А-Я]|[а-я]|[і]|[ї]|[І]|[Ї]|[Ґ]|[Є]|[\b]|\-/;
    var result = '';
    if(regex_error.test(key)){
        var A = {};

        A["Q"]="Й";A["W"]="Ц";A["E"]="У";A["R"]="К";A["T"]="Е";A["Y"]="Н";A["U"]="Г";A["I"]="Ш";A["O"]="Щ";A["P"]="З";A["{"]="Х";A["}"]="Ї";A["|"]="Ґ";
        A["q"]="й";A["w"]="ц";A["e"]="у";A["r"]="к";A["t"]="е";A["y"]="н";A["u"]="г";A["i"]="ш";A["o"]="щ";A["p"]="з";A["["]="х";A["]"]="ї";A["\\"]="ґ";
        A["A"]="Ф";A["S"]="І";A["D"]="В";A["F"]="А";A["G"]="П";A["H"]="Р";A["J"]="О";A["K"]="Л";A["L"]="Д";A[":"]="Ж";A["\""]="Є";
        A["a"]="ф";A["s"]="і";A["d"]="в";A["f"]="а";A["g"]="п";A["h"]="р";A["j"]="о";A["k"]="л";A["l"]="д";A[";"]="ж";A["'"]="є";
        A["Z"]="Я";A["X"]="Ч";A["C"]="С";A["V"]="М";A["B"]="И";A["N"]="Т";A["M"]="Ь";A["<"]="Б";A[">"]="Ю";
        A["z"]="я";A["x"]="ч";A["c"]="с";A["v"]="м";A["b"]="и";A["n"]="т";A["m"]="ь";A[","]="б";A["."]="ю";

        result = document.getElementById(id).value + A[key];

    }
    else{
        result += document.getElementById(id).value;
    }
    if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
    }
    //document.getElementById(id).value = result;

    var regionId = $("#" + regionIdSelector).find(":selected").index();
    $("#" + id).autocomplete({source: 'auto.php?region_id=' + regionId, minLength: 2, open: function(){
        setTimeout(function () {
            $('.ui-autocomplete').css('z-index', 99999999999999);
        }, 10);
    }});

    $("#" + id).focusout(function () {
        if ($(".ui-menu-item").size() == 1) {
            var val = $(".ui-menu-item").text();
            $(this).val(val);
            $(".ui-menu-item").remove();
        }
    });
}

function validateAmount(selector, maxLength, minLength){
    var valLength = $(selector).val().length;
     if (maxLength == 100){
        $("#ideas-subject-counter").text(valLength + " з 100");
         if(valLength < minLength){
             $("#ideas-subject-counter").css("color", "red");
         }
         else{
             $("#ideas-subject-counter").css("color", "green");
         }
     }
     else if(maxLength == 5000){
         $("#ideas-body-counter").text(valLength + " з 5000");
         if(valLength < minLength){
             $("#ideas-body-counter").css("color", "red");
         }
         else{
             $("#ideas-body-counter").css("color", "green");
         }
     }
}
