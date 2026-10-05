$(document).ready(function () {

    load_item();

    function load_item() {
        $.post("pages/items/action.php", {
                fn: "load_item",
                item_id: $("#item_id").val()
            }, function (data) {
                if (data.item.length == 0) {
                    return;
                }
                $('#title').html(data.item[0].item_title);
                if (data.cover.length > 0) {
                    $('#cover').html('<img src="files/item/' + data.item[0].item_id + '/' + data.cover[0].file_name + '" alt="Cover Image" width="40%">');
                } else {
                    $('#cover').html('<img src="img/930231.png" alt="No Cover" width="40%">');
                }
                $('#year').html(data.item[0].item_issued_year);
                var html = "";
                $.each(data.author, function (index, value) {
                    html += '<p>' + value.writer_fname + ' ' + value.writer_lname + '</p>';
                });
                $('#author').html(html);
                $('#publisher').html(data.item[0].item_publisher);
                $('#abstract').html(data.item[0].item_abstract);
                $('#abstract').html(data.item[0].item_abstract);
                var subjectNames = "";
                $.each(data.sbj, function (i, v) {
                    subjectNames += '<a href="?p=search&subject_name=' + v.subject_name + '">' + v.subject_name + '</a>';
                    if (i < data.sbj.length - 1) {
                        subjectNames += ", ";
                    }
                });
                $('#sbj').html(subjectNames);
                $('#collection').html(data.item[0].collection_name);
                if (data.item[0].item_uri != "") {
                    $('#elink').html('<p>อ่านอีบุ๊ก <a href="' + data.item[0].item_uri + '" class="genric-btn link-border">คลิ๊กที่นี่</a></p>');
                }
            },
            "json"
        );
    }


    let limit = 5;
    let offset = 0;

    $('#collapse').hide();

    load_files();

    function load_files() {
        $.ajax({
            type: "post",
            url: "pages/items/action.php",
            data: {
                fn: "load_files",
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