$(document).ready(function () {
    load_data(query = '');

    $('#search').keyup(function (e) {
        var query = $('#search').val();
        load_data(query);
    });

    function load_data(query, page_number = 1) {
        var form_data = new FormData();
        form_data.append('query', query);
        form_data.append('page', page_number);
        form_data.append('fn', 'load_item');

        $.ajax({
            url: 'pages/item-list/action.php',
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
                        html += '<td>' + (index + 1) + '</td>';
                        html += '<td>' + post.item_issued_year + '</td>';
                        html += '<td>' + post.item_title + '</td>';
                        html += '<td>' + post.writer_name + '</td>';
                        html += '<td><a href="?p=upload&item_id=' + post.item_id + '" class="btn btn-sm btn-success edit"><i class="fas fa-upload"></i></a></td>';
                        html += '<td><div class="btn-list">';
                        html += '<a href="?p=item-view&item_id=' + post.item_id + '" class="btn btn-sm btn-info" title="ดูข้อมูลผลงาน"><i class="icon-eye"></i></a>';
                        html += '<a href="?p=item-edit&item_id=' + post.item_id + '" class="btn btn-sm btn-warning edit"><i class="icon-note"></i></a>';
                        html += '<button type="button" class="btn btn-sm btn-danger delete-item" item-id="' + post.item_id + '"><i class="icon-trash"></i></button>';
                        html += '</div></td>';
                        html += '</tr>';
                    });
                } else {
                    html += '<tr><td colspan="5" class="text-center">No Data Found</td></tr>';
                }
                $('#post_data').html(html);
                $('#total_data').html(data.total_data);
                $('#pagination_link').html(data.pagination);

                $('.delete-item').click(function (e) {
                    e.preventDefault();
                    var item_id = $(this).attr('item-id');
                    delete_item(item_id);
                });
            }
        });
    }

    $(document).on('click', '#pagination_link .page-link', function (e) {
        e.preventDefault();
        var page_number = $(this).data('page');
        var query = $('#search').val();
        load_data(query, page_number);
    });

    $(document).on('click', '#pagination_link .prev', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page > 1) {
            var query = $('#search').val();
            load_data(query, current_page - 1);
        }
    });

    $(document).on('click', '#pagination_link .next', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page < window.total_pages) {
            var query = $('#search').val();
            load_data(query, current_page + 1);
        }
    });

    function delete_item(item_id) {
        Swal.fire({
            title: 'คุณต้องการลบใช่หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ลบรายการนี้',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
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
                        Swal.fire(res.title, res.message, res.icon).then((result) => {
                            load_data(query = '');
                        });
                    }
                });
            }
        });
    }
});