$(function () {

    /*$('.list-group-item').find("a").on('click', function () {
        $('.ti-icon', this)
            .toggleClass('ti-angle-right')
            .toggleClass('ti-angle-down');
    });*/

    load_subject();

    function load_subject() {
        $.post("pages/srsc/action.php", {
                fn: "load_subject",
                search_query: $('#search_query').val()
            },
            function (data) {
                var html = '';
                $.each(data.data, function (index, value) {
                    html += '<div class="list-group-item my-list-group-item">';
                    html += '<div class="form-check" style="margin-left: 10px;">';
                    html += '<input type="checkbox" class="form-check-input" name="type_name[]" id="type_name' + (index + 1) + '" value="' + value.type_name + '">';
                    html += '<label class="form-check-label" for="type_name' + (index + 1) + '">' + value.type_name + '</label>';
                    html += '</div>';
                    html += '</div>';
                });
                $('.type-data').html(html);
            },
            "json"
        );
    }

    $('#btn-search').click(function (e) {
        e.preventDefault();
        load_subject();
    });

    $('#btn-reset').click(function (e) {
        e.preventDefault();
        $('#search_query').val('');
        load_subject();
    });

});