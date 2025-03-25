$(document).ready(function () {

    //-------------------- จบวันที่ -------------------//
    $("body").on("change", ".my-check", function () {
        load_data(searchQuery, 1);
    });

    $('#order_by').change(function (e) {
        e.preventDefault();
        load_data(searchQuery, 1);
    });

    $('#limit').change(function (e) {
        e.preventDefault();
        load_data(searchQuery, 1);
    });
    var searchQuery = $('#search_query').val();

    load_data(searchQuery, 1);

    function load_data(query, page_number) {
        var form_data = new FormData();
        var type_name = [];
        $.each($("[name^=type_name]:checked"), function () {
            type_name.push($(this).val());
        });
        form_data.append('query', query);
        form_data.append('type_name', type_name.length > 0 ? JSON.stringify(type_name) : '');
        form_data.append('order_by', $('#order_by').val());
        form_data.append('limit', $('#limit').val());
        form_data.append('page', page_number);
        form_data.append('fn', 'load_item');

        $.ajax({
            url: 'pages/search-srsc/action.php',
            method: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function (response) {
                var data = JSON.parse(response);
                var html = '';
                if (data.data.length > 0) {
                    $.each(data.data, function (index, post) {
                        var cover = (post.cover_name && post.cover_name !== "null") ?
                            '<img src="files/item/' + post.item_id + '/' + post.cover_name + '" alt="Cover Image">' :
                            '<img src="img/930231.png" alt="No Cover">';
                        html += '<div class="blog-author row my-blog" style="margin-bottom:20px;">';
                        html += '<div class="col-2"><a href="?p=item&item_id=' + post.item_id_md5 + '">' + cover + '</a></div>';
                        html += '<div class="col-10">';
                        html += '<span class="badge badge-primary">รายการ</span>';
                        html += '<a href="?p=item&item_id=' + post.item_id_md5 + '"><h4>' + post.item_title + '</h4></a>';
                        html += '<ul class="blog-info-link"><li>(' + post.item_publisher + ', ' + post.item_issued_year + ') ' + post.writer_names + '</li></ul>';
                        html += '<p>' + post.item_abstract + '</p>';
                        html += '</div>';
                        html += '</div>';
                    });
                } else {
                    html += '<tr><td colspan="5" class="text-center">No Data Found</td></tr>';
                }
                $('#item-data').html(html);
                $('#pagination_link').html(data.pagination);
                $('#pagination_info').text(data.pagination_info);

                window.total_pages = data.total_pages;
            }
        });
    }

    $(document).on('click', '#pagination_link .page-link', function (e) {
        e.preventDefault();
        var page_number = $(this).data('page');
        var searchQuery = $('#search_query').val();
        load_data(searchQuery, page_number);
    });

    $(document).on('click', '#pagination_link .prev', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page > 1) {
            var searchQuery = $('#search_query').val();
            load_data(searchQuery, current_page - 1);
        }
    });

    $(document).on('click', '#pagination_link .next', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page < window.total_pages) {
            var searchQuery = $('#search_query').val();
            load_data(searchQuery, current_page + 1);
        }
    });

    $('#search-button').on('click', function () {
        var searchQuery = $('#search_query').val();
        load_data(searchQuery, 1);
    });
});