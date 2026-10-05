$(document).ready(function () {

    var fileTypeBadges = {
        'cover': '<span class="badge badge-primary">ปกหน้า</span>',
        'bcover': '<span class="badge badge-secondary">ปกหลัง</span>',
        'introduction': '<span class="badge badge-info">คำนำ</span>',
        'contents': '<span class="badge badge-dark">สารบัญ</span>',
        'file': '<span class="badge badge-success">เนื้อหา</span>',
        'other': '<span class="badge badge-light border">อื่น ๆ</span>'
    };

    // อัปเดต label เมื่อเลือกไฟล์
    $('#file_name').on('change', function () {
        var fileName = $(this).val().split('\\').pop();
        $('#file-label').html(fileName ? fileName : 'เลือกไฟล์...');
    });

    load_file();

    function getFileIcon(fileName) {
        var ext = fileName.split('.').pop().toLowerCase();
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].indexOf(ext) !== -1) {
            return '<i class="far fa-file-image text-danger mr-1"></i>';
        } else if (ext === 'pdf') {
            return '<i class="far fa-file-pdf text-danger mr-1"></i>';
        } else if (['doc', 'docx'].indexOf(ext) !== -1) {
            return '<i class="far fa-file-word text-primary mr-1"></i>';
        } else if (['xls', 'xlsx'].indexOf(ext) !== -1) {
            return '<i class="far fa-file-excel text-success mr-1"></i>';
        } else if (['zip', 'rar', '7z'].indexOf(ext) !== -1) {
            return '<i class="far fa-file-archive text-warning mr-1"></i>';
        }
        return '<i class="far fa-file-alt text-secondary mr-1"></i>';
    }

    function load_file() {
        if (typeof NProgress !== 'undefined') NProgress.start();

        var itemId = $('#item_id').val();
        if (!itemId) {
            var urlParams = new URLSearchParams(window.location.search);
            itemId = urlParams.get('item_id') || '';
            $('#item_id').val(itemId);
        }

        $.ajax({
            type: "POST",
            url: "pages/upload/action.php",
            data: {
                fn: "load_file",
                item_id: itemId
            },
            dataType: "json",
            success: function (data) {
                if (typeof NProgress !== 'undefined') NProgress.done();

                // ตรวจสอบกรณี Session หมดอายุ
                if (data && data.data === 'n') {
                    Swal.fire(data.title, data.message, data.icon).then(function () {
                        window.location.href = data.url || './login/';
                    });
                    return;
                }

                var html = '';
                if (data && data.data && Array.isArray(data.data) && data.data.length > 0) {
                    $.each(data.data, function (index, post) {
                        var typeBadge = fileTypeBadges[post.file_type] || ('<span class="badge badge-light border">' + (post.file_type || 'other') + '</span>');
                        var icon = getFileIcon(post.file_name || '');
                        var fileUrl = '../files/item/' + post.item_id + '/' + encodeURIComponent(post.file_name);

                        html += '<tr data-file=\'' + JSON.stringify(post).replace(/'/g, "&#39;") + '\'>';
                        html += '<td class="text-center">' + (index + 1) + '</td>';
                        html += '<td><a href="' + fileUrl + '" target="_blank" class="font-weight-medium">' + icon + post.file_name + '</a></td>';
                        html += '<td>' + typeBadge + '</td>';
                        html += '<td>' + (post.file_description ? post.file_description : '<span class="text-muted font-italic">-</span>') + '</td>';
                        html += '<td class="text-center"><div class="btn-group btn-group-sm" role="group">';
                        html += '<button type="button" class="btn btn-warning edit" title="แก้ไข"><i class="fas fa-edit"></i></button>';
                        html += '<button type="button" class="btn btn-danger delete-file" title="ลบ"><i class="fas fa-trash-alt"></i></button>';
                        html += '</div></td>';
                        html += '</tr>';
                    });
                } else {
                    html += '<tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>ยังไม่มีไฟล์ในรายการนี้</td></tr>';
                }

                $('#post_data').html(html);
            },
            error: function (xhr, status, error) {
                if (typeof NProgress !== 'undefined') NProgress.done();
                console.error("load_file AJAX Error:", status, error, xhr.responseText);

                var errorText = 'เกิดข้อผิดพลาดในการโหลดข้อมูล (HTTP ' + xhr.status + ')';
                if (xhr.status === 200 && xhr.responseText) {
                    var cleanMsg = xhr.responseText.replace(/<[^>]*>?/gm, '').trim();
                    if (cleanMsg) {
                        errorText += '<br><small class="text-muted">' + cleanMsg.substring(0, 150) + '</small>';
                    }
                }
                $('#post_data').html('<tr><td colspan="5" class="text-center text-danger py-4">' + errorText + '</td></tr>');
            }
        });
    }

    // คลิกแก้ไขไฟล์ (Event Delegation ป้องกัน handler ซ้ำ)
    $(document).off('click', '.edit').on('click', '.edit', function (e) {
        e.preventDefault();
        var data_file = $(this).closest('tr').data('file');
        if (typeof data_file === 'string') {
            try { data_file = JSON.parse(data_file); } catch (e) { }
        }
        edit_file(data_file);
    });

    // คลิกปุ่มลบไฟล์
    $(document).off('click', '.delete-file').on('click', '.delete-file', function (e) {
        e.preventDefault();
        var data_file = $(this).closest('tr').data('file');
        if (typeof data_file === 'string') {
            try { data_file = JSON.parse(data_file); } catch (e) { }
        }
        delete_file(data_file);
    });

    // คลิกปุ่มเพิ่มไฟล์
    $('#add').click(function (e) {
        e.preventDefault();
        $('#fn').val('add_file');
        $('#file_id').val('');
        $('#file_name').val('');
        $('#file-label').html('เลือกไฟล์...');
        $("#file_type").val("cover").change();
        $('#file_description').val('');
        $('#file-help').html('เลือกไฟล์ที่ต้องการอัปโหลด (รองรับ PDF, รูปภาพ หรือเอกสาร)');

        $('#file-header-modalLabel').html('<i class="fas fa-plus-circle mr-1"></i> เพิ่มไฟล์');
        $('#header-modal').removeClass('bg-warning').addClass('bg-success');
        $('#add-file').show();
        $('#edit-file').hide();
        $('#file-modal').modal('show');
    });

    function edit_file(data) {
        $('#fn').val('edit_file');
        $('#file_id').val(data.file_id);
        $('#file_name').val('');
        $('#file-label').html('เลือกไฟล์ใหม่หากต้องการเปลี่ยน (หรือเว้นว่างไว้)...');
        $("#file_type").val(data.file_type).change();
        $('#file_description').val(data.file_description || '');
        $('#file-help').html('ไฟล์ปัจจุบัน: <strong>' + data.file_name + '</strong> (หากไม่ต้องการเปลี่ยนไฟล์ ให้เว้นว่างไว้)');

        $('#file-header-modalLabel').html('<i class="fas fa-edit mr-1"></i> แก้ไขไฟล์');
        $('#header-modal').removeClass('bg-success').addClass('bg-warning');
        $('#add-file').hide();
        $('#edit-file').show();
        $('#file-modal').modal('show');
    }

    $('#form-file').submit(function (e) {
        e.preventDefault();
        var fn = $('#fn').val();

        // ตรวจสอบกรณีเพิ่มไฟล์ ต้องเลือกไฟล์เสมอ
        if (fn === 'add_file') {
            var fileInput = document.getElementById('file_name');
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                Swal.fire('แจ้งเตือน', 'กรุณาเลือกไฟล์ที่ต้องการอัปโหลด', 'warning');
                return;
            }
        }

        var btn = (fn === 'add_file') ? $('#add-file') : $('#edit-file');
        btn.prop('disabled', true);
        if (typeof NProgress !== 'undefined') NProgress.start();

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
                btn.prop('disabled', false);
                if (typeof NProgress !== 'undefined') NProgress.done();

                Swal.fire(res.title, res.message, res.icon).then((result) => {
                    if (res.data === 'y') {
                        load_file();
                        $('#file-modal').modal('hide');
                    }
                });
            },
            error: function (xhr, status, error) {
                btn.prop('disabled', false);
                if (typeof NProgress !== 'undefined') NProgress.done();

                var msg = 'ไม่สามารถบันทึกข้อมูลได้';
                if (xhr.status === 413) {
                    msg = 'ขนาดไฟล์ใหญ่เกินกว่าที่เซิร์ฟเวอร์กำหนด (413 Payload Too Large)';
                } else if (xhr.status === 500) {
                    msg = 'เกิดข้อผิดพลาดภายในเซิร์ฟเวอร์ (500 Internal Server Error)';
                } else if (xhr.status) {
                    msg += ' (HTTP ' + xhr.status + ')';
                }
                Swal.fire('ข้อผิดพลาด', msg, 'error');
            }
        });
    });

    function delete_file(data) {
        Swal.fire({
            title: 'คุณต้องการลบใช่หรือไม่?',
            text: 'ต้องการลบไฟล์ "' + data.file_name + '" หรือไม่? (เมื่อลบแล้วจะไม่สามารถกู้คืนได้)',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ใช่, ลบไฟล์นี้',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                if (typeof NProgress !== 'undefined') NProgress.start();
                $.ajax({
                    type: "POST",
                    url: "pages/upload/action.php",
                    data: {
                        fn: "delete_file",
                        file_id: data.file_id,
                        item_id: data.item_id
                    },
                    dataType: "json",
                    success: function (res) {
                        if (typeof NProgress !== 'undefined') NProgress.done();
                        Swal.fire(res.title, res.message, res.icon).then((result) => {
                            if (res.data === 'y') {
                                load_file();
                            }
                        });
                    },
                    error: function (xhr) {
                        if (typeof NProgress !== 'undefined') NProgress.done();
                        Swal.fire('ข้อผิดพลาด', 'ไม่สามารถลบไฟล์ได้ (HTTP ' + xhr.status + ')', 'error');
                    }
                });
            }
        });
    }
});