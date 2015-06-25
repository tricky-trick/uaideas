$(document).ready(function() {
    $("#login-form-reg").click(function () {
        var marginTop = $(document).scrollTop();
        $("#reg-form").css("opacity", "1");
        $("#reg-form").css("-webkit-transform", "translate(0%," + "470px)");
        $("#reg-form").css("-moz-transform", "translate(0%," + "470px)");
        $("#reg-form").css("-ms-transform", "translate(0%," + "470px)");
        $("#reg-form").css("-o-transform", "translate(0%," + "470px)");
        return false;
    });

    $("#reg-form-cancel").click(function () {
        var marginTop = $(document).scrollTop();
        $("#reg-form").css("opacity", "0");
        $("#reg-form").css("-webkit-transform", "translate(0%," + "100px)");
        $("#reg-form").css("-moz-transform", "translate(0%," + "100px)");
        $("#reg-form").css("-ms-transform", "translate(0%," + "100px)");
        $("#reg-form").css("-o-transform", "translate(0%," + "100px)");
        $("#reg-label-error").text("");
        return false;
    });
});