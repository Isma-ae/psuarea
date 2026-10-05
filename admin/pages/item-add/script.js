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
        var item_abstract = tinyMCE.get('item_abstract').getContent();
        $('[name="item_abstract"]').val(item_abstract);
        $('#form-item').submit();
    });

    $('#form-item').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "pages/item-add/action.php",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                if (typeof NProgress !== 'undefined') {
                    NProgress.start();
                }
            },
            complete: function () {
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
                var msg = "เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์";
                if (xhr.status == 413) {
                    msg = "ขนาดไฟล์ใหญ่เกินกว่าที่เซิร์ฟเวอร์กำหนด (413 Request Entity Too Large) กรุณาตรวจสอบการตั้งค่าเว็บเซิร์ฟเวอร์";
                } else if (xhr.responseText) {
                    try {
                        var json = JSON.parse(xhr.responseText);
                        if (json.message) msg = json.message;
                    } catch(e) {
                        msg += " (Status " + xhr.status + ": " + (error || "Unknown Error") + ")";
                    }
                }
                Swal.fire("ไม่สำเร็จ", msg, "error");
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