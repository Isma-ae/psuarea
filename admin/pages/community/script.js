load_data(query = '');

$('#search').keyup(function (e) {
    var query = $('#search').val();
    load_data(query);
});

function load_data(query, page_number = 1) {
    var form_data = new FormData();
    form_data.append('query', query);
    form_data.append('page', page_number);
    form_data.append('fn', 'load_community');

    $.ajax({
        url: 'pages/community/action.php',
        method: 'POST',
        data: form_data,
        contentType: false,
        processData: false,
        success: function (response) {
            var data = JSON.parse(response);
            var html = '';

            if (data.data.length > 0) {
                $.each(data.data, function (index, post) {
                    html += '<tr data-com=\'' + JSON.stringify(post) + '\'>';
                    html += '<td>' + (index + 1) + '</td>';
                    html += '<td><img src="../files/community/' + post.community_img + '?t=' + new Date().getTime() + '" width="150px;"></td>';
                    html += '<td>' + post.community_title + '</td>';
                    html += '<td>' + post.community_description + '</td>';
                    html += '<td><div class="btn-list">';
                    html += '<button type="button" class="btn btn-sm btn-warning edit"><i class="icon-note"></i></button>';
                    html += '<button type="button" class="btn btn-sm btn-danger delete-community"><i class="icon-trash"></i></button>';
                    html += '</div></td>';
                    html += '</tr>';
                });
            } else {
                html += '<tr><td colspan="4" class="text-center">No Data Found</td></tr>';
            }
            $('#post_data').html(html);
            $('#total_data').html(data.total_data);
            $('#pagination_link').html(data.pagination);

            $('.edit').click(function (e) {
                e.preventDefault();
                var data_com = $(this).closest('tr').data('com');
                edit_community(data_com);
            });

            $('.delete-community').click(function (e) {
                e.preventDefault();
                var data_com = $(this).closest('tr').data('com');
                delete_community(data_com);
            });
        }
    });
}

$('#add').click(function (e) {
    e.preventDefault();
    $('#fn').val('add_community');
    $('#community_id').val('');
    $('#img').attr('src', '../img/3673.jpg');
    $('#community_img').val('');
    $('#community_title').val('');
    $('#community_description').val('');

    $('#community-header-modalLabel').html('เพิ่มชุมชน');
    $('#header-modal').removeClass('bg-warning');
    $('#header-modal').addClass('bg-success');
    $('#add-community').show();
    $('#edit-community').hide();
    $('#community-modal').modal('show');
});

function edit_community(data) {
    $('#fn').val('edit_community');
    $('#community_id').val(data.community_id);
    $('#img').attr('src', '../files/community/' + data.community_img);
    $('#community_img').val('');
    $('#community_title').val(data.community_title);
    $('#community_description').val(data.community_description);

    $('#community-header-modalLabel').html('แก้ไขชุมชน');
    $('#header-modal').removeClass('bg-success');
    $('#header-modal').addClass('bg-warning');
    $('#add-community').hide();
    $('#edit-community').show();
    $('#community-modal').modal('show');
}

$('#add-community,#edit-community').click(function (e) {
    e.preventDefault();
    $('#form-community').submit();
});

$('#form-community').submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: "pages/community/action.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        success: function (res) {
            Swal.fire(res.title, res.message, res.icon).then((result) => {
                load_data(query = '');
                $('#community-modal').modal('hide');
            });
        }
    });
});

function delete_community(data) {
    Swal.fire({
        title: 'คุณต้องการลบใช่หรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ลบชุมชนนี้',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "post",
                url: "pages/community/action.php",
                data: {
                    fn: "delete_community",
                    community_id: data.community_id
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