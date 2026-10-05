$(function () {

    tinymce.init({
        selector: 'textarea#title',
        height: 400,
        promotion: false
    });

    // แสดงชื่อไฟล์เมื่อผู้ใช้เลือกรูปภาพ
    $('#page_banner').on('change', function () {
        var fileName = $(this).val().split('\\').pop();
        $('#banner-label').html(fileName ? fileName : 'เลือกรูปภาพแบนเนอร์...');
    });

    load_page();

    function load_page() {
        if (typeof NProgress !== 'undefined') NProgress.start();
        $.ajax({
            type: "post",
            url: "pages/home-page/action.php",
            data: {
                fn: "load_page"
            },
            dataType: "json",
            success: function (res) {
                if (typeof NProgress !== 'undefined') NProgress.done();
                if (res.data == 'n') {
                    Swal.fire(res.title, res.message, res.icon).then((result) => {
                        window.location.href = res.url;
                    });
                } else if (res.data && res.data.length > 0) {
                    var pageData = res.data[0];

                    if (pageData.page_banner && pageData.page_banner !== "") {
                        $('#banner_img').find('img').attr('src', '../files/banner/' + pageData.page_banner);
                        $('#banner_img').show();
                        $('#form_banner').hide();
                    } else {
                        $('#banner_img').find('img').attr('src', '');
                        $('#banner-label').html('เลือกรูปภาพแบนเนอร์...');
                        $('#page_banner').val('');
                        $('#banner_img').hide();
                        $('#form_banner').show();
                    }

                    if (pageData.page_title && pageData.page_title !== "") {
                        $('#page_title').html(pageData.page_title);
                        $('.edit-title').show();
                        $('.add-title').hide();
                    } else {
                        $('#page_title').html('<span class="text-muted font-italic">ยังไม่มีข้อความไตเติ้ล</span>');
                        $('.edit-title').hide();
                        $('.add-title').show();
                    }

                    $('.edit-title').off('click').on('click', function (e) {
                        e.preventDefault();
                        $('#header-modal').removeClass('bg-success').addClass('bg-warning');
                        $('#edit-title').removeClass('btn-success').addClass('btn-warning');
                        if (tinymce.get('title')) {
                            tinymce.get('title').setContent(pageData.page_title || '');
                        }
                        $('.modal_title').modal('show');
                    });
                }
            },
            error: function (xhr) {
                if (typeof NProgress !== 'undefined') NProgress.done();
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถโหลดข้อมูลหน้าแรกได้ (HTTP ' + xhr.status + ')', 'error');
            }
        });
    }

    $('#form_banner').submit(function (e) {
        e.preventDefault();
        var fileInput = document.getElementById('page_banner');
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
            Swal.fire('แจ้งเตือน', 'กรุณาเลือกไฟล์รูปภาพแบนเนอร์', 'warning');
            return;
        }

        var btn = $('#add-banner');
        btn.prop('disabled', true);
        if (typeof NProgress !== 'undefined') NProgress.start();

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
                btn.prop('disabled', false);
                if (typeof NProgress !== 'undefined') NProgress.done();
                Swal.fire(res.title, res.message, res.icon).then((result) => {
                    if (res.data === 'y') {
                        $('#form_banner')[0].reset();
                        $('#banner-label').html('เลือกรูปภาพแบนเนอร์...');
                        load_page();
                    }
                });
            },
            error: function (xhr, status, error) {
                btn.prop('disabled', false);
                if (typeof NProgress !== 'undefined') NProgress.done();
                var msg = 'ไม่สามารถอัปโหลดไฟล์ได้';
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

    $('#delete-banner').click(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'คุณต้องการลบแบนเนอร์ใช่หรือไม่?',
            text: 'เมื่อลบแล้วจะไม่สามารถกู้คืนได้',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ใช่, ลบภาพนี้',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                var btn = $('#delete-banner');
                btn.prop('disabled', true);
                if (typeof NProgress !== 'undefined') NProgress.start();
                $.ajax({
                    type: "post",
                    url: "pages/home-page/action.php",
                    data: {
                        fn: "delete_banner"
                    },
                    dataType: "json",
                    success: function (res) {
                        btn.prop('disabled', false);
                        if (typeof NProgress !== 'undefined') NProgress.done();
                        Swal.fire(res.title, res.message, res.icon).then((result) => {
                            if (res.data === 'y') {
                                load_page();
                            }
                        });
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false);
                        if (typeof NProgress !== 'undefined') NProgress.done();
                        Swal.fire('ข้อผิดพลาด', 'ไม่สามารถลบแบนเนอร์ได้ (HTTP ' + xhr.status + ')', 'error');
                    }
                });
            }
        });
    });

    $('.add-title').click(function (e) {
        e.preventDefault();
        $('#header-modal').removeClass('bg-warning').addClass('bg-success');
        $('#edit-title').removeClass('btn-warning').addClass('btn-success');
        if (tinymce.get('title')) {
            tinymce.get('title').setContent('');
        }
        $('.modal_title').modal('show');
    });

    $('#edit-title').click(function (e) {
        e.preventDefault();
        var page_title = tinymce.get('title') ? tinymce.get('title').getContent() : '';
        var btn = $('#edit-title');
        btn.prop('disabled', true);
        if (typeof NProgress !== 'undefined') NProgress.start();

        $.ajax({
            type: "post",
            url: "pages/home-page/action.php",
            data: {
                fn: "edit_title",
                page_title: page_title
            },
            dataType: "json",
            success: function (res) {
                btn.prop('disabled', false);
                if (typeof NProgress !== 'undefined') NProgress.done();
                Swal.fire(res.title, res.message, res.icon).then((result) => {
                    if (res.data === 'y') {
                        load_page();
                        $('.modal_title').modal('hide');
                    }
                });
            },
            error: function (xhr) {
                btn.prop('disabled', false);
                if (typeof NProgress !== 'undefined') NProgress.done();
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถบันทึกข้อความได้ (HTTP ' + xhr.status + ')', 'error');
            }
        });
    });
});