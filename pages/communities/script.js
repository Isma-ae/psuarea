$(document).ready(function () {
    load_community();

    function load_community() {
        $.post("pages/communities/action.php", {
                fn: "load_community",
                community_id: $('#community_id').val()
            },
            function (data) {
                $('.community_title').html(data.data[0].community_title);
                $('#community_img').html('<img src="files/community/' + data.data[0].community_img + '" width="25%">');
            },
            "json"
        );
    }

    load_collection(1);

    function load_collection(page_number) {
        var community_id = $('#community_id').val();
        $.post("pages/communities/action.php", {
                fn: "load_collection",
                community_id: community_id,
                page: page_number
            },
            function (data) {
                var html = '';
                $.each(data.data, function (i, v) {
                    html += '<div class="my-blog"><p>';
                    html += '<a href="?p=community-collection&community=' + community_id + '&collection=' + v.collection_id + '" class="my-ink">' + v.collection_name + '</a> ';
                    html += '<span class="badge badge-primary">' + v.count_collection + '</span>';
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
        load_collection(page_number);
    });

    $(document).on('click', '#pagination_link .prev', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page > 1) {
            load_collection(current_page - 1);
        }
    });

    $(document).on('click', '#pagination_link .next', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page < window.total_pages) {
            load_collection(current_page + 1);
        }
    });
});