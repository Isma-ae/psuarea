$(document).ready(function () {
    var current_page = 1;

    load_data($('#search').val() || '', 1);

    var search_timer = null;
    $('#search').on('keyup input', function () {
        clearTimeout(search_timer);
        var query = $(this).val();
        search_timer = setTimeout(function () {
            current_page = 1;
            load_data(query, 1);
        }, 300);
    });

    $('#limit_select').on('change', function () {
        current_page = 1;
        var query = $('#search').val();
        load_data(query, 1);
    });

    function update_export_urls(query) {
        $('.export-link').each(function () {
            var format = $(this).data('format');
            var url = 'pages/item-list/export.php?format=' + format;
            if (query && query.trim() !== '') {
                url += '&query=' + encodeURIComponent(query.trim());
            }
            $(this).attr('href', url);
        });
    }

    function load_data(query, page_number) {
        page_number = page_number || 1;
        current_page = page_number;
        var limit = $('#limit_select').val() || 10;
        update_export_urls(query);

        var form_data = new FormData();
        form_data.append('query', query);
        form_data.append('page', page_number);
        form_data.append('limit', limit);
        form_data.append('fn', 'load_item');

        $.ajax({
            url: 'pages/item-list/action.php',
            method: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                var html = '';
                var page_limit = parseInt(data.limit) || parseInt(limit) || 10;
                var current_p = parseInt(data.page) || parseInt(page_number) || 1;
                var start_no = (current_p - 1) * page_limit;

                if (data.data && data.data.length > 0) {
                    $.each(data.data, function (index, post) {
                        html += '<tr>';
                        html += '<td class="text-center font-weight-bold text-muted">' + (start_no + index + 1) + '</td>';
                        html += '<td>' + (post.item_issued_year ? post.item_issued_year : '-') + '</td>';
                        html += '<td>' + post.item_title + '</td>';
                        html += '<td>' + (post.writer_name ? post.writer_name : '-') + '</td>';
                        html += '<td><a href="?p=upload&item_id=' + post.item_id + '" class="btn btn-sm btn-success edit" title="อัปโหลดไฟล์"><i class="fas fa-upload"></i></a></td>';
                        html += '<td><div class="btn-list">';
                        html += '<a href="?p=item-view&item_id=' + post.item_id + '" class="btn btn-sm btn-info" title="ดูข้อมูลผลงาน"><i class="icon-eye"></i></a>';
                        html += '<a href="?p=item-edit&item_id=' + post.item_id + '" class="btn btn-sm btn-warning edit" title="แก้ไขรายการ"><i class="icon-note"></i></a>';
                        html += '<button type="button" class="btn btn-sm btn-danger delete-item" item-id="' + post.item_id + '" title="ลบรายการ"><i class="icon-trash"></i></button>';
                        html += '</div></td>';
                        html += '</tr>';
                    });
                } else {
                    html += '<tr><td colspan="6" class="text-center text-muted py-4">ไม่พบข้อมูลรายการ</td></tr>';
                }
                $('#post_data').html(html);
                $('#total_data').html(data.total_data !== undefined ? data.total_data : 0);
                $('#pagination_link').html(data.pagination || '');

                $('.delete-item').off('click').on('click', function (e) {
                    e.preventDefault();
                    var item_id = $(this).attr('item-id');
                    delete_item(item_id);
                });
            },
            error: function () {
                $('#post_data').html('<tr><td colspan="6" class="text-center text-danger py-4">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>');
            }
        });
    }

    $(document).on('click', '#pagination_link .page-link', function (e) {
        e.preventDefault();
        var parent_li = $(this).parent();
        if (parent_li.hasClass('disabled') || parent_li.hasClass('active')) {
            return;
        }
        var page_number = $(this).data('page');
        if (page_number) {
            var query = $('#search').val();
            load_data(query, page_number);
        }
    });

    function delete_item(item_id) {
        Swal.fire({
            title: 'คุณต้องการลบใช่หรือไม่?',
            text: 'หากลบแล้วจะไม่สามารถกู้คืนข้อมูลได้',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ลบรายการนี้',
            cancelButtonText: 'ยกเลิก'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "pages/item-list/action.php",
                    data: {
                        fn: "delete_item",
                        item_id: item_id
                    },
                    dataType: "json",
                    success: function (res) {
                        Swal.fire(res.title, res.message, res.icon).then(function () {
                            load_data($('#search').val(), current_page);
                        });
                    },
                    error: function () {
                        Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
                    }
                });
            }
        });
    }
});