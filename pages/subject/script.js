$(document).ready(function () {

    load_community()

    function load_community() {
        var community_id = $('#community_id').val();
        if (community_id != "") {
            $.post("pages/subject/action.php", {
                    fn: "load_community",
                    community_id: community_id
                },
                function (data) {
                    $('#title').html(data[0].community_title);
                    $('#img').html('<img src="files/community/' + data[0].community_img + '" width="25%">');
                },
                "json"
            );
        }
    }

    load_subject(1);

    function load_subject(page_number) {
        var community_id = $('#community_id').val();
        var com_link = "";
        if (community_id != "") {
            com_link = '&community=' + community_id;
        }
        $.post("pages/subject/action.php", {
                fn: "load_subject",
                community_id: community_id,
                search_query: $('#subject_name').val(),
                page: page_number
            },
            function (data) {
                var html = '';
                $.each(data.data, function (i, v) {
                    html += '<div class="my-blog"><p>';
                    html += '<a href="?p=item-subject' + com_link + '&subject_name=' + v.subject_name + '" class="my-ink">' + v.subject_name + '</a> ';
                    html += '<span class="badge badge-primary">' + v.count_subject + '</span>';
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
        load_subject(page_number);
    });

    $(document).on('click', '#pagination_link .prev', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page > 1) {
            load_subject(current_page - 1);
        }
    });

    $(document).on('click', '#pagination_link .next', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page < window.total_pages) {
            load_subject(current_page + 1);
        }
    });

    $('#search-by-subject').click(function (e) {
        e.preventDefault();
        load_subject(1);
    });
});