<script src="pages/home-page/script.js"></script>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">แดชบอร์ด</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="index.html" class="text-muted">แดชบอร์ด</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">จัดการหน้าแรก</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">แบนเนอร์</h4>
                    <div id="banner_img" style="display: none;">
                        <img src="" alt="แบนเนอร์หน้าแรก" class="img-thumbnail d-block mb-3" style="max-height: 350px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-danger" id="delete-banner">
                            <i class="fas fa-trash-alt mr-1"></i> ลบแบนเนอร์
                        </button>
                    </div>
                    <form class="mt-4" id="form_banner" enctype="multipart/form-data">
                        <input type="hidden" id="fn" name="fn" value="add_banner">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="page_banner" name="page_banner" accept="image/*">
                                    <label class="custom-file-label" for="page_banner" id="banner-label">เลือกรูปภาพแบนเนอร์...</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">รองรับไฟล์รูปภาพ เช่น .jpg, .png, .webp (แนะนำขนาด 1920x600 px)</small>
                        </div>
                        <button type="submit" class="btn btn-success" id="add-banner">
                            <i class="fas fa-upload mr-1"></i> เพิ่มแบนเนอร์
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">ข้อความไตเติ้ล</h4>
                    <div id="title_show">
                        <div id="page_title" class="p-3 bg-light rounded mb-3 border"></div>
                        <button type="button" class="btn btn-warning edit-title">
                            <i class="fas fa-edit mr-1"></i> แก้ไขข้อความ
                        </button>
                        <button type="button" class="btn btn-success add-title">
                            <i class="fas fa-plus mr-1"></i> เพิ่มข้อความ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="warning-header-modal" class="modal fade modal_title" tabindex="-1" role="dialog"
    aria-labelledby="warning-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-warning" id="header-modal">
                <h4 class="modal-title" id="warning-header-modalLabel">จัดการข้อความไตเติ้ล</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <textarea id="title"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-warning" id="edit-title">บันทึก</button>
            </div>
        </div>
    </div>
</div>