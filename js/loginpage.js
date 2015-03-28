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