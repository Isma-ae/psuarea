$(function () {

    $('.list-group-item').on('click', function () {
        $('.ti-icon', this)
            .toggleClass('ti-angle-right')
            .toggleClass('ti-angle-down');
    });

});