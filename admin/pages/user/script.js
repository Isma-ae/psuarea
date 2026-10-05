load_data(query = '');

$('#search').keyup(function (e) {
    var query = $('#search').val();
    load_data(query);
});

function load_data(query, page_number = 1) {
    var form_data = new FormData();
    form_data.append('query', query);
    form_data.append('page', page_number);
    form_data.append('fn', 'load_user');

    $.ajax({
        url: 'pages/user/action.php',
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
                    html += '<td>' + post.user_name + '</td>';
                    html += '<td>' + post.user_fname + ' ' + post.user_lname + '</td>';
                    html += '<td><div class="btn-list">';
                    html += '<button type="button" class="btn btn-sm btn-warning edit"><i class="icon-note"></i></button>';
                    html += '<button type="button" class="btn btn-sm btn-danger delete-user"><i class="icon-trash"></i></button>';
                    html += '</div></td>';
                    html += '</tr>';
                });
            } else {
                html += '<tr><td colspan="3" class="text-center">No Data Found</td></tr>';
            }
            $('#post_data').html(html);
            $('#total_data').html(data.total_data);
            $('#pagination_link').html(data.pagination);

            $('.edit').click(function (e) {
                e.preventDefault();
                var data_com = $(this).closest('tr').data('com');
                edit_user(data_com);
            });

            $('.delete-user').click(function (e) {
                e.preventDefault();
                var data_com = $(this).closest('tr').data('com');
                delete_user(data_com);
            });
        }
    });
}

$('#add').click(function (e) {
    e.preventDefault();
    $('#fn').val('add_user');
    $('#user_id').val('');
    $('#user_name').val('');
    $('#user_fname').val('');
    $('#user_lname').val('');

    $('#user-header-modalLabel').html('เพิ่มผู้ดูแลระบบ');
    $('#header-modal').removeClass('bg-warning');
    $('#header-modal').addClass('bg-success');
    $('#add-user').show();
    $('#edit-user').hide();
    $('#user-modal').modal('show');
});

function edit_user(data) {
    $('#fn').val('edit_user');
    $('#user_id').val(data.user_id);
    $('#user_name').val(data.user_name);
    $('#user_fname').val(data.user_fname);
    $('#user_lname').val(data.user_lname);

    $('#user-header-modalLabel').html('แก้ไขผู้ดูแลระบบ');
    $('#header-modal').removeClass('bg-success');
    $('#header-modal').addClass('bg-warning');
    $('#add-user').hide();
    $('#edit-user').show();
    $('#user-modal').modal('show');
}

$('#add-user,#edit-user').click(function (e) {
    e.preventDefault();
    $('#form-user').submit();
});

$('#form-user').submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: "pages/user/action.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        success: function (res) {
            Swal.fire(res.title, res.message, res.icon).then((result) => {
                load_data(query = '');
                $('#user-modal').modal('hide');
            });
        }
    });
});

function delete_user(data) {
    Swal.fire({
        title: 'คุณต้องการลบใช่หรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ลบผู้ดูแลระบบนี้',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "post",
                url: "pages/user/action.php",
                data: {
                    fn: "delete_user",
                    user_id: data.user_id
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