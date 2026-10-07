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
                            '<img src="files/item/' + post.item_id + '/' + encodeURIComponent(post.cover_name) + '" alt="Cover Image">' :
                            '<img src="img/930231.png" alt="No Cover">';
                        var pubYear = [];
                        if (post.item_publisher && String(post.item_publisher).trim() !== '') {
                            pubYear.push(String(post.item_publisher).trim());
                        }
                        if (post.item_issued_year && String(post.item_issued_year).trim() !== '') {
                            pubYear.push(String(post.item_issued_year).trim());
                        }
                        var pubYearStr = pubYear.length > 0 ? '(' + pubYear.join(', ') + ') ' : '';
                        var writerStr = post.writer_names ? post.writer_names : '<span class="text-muted">ไม่ระบุผู้เขียน</span>';

                        var abstractRaw = post.item_abstract_clean || post.item_abstract || '';
                        var tmp = document.createElement("DIV");
                        tmp.innerHTML = abstractRaw;
                        var cleanAbstract = (tmp.textContent || tmp.innerText || "").replace(/\s+/g, ' ').trim();
                        var safeAbstractHtml = $('<div>').text(cleanAbstract).html();

                        html += '<div class="blog-author row my-blog" style="margin-bottom:20px;">';
                        html += '  <div class="col-12 col-sm-3 col-md-2 text-center text-sm-left mb-3 mb-sm-0"><a href="?p=items&item_id=' + post.item_id_md5 + '">' + cover + '</a></div>';
                        html += '  <div class="col-12 col-sm-9 col-md-10">';
                        html += '    <span class="badge badge-primary">รายการ</span>';
                        html += '    <a href="?p=items&item_id=' + post.item_id_md5 + '"><h4 class="item-title">' + $('<div>').text(post.item_title).html() + '</h4></a>';
                        html += '    <ul class="blog-info-link"><li>' + pubYearStr + writerStr + '</li></ul>';
                        html += '    <p class="item-abstract">' + safeAbstractHtml + '</p>';
                        if (cleanAbstract && cleanAbstract.length > 0) {
                            html += '    <a href="javascript:void(0);" class="btn-toggle-abstract"><i class="ti-angle-down"></i> แสดงเพิ่มเติม</a>';
                        }
                        html += '  </div>';
                        html += '</div>';
                    });
                } else {
                    html += '<div class="alert alert-light text-center py-5 border"><i class="ti-search mb-2 d-block" style="font-size: 2rem;"></i>ไม่พบผลการค้นหาที่ตรงกับเงื่อนไข</div>';
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
                'max-height': ''
            });
            $btn.html('<i class="ti-angle-down"></i> แสดงเพิ่มเติม');
        } else {
            $abstract.addClass('expanded');
            $abstract.css({
                'display': 'block',
                '-webkit-line-clamp': 'unset',
                '-webkit-box-orient': 'unset',
                'overflow': 'visible',
                'text-overflow': 'unset',
                'max-height': 'none'
            });
            $btn.html('<i class="ti-angle-up"></i> ย่อ');
        }
    });

    $('#search-button').on('click', function () {
        var searchQuery = $('#search_query').val();
        load_data(searchQuery, 1);
    });
});