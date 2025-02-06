$(function () {

    tinymce.init({
        selector: 'textarea#title',
        height: 500,
        promotion: false
    });

    load_page();

    function load_page() {
        $.ajax({
            type: "post",
            url: "pages/home-page/action.php",
            data: {
                fn: "load_page"
            },
            dataType: "json",
            success: function (res) {
                if (res.data == 'n') {
                    Swal.fire(res.title, res.message, res.icon).then((result) => {
                        window.location.href = res.url;
                    });
                } else {
                    if (res.data[0].page_banner != "") {
                        $('#banner_img').find('img').attr('src', '../file/banner/' + res.data[0].page_banner);
                        $('#banner_img').show();
                        $('#form_banner').hide();
                    } else {
                        $('#banner_img').find('img').attr('src', '');
                        $('#banner-label').html('');
                        $('#banner_img').hide();
                        $('#form_banner').show();
                    }

                    if (res.data[0].page_title != "") {
                        $('#page_title').html(res.data[0].page_title);
                        $('.edit-title').show();
                        $('.add-title').hide();
                    } else {
                        $('#page_title').html('');
                        $('.edit-title').hide();
                        $('.add-title').show();
                    }

                    $('.edit-title').click(function (e) {
                        e.preventDefault();
                        $('#header-modal').removeClass('bg-success');
                        $('#header-modal').addClass('bg-warning');
                        $('#edit-title').removeClass('btn-success');
                        $('#edit-title').addClass('btn-warning');
                        tinyMCE.get('title').setContent(res.data[0].page_title);
                        $('.modal_title').modal('show');
                    });
                }
            }
        });
    }

    $('#form_banner').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "pages/home-page/action.php",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function (res) {
                Swal.fire(res.title, res.message, res.icon).then((result) => {
                    load_page();
                });
            }
        });
    });

    $('#delete-banner').click(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'คุณต้องการลบใช่หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ลบภาพนี้',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "pages/home-page/action.php",
                    data: {
                        fn: "delete_banner"
                    },
                    dataType: "json",
                    success: function (res) {
                        Swal.fire(res.title, res.message, res.icon).then((result) => {
                            load_page();
                        });
                    }
                });
            }
        });
    });

    $('.add-title').click(function (e) {
        e.preventDefault();
        $('#header-modal').removeClass('bg-warning');
        $('#header-modal').addClass('bg-success');
        $('#edit-title').removeClass('btn-warning');
        $('#edit-title').addClass('btn-success');
        tinyMCE.get('title').setContent('');
        $('.modal_title').modal('show');
    });

    $('#edit-title').click(function (e) {
        e.preventDefault();
        var page_title = tinyMCE.get('title').getContent()
        $.ajax({
            type: "post",
            url: "pages/home-page/action.php",
            data: {
                fn: "edit_title",
                page_title: page_title
            },
            dataType: "json",
            success: function (res) {
                Swal.fire(res.title, res.message, res.icon).then((result) => {
                    load_page();
                    $('.modal_title').modal('hide');
                });
            }
        });
    });
});