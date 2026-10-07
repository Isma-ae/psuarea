$(document).ready(function () {

    load_community()

    function load_community() {
        var community_id = $('#community_id').val();
        if (community_id != "") {
            $.post("pages/title/action.php", {
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

    load_data(1);

    function load_data(page_number) {
        var form_data = new FormData();
        form_data.append('fn', 'load_item');
        form_data.append('community_id', $('#community_id').val());
        form_data.append('item_title', $('#item_title').val());
        form_data.append('page', page_number);

        $.ajax({
            url: 'pages/title/action.php',
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
                        
                        var abstractRaw = post.item_abstract_clean || post.item_abstract || '';
                        var tmp = document.createElement("DIV");
                        tmp.innerHTML = abstractRaw;
                        var cleanAbstract = (tmp.textContent || tmp.innerText || "").replace(/\s+/g, ' ').trim();
                        var safeAbstractHtml = $('<div>').text(cleanAbstract).html();

                        html += '<div class="blog-author row my-blog" style="margin-bottom:20px;">';
                        html += '<div class="col-2"><a href="?p=items&item_id=' + post.item_id_md5 + '">' + cover + '</a></div>';
                        html += '<div class="col-10">';
                        html += '<span class="badge badge-primary">รายการ</span>';
                        html += '<a href="?p=items&item_id=' + post.item_id_md5 + '"><h4>' + post.item_title + '</h4></a>';
                        html += '<ul class="blog-info-link"><li>(' + post.item_publisher + ', ' + post.item_issued_year + ') ' + post.writer_names + '</li></ul>';
                        html += '<p class="item-abstract">' + safeAbstractHtml + '</p>';
                        if (cleanAbstract && cleanAbstract.length > 0) {
                            html += '<a href="javascript:void(0);" class="btn-toggle-abstract"><i class="ti-angle-down"></i> แสดงเพิ่มเติม</a>';
                        }
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
        load_data(page_number);
    });

    $(document).on('click', '#pagination_link .prev', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page > 1) {
            load_data(current_page - 1);
        }
    });

    $(document).on('click', '#pagination_link .next', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page < window.total_pages) {
            load_data(current_page + 1);
        }
    });

    $('#item_issued_year,#item_issued_month').change(function (e) {
        e.preventDefault();
        load_data(1);
    });

    $('#search-by-date').click(function (e) {
        e.preventDefault();
        load_data(1);
    });

    $(document).off('click', '.btn-toggle-abstract').on('click', '.btn-toggle-abstract', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $abstract = $btn.prev('.item-abstract');
        if (!$abstract.length) {
            $abstract = $btn.siblings('.item-abstract');
        }
        if (!$abstract.length) {
            $abstract = $btn.closest('div').find('.item-abstract');
        }
        if ($abstract.hasClass('expanded')) {
            $abstract.removeClass('expanded');
            $abstract.css({
                'display': '-webkit-box',
                '-webkit-line-clamp': '3',
                '-webkit-box-orient': 'vertical',
                'overflow': 'hidden',
                'text-overflow': 'ellipsis',
                'max-height': '',
                'height': ''
            });
            $btn.html('<i class="ti-angle-down"></i> แสดงเพิ่มเติม');
        } else {
            $abstract.addClass('expanded');
            $abstract.css({
                'display': 'block',
                '-webkit-line-clamp': 'none',
                '-webkit-box-orient': 'horizontal',
                'overflow': 'visible',
                'text-overflow': 'clip',
                'max-height': 'none',
                'height': 'auto'
            });
            $btn.html('<i class="ti-angle-up"></i> ย่อ');
        }
    });

    $('#search-by-title').click(function (e) {
        e.preventDefault();
        load_data(1);
    });
});