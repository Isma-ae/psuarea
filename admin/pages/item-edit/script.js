$(document).ready(function () {
    // ปิด preloader ทันทีเพื่อป้องกันหน้าค้างที่สถานะ loading
    if (typeof $ !== 'undefined' && $('.preloader').length) {
        $('.preloader').fadeOut(150);
    }

    // 1. กำหนดค่า TinyMCE อย่างปลอดภัย
    try {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: 'textarea#item_abstract',
                height: 300,
                promotion: false
            });
        }
    } catch (e) {
        console.warn('TinyMCE init error:', e);
    }

    // 2. เริ่มต้นการทำงาน Select2 บนชุมชน (Multiple)
    function initSelect2() {
        try {
            if ($.fn && $.fn.select2 && $('#community_id').length) {
                $('#community_id').select2({
                    theme: 'bootstrap4',
                    placeholder: 'เลือกชุมชน (สามารถเลือกได้มากกว่า 1 ชุมชน)',
                    allowClear: true,
                    width: '100%'
                });
            }
        } catch (e) {
            console.warn('Select2 init error:', e);
        }
    }
    initSelect2();
    if (!$.fn || !$.fn.select2) {
        $(window).on('load', initSelect2);
    }

    // 3. ฟังก์ชันปรับฟิลด์ตามคอลเล็กชันที่เลือก (รองรับ instant load โดยไม่ต้อง animate)
    function updateFormFields(colId, isInstant) {
        var duration = isInstant ? 0 : 250;

        if (!colId || colId === "0") {
            if (isInstant) {
                $('#section-contributor, #section-journal-volume, #section-isbn').hide();
            } else {
                $('#section-contributor, #section-journal-volume, #section-isbn').slideUp(duration);
            }
            $('#creator-add-wrapper').hide();

            $('.creator-org-col').removeClass('d-none');
            $('.creator-name-col').removeClass('col-md-10 col-md-5').addClass('col-md-6');
            $('.creator-org-col').removeClass('col-md-5').addClass('col-md-6');
            $('.creator-action-col').hide();
            return;
        }

        switch (colId) {
            case 'COM00000000001': // วิจัย - Research
            case 'COM00000000002': // วิทยานิพนธ์ - Thesis
                // Creater: Name + Organization, ผู้เขียน(ไม่สามารถเพิ่มได้)
                $('#creator-add-wrapper').hide();
                $('.creator-org-col').removeClass('d-none');
                $('.creator-name-col').removeClass('col-md-10 col-md-5').addClass('col-md-6');
                $('.creator-org-col').removeClass('col-md-5').addClass('col-md-6');
                $('.creator-action-col').hide();

                // contributor: แสดง
                if (isInstant) {
                    $('#section-contributor').show();
                    $('#section-journal-volume, #section-isbn').hide();
                } else {
                    $('#section-contributor').slideDown(duration);
                    $('#section-journal-volume, #section-isbn').slideUp(duration);
                }
                break;

            case 'COM00000000004': // บทความทางวิชาการ - Academic Article
                // Creater: Name + Organization, ผู้เขียน(เพิ่มได้)
                $('#creator-add-wrapper').show();
                $('.creator-org-col').removeClass('d-none');
                $('.creator-name-col').removeClass('col-md-10 col-md-6').addClass('col-md-5');
                $('.creator-org-col').removeClass('col-md-6').addClass('col-md-5');
                $('.creator-action-col').show();
                updateCreatorRemoveButtons();

                // Journal & Volume: แสดง, contributor & ISBN: ซ่อน
                if (isInstant) {
                    $('#section-journal-volume').show();
                    $('#section-contributor, #section-isbn').hide();
                } else {
                    $('#section-journal-volume').slideDown(duration);
                    $('#section-contributor, #section-isbn').slideUp(duration);
                }
                break;

            case 'COM00000000005': // หนังสือ - Book
                // Creater: Name, ผู้เขียน(เพิ่มได้)
                $('#creator-add-wrapper').show();
                $('.creator-org-col').addClass('d-none');
                $('.creator-name-col').removeClass('col-md-6 col-md-5').addClass('col-md-10');
                $('.creator-action-col').show();
                updateCreatorRemoveButtons();

                // ISBN: แสดง, contributor & Journal: ซ่อน
                if (isInstant) {
                    $('#section-isbn').show();
                    $('#section-contributor, #section-journal-volume').hide();
                } else {
                    $('#section-isbn').slideDown(duration);
                    $('#section-contributor, #section-journal-volume').slideUp(duration);
                }
                break;

            case 'COM00000000006': // รายงาน - Report
                // Creater: Name, ผู้เขียน(เพิ่มได้)
                $('#creator-add-wrapper').show();
                $('.creator-org-col').addClass('d-none');
                $('.creator-name-col').removeClass('col-md-6 col-md-5').addClass('col-md-10');
                $('.creator-action-col').show();
                updateCreatorRemoveButtons();

                // contributor, Journal, ISBN: ซ่อน
                if (isInstant) {
                    $('#section-contributor, #section-journal-volume, #section-isbn').hide();
                } else {
                    $('#section-contributor, #section-journal-volume, #section-isbn').slideUp(duration);
                }
                break;

            default:
                break;
        }
    }

    // เริ่มต้นปรับฟิลด์ตามคอลเล็กชันที่มีอยู่ (โหลดทันทีไม่กระตุก)
    updateFormFields($('#collection_id').val(), true);

    // เมื่อเปลี่ยนคอลเล็กชัน
    $('#collection_id').on('change', function () {
        updateFormFields($(this).val(), false);
    });

    // 4. จัดการ Creater (เพิ่ม/ลบ)
    $('#btn-add-creator').click(function () {
        let colId = $('#collection_id').val();
        let isOrgVisible = (colId === 'COM00000000004');
        let nameColClass = isOrgVisible ? 'col-md-5' : 'col-md-10';
        let orgColClass = isOrgVisible ? 'col-md-5' : 'd-none';

        let html = `
            <div class="row creator-item mb-2 align-items-center">
                <div class="${nameColClass} creator-name-col">
                    <label class="small text-muted mb-1">Name</label>
                    <input type="text" class="form-control" name="creator_name[]" placeholder="กรุณากรอก Name...">
                </div>
                <div class="${orgColClass} creator-org-col">
                    <label class="small text-muted mb-1">Organization</label>
                    <input type="text" class="form-control" name="creator_org[]" placeholder="กรุณากรอก Organization...">
                </div>
                <div class="col-md-2 creator-action-col pt-4">
                    <button type="button" class="btn btn-outline-danger btn-block btn-remove-creator">
                        <i class="fas fa-trash-alt mr-1"></i> ลบ
                    </button>
                </div>
            </div>
        `;
        $('#creators-list').append(html);
        updateCreatorRemoveButtons();
    });

    $(document).on('click', '.btn-remove-creator', function () {
        $(this).closest('.creator-item').remove();
        updateCreatorRemoveButtons();
    });

    function updateCreatorRemoveButtons() {
        let colId = $('#collection_id').val();
        if (colId === 'COM00000000001' || colId === 'COM00000000002') {
            $('.creator-action-col').hide();
            return;
        }
        let items = $('#creators-list .creator-item');
        if (items.length <= 1) {
            items.find('.btn-remove-creator').hide();
        } else {
            items.find('.btn-remove-creator').show();
        }
    }

    // 5. จัดการ contributor (เพิ่ม/ลบ)
    $('#btn-add-contributor').click(function () {
        let html = `
            <div class="row contributor-item mb-2 align-items-center">
                <div class="col-md-5">
                    <label class="small text-muted mb-1">Name</label>
                    <input type="text" class="form-control" name="contributor_name[]" placeholder="กรุณากรอก Name...">
                </div>
                <div class="col-md-5">
                    <label class="small text-muted mb-1">Role</label>
                    <input type="text" class="form-control" name="contributor_role[]" placeholder="กรุณากรอก Role (เช่น อาจารย์ที่ปรึกษา, ผู้ร่วมวิจัย)...">
                </div>
                <div class="col-md-2 pt-4">
                    <button type="button" class="btn btn-outline-danger btn-block btn-remove-contributor">
                        <i class="fas fa-trash-alt mr-1"></i> ลบ
                    </button>
                </div>
            </div>
        `;
        $('#contributors-list').append(html);
        updateContributorRemoveButtons();
    });

    $(document).on('click', '.btn-remove-contributor', function () {
        $(this).closest('.contributor-item').remove();
        updateContributorRemoveButtons();
    });

    function updateContributorRemoveButtons() {
        let items = $('#contributors-list .contributor-item');
        if (items.length <= 1) {
            items.find('.btn-remove-contributor').hide();
        } else {
            items.find('.btn-remove-contributor').show();
        }
    }

    // 6. จัดการ Subject (เพิ่ม/ลบ)
    $('#btn-add-subject').click(function () {
        let html = `
            <div class="row subject-item mb-2 align-items-center">
                <div class="col-md-10">
                    <input type="text" class="form-control" name="subject_name[]" placeholder="กรุณากรอก Subject...">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger btn-block btn-remove-subject">
                        <i class="fas fa-trash-alt mr-1"></i> ลบ
                    </button>
                </div>
            </div>
        `;
        $('#subjects-list').append(html);
        updateSubjectRemoveButtons();
    });

    $(document).on('click', '.btn-remove-subject', function () {
        $(this).closest('.subject-item').remove();
        updateSubjectRemoveButtons();
    });

    function updateSubjectRemoveButtons() {
        let items = $('#subjects-list .subject-item');
        if (items.length <= 1) {
            items.find('.btn-remove-subject').hide();
        } else {
            items.find('.btn-remove-subject').show();
        }
    }

    // 7. จัดการลบไฟล์เดิม
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
                        Swal.fire(res.title, res.message, res.icon).then(() => {
                            $(".file-show").remove();
                        });
                    }
                });
            }
        });
    });

    // 8. การตรวจสอบและส่งฟอร์ม (Submit)
    $('#edit-item').click(function (e) {
        e.preventDefault();

        // 8.1 ดึงเนื้อหาจาก TinyMCE
        if (typeof tinyMCE !== 'undefined' && tinyMCE.get('item_abstract')) {
            var item_abstract = tinyMCE.get('item_abstract').getContent();
            $('[name="item_abstract"]').val(item_abstract);
        }

        // 8.2 ตรวจสอบการเลือกชุมชน (อย่างน้อย 1 ชุมชน)
        var community_ids = $('#community_id').val();
        if (!community_ids || (Array.isArray(community_ids) && community_ids.length === 0)) {
            Swal.fire("กรุณากรอกข้อมูล", "กรุณาเลือกชุมชนอย่างน้อย 1 ชุมชน", "warning");
            $('#community_id').focus();
            return false;
        }

        // 8.3 ตรวจสอบการเลือกคอลเล็กชัน
        var collection_id = $('#collection_id').val();
        if (!collection_id || collection_id === '0') {
            Swal.fire("กรุณากรอกข้อมูล", "กรุณาเลือกคอลเล็กชัน", "warning");
            $('#collection_id').focus();
            return false;
        }

        // 8.4 ตรวจสอบ Title
        var item_title = $.trim($('[name="item_title"]').val());
        if (!item_title) {
            Swal.fire("กรุณากรอกข้อมูล", "กรุณากรอก Title", "warning");
            $('[name="item_title"]').focus();
            return false;
        }

        // 8.5 ตรวจสอบขนาดไฟล์ที่เลือกล่วงหน้า (ไม่เกิน 40 MB)
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
            return false;
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
            timeout: 180000,
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

$(window).on('load', function () {
    if (typeof $ !== 'undefined' && $('.preloader').length) {
        $('.preloader').fadeOut(100);
    }
});