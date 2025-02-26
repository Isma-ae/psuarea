$(document).ready(function () {
    tinymce.init({
        selector: 'textarea#title',
        height: 300,
        promotion: false
    });

    $("#add-author").click(function () {
        let newAuthor = $(".author-main").first().clone();

        newAuthor.find("input").val(""); // ล้างค่าช่องกรอกข้อมูล

        let count = $(".author-main").length + 1;
        newAuthor.find('input[type="radio"]')
            .attr("id", "writer_main" + count)
            .val(2);
        newAuthor.find('label[for^="writer_main"]').attr("for", "writer_main" + count);

        newAuthor.find("#add-author")
            .removeClass("btn-primary")
            .addClass("btn-danger remove-author")
            .text("ลบผู้เขียน")
            .attr("id", "");

        $(".author-main").last().after(newAuthor); // เพิ่มองค์ประกอบใหม่
    });

    $(document).on("click", ".remove-author", function () {
        $(this).closest(".author-main").remove();
    });

    $(document).on("change", 'input[type="radio"][name="writer_main"]', function () {
        $('input[type="radio"][name="writer_main"]').val(2);
        $(this).val(1);
    });

    $("#add-subject").click(function () {
        let newSubject = $(".subject").first().clone(); // โคลน element หัวข้อแรก
        newSubject.find("input").val(""); // ล้างค่าช่อง input

        // เปลี่ยนปุ่มจาก "เพิ่มหัวเรื่อง" เป็น "ลบหัวเรื่อง"
        newSubject.find("#add-subject")
            .removeClass("btn-primary")
            .addClass("btn-danger remove-subject")
            .text("ลบหัวเรื่อง")
            .attr("id", ""); // เอา ID ออกเพื่อป้องกันซ้ำกัน

        $(".subject").last().after(newSubject); // เพิ่มหัวเรื่องใหม่
    });

    // ลบหัวเรื่อง
    $(document).on("click", ".remove-subject", function () {
        $(this).closest(".subject").remove();
    });

});