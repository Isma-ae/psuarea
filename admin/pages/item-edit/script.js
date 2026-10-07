$(document).ready(function () {
    tinymce.init({
        selector: 'textarea#item_abstract',
        height: 300,
        promotion: false
    });

    $(".add-author").click(function () {
        let newIndex = 0; // นับจำนวนผู้เขียน
        let newAuthor = '<div class="row author-main" data-action="edit_author">\
                                <div class="col-md-4">\
                                    <div class="form-group">\
                                        <label for="edit_fname">ชื่อผู้เขียน</label>\
                                        <input type="text" class="form-control" name="edit_fname[]"\
                                            placeholder="กรุณากรอกชื่อผู้เขียน...">\
                                    </div>\
                                </div>\
                                <div class="col-md-4">\
                                    <div class="form-group">\
                                        <label for="edit_lname">นามสกุล</label>\
                                        <input type="text" class="form-control" name="edit_lname[]"\
                                            aria-describedby="emailHelp" placeholder="กรุณากรอกนามสกุลผู้เขียน...">\
                                    </div>\
                                </div>\
                                <div class="col-md-2">\
                                    <div class="form-group">\
                                        <label for="edit_main">ประเภทผู้เขียน</label>\
                                        <div class="form-check">\
                                            <input class="form-check-input" type="radio" name="edit_main[' + newIndex + ']" value="2">\
                                            <label class="form-check-label" for="edit_main' + newIndex + '">\
                                                ผู้เขียนหลัก\
                                            </label>\
                                        </div>\
                                    </div>\
                                </div>\
                                <div class="col-md-2 pt-4">\
                                    <button type="button" class="btn btn-danger remove-author">ลบผู้เขียน</button>\
                                </div>\
                            </div>';


        $("#authors-list").append(newAuthor);
    });

    $(document).on("click", ".remove-author", function () {
        var del_action = $(this).closest(".author-main").attr('data-action');
        if (del_action == 'edit_author') {
            $(this).closest(".author-main").remove();
        } else {
            var writer_id = $(this).attr('writer-id');
            $(this).closest(".author-main").remove();
            Swal.fire({
                title: 'คุณต้องการลบผู้เขียนคนนี้ใช่หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบผู้เขียนคนนี้',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "pages/item-edit/action.php",
                        data: {
                            fn: "delete_writer",
                            writer_id: writer_id
                        },
                        dataType: "json",
                        success: function (res) {
                            Swal.fire(res.title, res.message, res.icon).then((result) => {});
                        }
                    });
                }
            });
        }
    });

    $(document).on("change", 'input[type="radio"]', function () {
        $('input[type="radio"]').prop('checked', false).val(2);
        $(this).prop('checked', true).val(1);
    });

    $(".add-subject").click(function () {
        let newSubject = '<div class="row subject" data-action="edit_subject">\
                            <div class="col-md-10">\
                                <div class="form-group">\
                                    <label for="edit_name">หัวเรื่อง</label>\
                                    <input type="text" class="form-control" name="edit_name[]" placeholder="กรุณากรอกหัวเรื่อง...">\
                                </div>\
                            </div>\
                            <div class="col-md-2 pt-4">\
                                <button type="button" class="btn btn-danger remove-subject">ลบหัวเรื่อง</button>\
                            </div>\
                        </div>';
        $(".subject").last().after(newSubject);
    });

    $(document).on("click", ".remove-subject", function () {
        var del_action = $(this).closest(".subject").attr('data-action');
        if (del_action == 'edit_subject') {
            $(this).closest(".subject").remove();
        } else {
            $(this).closest(".subject").remove();
            var subject_id = $(this).attr('subject-id');
            Swal.fire({
                title: 'คุณต้องการลบหัวเรื่องนี้ใช่หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบหัวเรื่องนี้',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "pages/item-edit/action.php",
                        data: {
                            fn: "delete_subject",
                            subject_id: subject_id
                        },
                        dataType: "json",
                        success: function (res) {
                            Swal.fire(res.title, res.message, res.icon).then((result) => {});
                        }
                    });
                }
            });
        }
    });


    $('.file-add').hide();
    load_file();

    function load_file() {
        if ($(".file-show").length) {
            $('.file-add').hide();
        } else {
            $('.file-add').show();
        }
    }

    $('#del-file').click(function (e) {
        e.preventDefault();
        var file_id = $(this).attr('file-id');
        var item_id = $('[name="item_id"]').val();
        Swal.fire({
            title: 'คุณต้องการลบไฟล์นี้ใช่หรือไม่?',
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
                    url: "pages/item-edit/action.php",
                    data: {
                        fn: "delete_file",
                        file_id: file_id,
                        item_id: item_id
                    },
                    dataType: "json",
                    success: function (res) {
                        Swal.fire(res.title, res.message, res.icon).then((result) => {
                            $(".file-show").hide();
                            $('.file-add').show();
                        });
                    }
                });
            }
        });
    });

    $('#edit-item').click(function (e) {
        e.preventDefault();

        // 1. ดึงข้อมูลจาก TinyMCE อย่างปลอดภัย
        if (typeof tinyMCE !== 'undefined' && tinyMCE.get('item_abstract')) {
            var item_abstract = tinyMCE.get('item_abstract').getContent();
            $('[name="item_abstract"]').val(item_abstract);
        }

        // 2. ตรวจสอบข้อมูลจำเป็นเบื้องต้น
        var item_title = $.trim($('[name="item_title"]').val());
        if (!item_title) {
            Swal.fire("กรุณากรอกข้อมูล", "กรุณากรอกชื่อเรื่องผลงาน", "warning");
            $('[name="item_title"]').focus();
            return false;
        }

        // 3. ตรวจสอบขนาดไฟล์ที่เลือกล่วงหน้า (Client-side validation)
        var fileInput = $('[name="file_name"]')[0];
        if (fileInput && fileInput.files && fileInput.files.length > 0) {
            var file = fileInput.files[0];
            var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            if (file.size > 40 * 1024 * 1024) {
                Swal.fire({
                    icon: "warning",
                    title: "ไฟล์มีขนาดใหญ่เกินไป",
                    text: "ไฟล์ \"" + file.name + "\" มีขนาด " + sizeMB + " MB ซึ่งอาจเกินขีดจำกัดของเซิร์ฟเวอร์ (แนะนำไม่เกิน 40 MB) กรุณาลดขนาดไฟล์หรือบีบอัดไฟล์ก่อนอัปโหลด"
                });
                return false;
            }
        }

        $('#form-item').submit();
    });

    var isSubmitting = false;

    $('#form-item').submit(function (e) {
        e.preventDefault();

        if (isSubmitting) {
            return false; // ป้องกันการกดซ้ำซ้อน
        }
        isSubmitting = true;

        var $btn = $('#edit-item');
        var originalBtnHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึกข้อมูล...');

        var formData = new FormData(this);
        $.ajax({
            url: "pages/item-edit/action.php",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            timeout: 180000, // Timeout 3 นาที
            beforeSend: function () {
                if (typeof NProgress !== 'undefined') {
                    NProgress.start();
                }
            },
            complete: function () {
                isSubmitting = false;
                $btn.prop('disabled', false).html(originalBtnHtml);
                if (typeof NProgress !== 'undefined') {
                    NProgress.done();
                }
            },
            success: function (res) {
                Swal.fire(res.title, res.message, res.icon).then((result) => {
                    if (res.data == 'y') {
                        window.location.href = "?p=item-list";
                    }
                });
            },
            error: function (xhr, status, error) {
                var title = "ไม่สำเร็จ";
                var msg = "เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์";

                if (status === "timeout") {
                    msg = "การส่งข้อมูลหมดเวลา (Request Timeout) ไฟล์อาจมีขนาดใหญ่เกินไปหรือสัญญาณอินเทอร์เน็ตไม่เสถียร กรุณาลองใหม่อีกครั้ง";
                } else if (xhr.status === 0) {
                    msg = "ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ (Network Error) อาจเกิดจากการเชื่อมต่อหลุด หรือไฟล์ที่อัปโหลดมีขนาดใหญ่เกินกว่าที่เว็บเซิร์ฟเวอร์อนุญาต";
                } else if (xhr.status === 413) {
                    msg = "ขนาดไฟล์ใหญ่เกินกว่าที่เซิร์ฟเวอร์กำหนด (413 Request Entity Too Large) กรุณาลดขนาดไฟล์ หรือแจ้งผู้ดูแลระบบปรับแต่งค่า post_max_size / upload_max_filesize";
                } else if (xhr.status === 500) {
                    msg = "เซิร์ฟเวอร์เกิดข้อผิดพลาดภายใน (Internal Server Error 500)";
                } else if (xhr.status === 504) {
                    msg = "เซิร์ฟเวอร์หมดเวลาการตอบสนอง (504 Gateway Timeout)";
                } else if (xhr.responseText) {
                    try {
                        var json = JSON.parse(xhr.responseText);
                        if (json.message) msg = json.message;
                        if (json.title) title = json.title;
                    } catch(e) {
                        var trimmed = xhr.responseText.trim();
                        if (trimmed === "") {
                            msg = "เซิร์ฟเวอร์ไม่ส่งข้อมูลตอบกลับ อาจเกิดจากขนาดไฟล์ใหญ่เกินกว่าค่า post_max_size ของเซิร์ฟเวอร์";
                        } else {
                            var tempDiv = document.createElement("div");
                            tempDiv.innerHTML = trimmed;
                            var plain = (tempDiv.textContent || tempDiv.innerText || "").trim();
                            if (plain.length > 0 && plain.length < 250) {
                                msg = plain;
                            } else {
                                msg += " (HTTP " + xhr.status + ": " + (error || "Unknown Error") + ")";
                            }
                        }
                    }
                }
                Swal.fire(title, msg, "error");
            }
        });
    });
});