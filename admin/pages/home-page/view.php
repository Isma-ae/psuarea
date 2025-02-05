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
                    <div id="banner_img">
                        <img src="..." alt="..." class="img-thumbnail" width="100%">
                        <button type="button" class="btn btn-danger" id="delete-banner">ลบแบนเนอร์</button>
                    </div>
                    <form class="mt-4" id="form_banner">
                        <input type="hidden" id="fn" name="fn" value="add_banner">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="page_banner" name="page_banner">
                                    <label class="custom-file-label" for="page_banner" id="banner-label"></label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" id="add-banner">เพิ่มแบนเนอร์</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">ข้อความไตเติ้ล</h4>
                    <div id="title_show">
                        <p id="page_title"></p>
                        <button type="button" class="btn btn-warning edit-title">แก้ไขข้อความ</button>
                        <button type="button" class="btn btn-success add-title">เพิ่มข้อความ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="warning-header-modal" class="modal fade modal-title" tabindex="-1" role="dialog"
    aria-labelledby="warning-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-warning">
                <h4 class="modal-title" id="warning-header-modalLabel">Modal Heading
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <textarea id="basic-example"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="edit-title">บันทึก</button>
            </div>
        </div>
    </div>
</div>