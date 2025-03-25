$(document).ready(function () {
    load_author(1);

    function load_author(page_number) {
        var collection_id = $('#collection_id').val();
        var community_id = $('#community_id').val();
        var com_link = "";
        if (community_id != "") {
            com_link = '&community=' + community_id;
        }
        $.post("pages/collection-author/action.php", {
                fn: "load_author",
                community_id: community_id,
                collection_id: collection_id,
                search_query: $('#author_name').val(),
                page: page_number
            },
            function (data) {
                var html = '';
                $.each(data.data, function (i, v) {
                    html += '<div class="my-blog"><p>';
                    html += '<a href="?p=collection-item-author&collection=' + collection_id + com_link + '&author_name=' + v.author_name + '" class="my-ink">' + v.author_name + '</a> ';
                    html += '<span class="badge badge-primary">' + v.count_author + '</span>';
                    html += '</p></div>';
                });
                $('.my-item').html(html);
                $('#pagination_link').html(data.pagination);
                $('#pagination_info').text(data.pagination_info);
                window.total_pages = data.total_pages;
            },
            "json"
        );
    }

    $(document).on('click', '#pagination_link .page-link', function (e) {
        e.preventDefault();
        var page_number = $(this).data('page');
        load_author(page_number);
    });

    $(document).on('click', '#pagination_link .prev', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page > 1) {
            load_author(current_page - 1);
        }
    });

    $(document).on('click', '#pagination_link .next', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page < window.total_pages) {
            load_author(current_page + 1);
        }
    });

    $('#search-by-author').click(function (e) {
        e.preventDefault();
        load_author(1);
    });
});