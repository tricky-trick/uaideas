$(document).ready(function() {
    $("#login-form-reg").click(function () {
        var marginTop = $(document).scrollTop();
        $("#reg-form").css("opacity", "1");
        $("#reg-form").css("-webkit-transform", "translate(101%," + "0%)");
        $("#reg-form").css("-moz-transform", "translate(101%," + "0%)");
        $("#reg-form").css("-ms-transform", "translate(101%," + "0%)");
        $("#reg-form").css("-o-transform", "translate(101%," + "0%)");
        return false;
    });

    $("#reg-form-cancel").click(function () {
        var marginTop = $(document).scrollTop();
        $("#reg-form").css("opacity", "0");
        $("#reg-form").css("-webkit-transform", "translate(-101%," + "0%)");
        $("#reg-form").css("-moz-transform", "translate(-101%," + "0%)");
        $("#reg-form").css("-ms-transform", "translate(-101%," + "0%)");
        $("#reg-form").css("-o-transform", "translate(-101%," + "0%)");
        $("#reg-form").css("-o-transform", "translate(-101%," + "0%)");
        $("#reg-label-error").text("");
        return false;
    });
});

function escapeSpecialSymbols(id, evt){
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode( key );
    var pattern = /[a-z]|[A-Z]|[0-9]|[А-Я]|[а-я]|[і]|[ї]|[І]|[Ї]|[Ґ]|\-| /;
    var result = "";

    if(!pattern.test(key)){
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
    }
}
