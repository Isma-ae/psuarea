$(function () {
    load_data();

    function load_data(query = '', page_number = 1) {
        var form_data = new FormData();
        form_data.append('query', query);
        form_data.append('page', page_number);
        form_data.append('fn', 'load_community');

        $.ajax({
            url: 'pages/community/action.php',
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
                        html += '<td>' + post.post_id + '</td>';
                        html += '<td>' + post.post_title + '</td>';
                        html += '<td>' + post.post_description + '</td>';
                        html += '</tr>';
                    });
                } else {
                    html += '<tr><td colspan="3" class="text-center">No Data Found</td></tr>';
                }

                $('#post_data').html(html);
                $('#total_data').html(data.total_data);
                $('#pagination_link').html(data.pagination);
            }
        });
    }
});