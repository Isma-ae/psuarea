load_data(query = '');

$('#search').keyup(function (e) {
    var query = $('#search').val();
    load_data(query);
});

function load_data(query, page_number = 1) {
    var form_data = new FormData();
    form_data.append('query', query);
    form_data.append('page', page_number);
    form_data.append('fn', 'load_item');

    $.ajax({
        url: 'pages/item-list/action.php',
        method: 'POST',
        data: form_data,
        contentType: false,
        processData: false,
        success: function (response) {
            var data = JSON.parse(response);
            var html = '';
            if (data.data.length > 0) {
                $.each(data.data, function (index, post) {
                    html += '<tr>';
                    html += '<td>' + (index + 1) + '</td>';
                    html += '<td><img src="../files/item/' + post.item_cover + '?t=' + new Date().getTime() + '" width="150px;"></td>';
                    html += '<td>' + post.item_title + '</td>';
                    html += '<td>' + post.writer_name + '</td>';
                    html += '<td><button type="button" class="btn btn-sm btn-success edit"><i class="fas fa-upload"></i></button></td>';
                    html += '<td><div class="btn-list">';
                    html += '<button type="button" class="btn btn-sm btn-info edit"><i class="icon-eye"></i></button>';
                    html += '<button type="button" class="btn btn-sm btn-warning edit"><i class="icon-note"></i></button>';
                    html += '<button type="button" class="btn btn-sm btn-danger delete-community"><i class="icon-trash"></i></button>';
                    html += '</div></td>';
                    html += '</tr>';
                });
            } else {
                html += '<tr><td colspan="5" class="text-center">No Data Found</td></tr>';
            }
            $('#post_data').html(html);
            $('#total_data').html(data.total_data);
            $('#pagination_link').html(data.pagination);
        }
    });
}