$(document).ready(function () {
    tinymce.init({
        selector: 'textarea#item_abstract',
        height: 300,
        promotion: false
    });

    $("#add-author").click(function () {
        let newIndex = $(".author-main").length; // นับจำนวนผู้เขียน
        let newAuthor = $(".author-main").first().clone();

        newAuthor.find("input").val("");
        newAuthor.find("select").val("");

        newAuthor.find('input[type="radio"]').attr("name", "writer_main[" + newIndex + "]").prop('checked', false).val(2);

        newAuthor.find("#add-author")
            .removeClass("btn-primary")
            .addClass("btn-danger remove-author")
            .text("ลบผู้เขียน")
            .attr("id", "");

        $("#authors-list").append(newAuthor);
    });

    $(document).on("click", ".remove-author", function () {
        $(this).closest(".author-main").remove();
    });

    $(document).on("change", 'input[type="radio"]', function () {
        $('input[type="radio"]').prop('checked', false).val(2);
        $(this).prop('checked', true).val(1);
    });

    $("#add-subject").click(function () {
        let newSubject = $(".subject").first().clone();
        newSubject.find("input").val("");
        newSubject.find("#add-subject")
            .removeClass("btn-primary")
            .addClass("btn-danger remove-subject")
            .text("ลบหัวเรื่อง")
            .attr("id", "");

        $(".subject").last().after(newSubject);
    });

    $(document).on("click", ".remove-subject", function () {
        $(this).closest(".subject").remove();
    });

    $('#add-item').click(function (e) {
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

        var community_id = $('[name="community_id"]').val();
        if (!community_id || community_id === '0') {
            Swal.fire("กรุณากรอกข้อมูล", "กรุณาเลือกชุมชน / ขอบเขตเนื้อหา", "warning");
            $('[name="community_id"]').focus();
            return false;
        }

        var collection_id = $('[name="collection_id"]').val();
        if (!collection_id || collection_id === '0') {
            Swal.fire("กรุณากรอกข้อมูล", "กรุณาเลือกคอลเลกชัน", "warning");
            $('[name="collection_id"]').focus();
            return false;
        }

        // 3. ตรวจสอบขนาดไฟล์ที่เลือกล่วงหน้า (Client-side validation)
        var fileInput = $('[name="file_name"]')[0];
        if (fileInput && fileInput.files && fileInput.files.length > 0) {
            var file = fileInput.files[0];
            var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            // แจ้งเตือนหากไฟล์มีขนาดใหญ่เกิน 40 MB
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

        var $btn = $('#add-item');
        var originalBtnHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึกข้อมูล...');

        var formData = new FormData(this);
        $.ajax({
            url: "pages/item-add/action.php",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            timeout: 180000, // กำหนด Timeout 3 นาที สำหรับไฟล์ขนาดใหญ่
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
                            // ดึงข้อความจาก HTML Error
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

    load_community();

    function load_community() {
        $.post("pages/item-add/action.php", {
                fn: "load_community"
            },
            function (data) {
                var html = "";
                html += '<option value="0"> -- กรุณาเลือกชุมชน -- </option>';
                $.each(data.data, function (i, v) {
                    html += '<option value="' + v.community_id + '">' + v.community_title + '</option>';
                });
                $('[name="community_id"]').html(html);
            },
            "json"
        );
    }

    load_collection();

    function load_collection() {
        $.post("pages/item-add/action.php", {
                fn: "load_collection"
            },
            function (data) {
                var html = "";
                html += '<option value="0"> -- กรุณาเลือกคอลเลกชัน -- </option>';
                $.each(data.data, function (i, v) {
                    html += '<option value="' + v.collection_id + '">' + v.collection_name + '</option>';
                });
                $('[name="collection_id"]').html(html);
            },
            "json"
        );
    }

    /*load_day();

    function load_day() {
        var html = '<option value=""> -- เลือกวันที่ -- </option>';
        for (var i = 1; i < 32; i++) {
            html += '<option value="' + i + '">' + i + '</option>';
        }
        $('[name="item_issued_day"]').html(html);
    }*/

    load_month();

    function load_month() {
        var monthsThai = [
            "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
            "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
        ];
        var monthId = '<option value=""> -- เลือกเดือน -- </option>';
        $.each(monthsThai, function (index, month) {
            monthId += '<option value="' + (index + 1) + '">' + month + '</option>';
        });
        $('[name="item_issued_month"]').html(monthId);
    }

});