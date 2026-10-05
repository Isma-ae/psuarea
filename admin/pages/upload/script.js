$(document).ready(function () {

    load_file();

    function load_file() {
        $.post("pages/upload/action.php", {
                fn: "load_file",
                item_id: $('#item_id').val()
            },
            function (data) {
                var html = '';

                if (data.data.length > 0) {
                    $.each(data.data, function (index, post) {
                        html += '<tr data-file=\'' + JSON.stringify(post) + '\'>';
                        html += '<td>' + (index + 1) + '</td>';
                        html += '<td><a href="../files/item/' + post.item_id + '/' + post.file_name + '" target="_blank">' + post.file_name + '</a></td>';
                        html += '<td>' + post.file_description + '</td>';
                        html += '<td><div class="btn-list">';
                        html += '<button type="button" class="btn btn-sm btn-warning edit"><i class="icon-note"></i></button>';
                        html += '<button type="button" class="btn btn-sm btn-danger delete-file"><i class="icon-trash"></i></button>';
                        html += '</div></td>';
                        html += '</tr>';
                    });
                } else {
                    html += '<tr><td colspan="4" class="text-center">No Data Found</td></tr>';
                }

                $('#post_data').html(html);

                $(document).on('click', '.edit', function (e) {
                    e.preventDefault();
                    var data_file = $(this).closest('tr').data('file');
                    edit_file(data_file);
                });

                $(document).on('click', '.delete-file', function (e) {
                    e.preventDefault();
                    var data_file = $(this).closest('tr').data('file');
                    delete_file(data_file);
                });
            },
            "json"
        );
    }

    $('#add').click(function (e) {
        e.preventDefault();
        $('#fn').val('add_file');
        $('#file_id').val('');
        $('#file_name').val('');
        $("#file_type").val("cover").change();
        $('#file_description').val('');

        $('#file-header-modalLabel').html('เพิ่มไฟล์');
        $('#header-modal').removeClass('bg-warning');
        $('#header-modal').addClass('bg-success');
        $('#add-file').show();
        $('#edit-file').hide();
        $('#file-modal').modal('show');
    });

    function edit_file(data) {
        $('#fn').val('edit_file');
        $('#file_id').val(data.file_id);
        $('#file_name').val('');
        $("#file_type").val(data.file_type).change();
        $('#file_description').val(data.file_description);

        $('#file-header-modalLabel').html('แก้ไขไฟล์');
        $('#header-modal').removeClass('bg-success');
        $('#header-modal').addClass('bg-warning');
        $('#add-file').hide();
        $('#edit-file').show();
        $('#file-modal').modal('show');
    }

    $('#add-file,#edit-file').click(function (e) {
        e.preventDefault();
        $('#form-file').submit();
    });

    $('#form-file').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "pages/upload/action.php",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function (res) {
                Swal.fire(res.title, res.message, res.icon).then((result) => {
                    load_file();
                    $('#file-modal').modal('hide');
                });
            }
        });
    });

    function delete_file(data) {
        Swal.fire({
            title: 'คุณต้องการลบใช่หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ลบไฟล์นี้',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "pages/upload/action.php",
                    data: {
                        fn: "delete_file",
                        file_id: data.file_id,
                        item_id: data.item_id
                    },
                    dataType: "json",
                    success: function (res) {
                        Swal.fire(res.title, res.message, res.icon).then((result) => {
                            load_file();
                        });
                    }
                });
            }
        });
    }
});