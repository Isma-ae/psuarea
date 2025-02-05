$(function () {

    $('.list-group-item').find("a").on('click', function () {
        $('.ti-icon', this)
            .toggleClass('ti-angle-right')
            .toggleClass('ti-angle-down');
    });

});