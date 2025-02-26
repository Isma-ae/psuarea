load_data(query = '');

$('#search').keyup(function (e) {
    var query = $('#search').val();
    load_data(query);
});

function load_data(query, page_number = 1) {
    var form_data = new FormData();
    form_data.append('query', query);
    form_data.append('page', page_number);
    form_data.append('fn', 'load_collection');

    $.ajax({
        url: 'pages/collection/action.php',
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
                    html += '<td>' + post.collection_name + '</td>';
                    html += '<td><div class="btn-list">';
                    html += '<button type="button" class="btn btn-sm btn-warning edit"><i class="icon-note"></i></button>';
                    html += '<button type="button" class="btn btn-sm btn-danger delete-collection"><i class="icon-trash"></i></button>';
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
                edit_collection(data_com);
            });

            $('.delete-collection').click(function (e) {
                e.preventDefault();
                var data_com = $(this).closest('tr').data('com');
                delete_collection(data_com);
            });
        }
    });
}

$('#add').click(function (e) {
    e.preventDefault();
    $('#fn').val('add_collection');
    $('#collection_id').val('');
    $('#collection_name').val('');

    $('#collection-header-modalLabel').html('เพิ่มคอลเลกชัน');
    $('#header-modal').removeClass('bg-warning');
    $('#header-modal').addClass('bg-success');
    $('#add-collection').show();
    $('#edit-collection').hide();
    $('#collection-modal').modal('show');
});

function edit_collection(data) {
    $('#fn').val('edit_collection');
    $('#collection_id').val(data.collection_id);
    $('#collection_name').val(data.collection_name);

    $('#collection-header-modalLabel').html('แก้ไขคอลเลกชัน');
    $('#header-modal').removeClass('bg-success');
    $('#header-modal').addClass('bg-warning');
    $('#add-collection').hide();
    $('#edit-collection').show();
    $('#collection-modal').modal('show');
}

$('#add-collection,#edit-collection').click(function (e) {
    e.preventDefault();
    $('#form-collection').submit();
});

$('#form-collection').submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: "pages/collection/action.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        success: function (res) {
            Swal.fire(res.title, res.message, res.icon).then((result) => {
                load_data(query = '');
                $('#collection-modal').modal('hide');
            });
        }
    });
});

function delete_collection(data) {
    Swal.fire({
        title: 'คุณต้องการลบใช่หรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ลบคอลเลกชันนี้',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "post",
                url: "pages/collection/action.php",
                data: {
                    fn: "delete_collection",
                    collection_id: data.collection_id
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