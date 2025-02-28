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
            success: function (res) {
                Swal.fire(res.title, res.message, res.icon).then((result) => {
                    window.location.href = "?p=item-list";
                });
            }
        });
    });

});