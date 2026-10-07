$(function () {
    $('#search').click(function (e) {
        e.preventDefault();
        var search_term = $('#serch-input').val();
        window.location.href = '?p=search&search_term=' + search_term;
    });

    load_page();

    function load_page() {
        $.ajax({
            type: "post",
            url: "pages/home/action.php",
            data: {
                fn: "load_page"
            },
            dataType: "json",
            success: function (res) {
                if (res.data[0].page_banner != "") {
                    $('#banner-img').attr('src', 'files/banner/' + res.data[0].page_banner);
                    $('.my_single_feature_part').find('h4').html(res.data[0].page_title);
                }
            }
        });
    }

    load_community();

    function load_community(page_number = 1) {
        var form_data = new FormData();
        form_data.append('page', page_number);
        form_data.append('fn', 'load_community');

        $.ajax({
            url: 'pages/home/action.php',
            method: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function (response) {
                var data = JSON.parse(response);
                var html = '';

                if (data.data.length > 0) {
                    $.each(data.data, function (index, post) {
                        html += '<div class="col-lg-3 col-sm-6">';
                        html += '<article class="blog_item">';
                        html += '<div class="blog_item_img">';
                        html += '<a class="d-inline-block" href="?p=communities&community=' + post.community_id + '"><img class="card-img rounded-0" src="files/community/' + post.community_img + '?t=' + new Date().getTime() + '" alt=""></a>';
                        html += '</div>';
                        html += '<div class="blog_details">';
                        html += '<a class="d-inline-block" href="?p=communities&community=' + post.community_id + '">';
                        html += '<h4>' + post.community_title + '</h4>';
                        html += '</a>';
                        html += '<p>' + post.community_description + '</p>';
                        html += '</div>';
                        html += '</article>';
                        html += '</div>';
                    });
                } else {
                    html += '<div class="col-lg-3 col-sm-6"><p>No Data Found</p></div>';
                }
                $('.community_data').html(html);
                $('#pagination_link').html(data.pagination);
                $('#pagination_info').text(data.pagination_info);
                window.total_pages = data.total_pages;
            }
        });
    }

    $(document).on('click', '#pagination_link .page-link', function (e) {
        e.preventDefault();
        var page_number = $(this).data('page');
        load_community(page_number);
    });

    $(document).on('click', '#pagination_link .prev', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page > 1) {
            load_community(current_page - 1);
        }
    });

    $(document).on('click', '#pagination_link .next', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page < window.total_pages) {
            load_community(current_page + 1);
        }
    });

    load_item();

    function load_item(page_number = 1) {
        var form_data = new FormData();
        form_data.append('page', page_number);
        form_data.append('fn', 'load_item');
        $.ajax({
            url: 'pages/home/action.php',
            method: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function (response) {
                var data = JSON.parse(response);
                var html = '';
                if (data.data.length > 0) {
                    $.each(data.data, function (index, post) {
                        var cover = (post.file_name && post.file_name !== "null") ?
                            '<img src="files/item/' + post.item_id + '/' + post.file_name + '" alt="Cover Image">' :
                            '<img src="img/930231.png" alt="No Cover">';
                        
                        var abstractRaw = post.item_abstract_clean || post.item_abstract || '';
                        var tmp = document.createElement("DIV");
                        tmp.innerHTML = abstractRaw;
                        var cleanAbstract = (tmp.textContent || tmp.innerText || "").replace(/\s+/g, ' ').trim();
                        var safeAbstractHtml = $('<div>').text(cleanAbstract).html();

                        html += '<div class="blog-author row my-blog" style="margin-bottom:20px;">';
                        html += '<div class="col-2"><a href="?p=items&item_id=' + post.item_id_md5 + '">' + cover + '</a></div>';
                        html += '<div class="col-10">';
                        html += '<span class="badge badge-primary item">รายการ</span>';
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
            }
        });
    }

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
});