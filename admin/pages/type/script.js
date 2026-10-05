load_data(query = '');

$('#search').keyup(function (e) {
    var query = $('#search').val();
    load_data(query);
});

function load_data(query, page_number = 1) {
    var form_data = new FormData();
    form_data.append('query', query);
    form_data.append('page', page_number);
    form_data.append('fn', 'load_type');

    $.ajax({
        url: 'pages/type/action.php',
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
                    html += '<td>' + post.type_name + '</td>';
                    html += '<td><div class="btn-list">';
                    html += '<button type="button" class="btn btn-sm btn-warning edit"><i class="icon-note"></i></button>';
                    html += '<button type="button" class="btn btn-sm btn-danger delete-type"><i class="icon-trash"></i></button>';
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
                edit_type(data_com);
            });

            $('.delete-type').click(function (e) {
                e.preventDefault();
                var data_com = $(this).closest('tr').data('com');
                delete_type(data_com);
            });
        }
    });
}

$('#add').click(function (e) {
    e.preventDefault();
    $('#fn').val('add_type');
    $('#type_id').val('');
    $('#type_name').val('');

    $('#type-header-modalLabel').html('เพิ่มหมวดหมู่');
    $('#header-modal').removeClass('bg-warning');
    $('#header-modal').addClass('bg-success');
    $('#add-type').show();
    $('#edit-type').hide();
    $('#type-modal').modal('show');
});

function edit_type(data) {
    $('#fn').val('edit_type');
    $('#type_id').val(data.type_id);
    $('#type_name').val(data.type_name);

    $('#type-header-modalLabel').html('แก้ไขหมวดหมู่');
    $('#header-modal').removeClass('bg-success');
    $('#header-modal').addClass('bg-warning');
    $('#add-type').hide();
    $('#edit-type').show();
    $('#type-modal').modal('show');
}

$('#add-type,#edit-type').click(function (e) {
    e.preventDefault();
    $('#form-type').submit();
});

$('#form-type').submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: "pages/type/action.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        success: function (res) {
            Swal.fire(res.title, res.message, res.icon).then((result) => {
                load_data(query = '');
                $('#type-modal').modal('hide');
            });
        }
    });
});

function delete_type(data) {
    Swal.fire({
        title: 'คุณต้องการลบใช่หรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ลบหมวดหมู่นี้',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "post",
                url: "pages/type/action.php",
                data: {
                    fn: "delete_type",
                    type_id: data.type_id
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