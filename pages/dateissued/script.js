$(document).ready(function () {

    load_community()

    function load_community() {
        var community_id = $('#community_id').val();
        if (community_id != "") {
            $.post("pages/dateissued/action.php", {
                    fn: "load_community",
                    community_id: community_id
                },
                function (data) {
                    $('#title').html(data[0].community_title);
                    $('.community_title').after('<li class="breadcrumb-item active"><a href="?p=communities&community=' + community_id + '">' + data[0].community_title + '</a></li>');
                    $('#img').html('<img src="files/community/' + data[0].community_img + '" width="25%">');
                },
                "json"
            );
        }
    }

    load_year()

    function load_year() {
        if (community_id != "") {
            $.post("pages/dateissued/action.php", {
                    fn: "load_year",
                },
                function (data) {
                    $.each(data, function (index, value) {
                        var html = '<option value="' + value.item_issued_year + '">' + value.item_issued_year + '</option>'
                        $('#year_start,#year_end').append(html);
                    });
                    $('#year_start, #year_end').niceSelect('update');
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
        form_data.append('year_start', $('#year_start').val());
        form_data.append('year_end', $('#year_end').val());
        form_data.append('page', page_number);

        $.ajax({
            url: 'pages/dateissued/action.php',
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
                            html += '<a href="javascript:void(0);" class="btn-toggle-abstract"><i class="ti-angle-down"></i> Show More</a>';
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
    $('#search-by-date').click(function (e) {
        e.preventDefault();
        load_data(1);
    });
});