$(document).ready(function () {
    let limit = 5;
    let offset = 0;

    $('#collapse').hide();

    load_files();

    function load_files() {
        $.ajax({
            type: "post",
            url: "pages/items/action.php",
            data: {
                item_id: $("#item_id").val(),
                limit: limit,
                offset: offset,
            },
            success: function (res) {
                var data = JSON.parse(res);
                if (data.data.length > 0) {
                    $.each(data.data, function (index, value) {
                        var html = '';
                        html += '<p><a href="files/item/' + value.item_id + '/' + value.file_name + '" target="_blank"><i class="fa fa-caret-right"></i> ' + value.file_description + '</p></p>';
                        $("#file-data").append(html);
                    });
                    offset += 5;
                } else {
                    $("#showMore").hide();
                }
            }
        });
    }
});