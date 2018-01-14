$(function () {
    $(".preloader").fadeOut();

    $('.tooltips').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    $('[data-toggle="popover"]').popover()

});
