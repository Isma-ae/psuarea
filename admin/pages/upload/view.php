<script src="pages/upload/script.js"></script>
<?php
    $item_id = isset($_GET["item_id"]) ? addslashes(trim($_GET["item_id"])) : "";
    $sql = "SELECT * FROM tb_item WHERE item_id = '$item_id'";
    $obj = $DATABASE->QueryObj($sql);
    $item_title = (!empty($obj) && isset($obj[0]["item_title"])) ? $obj[0]["item_title"] : "ไม่พบรายการ";
?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">จัดการไฟล์แนบ</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="./" class="text-muted">แดชบอร์ด</a></li>
                        <li class="breadcrumb-item"><a href="?p=item-list" class="text-muted">รายการทั้งหมด</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page"><?= htmlspecialchars($item_title);?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center text-right">
            <a href="?p=item-list" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> กลับหน้ารายการ
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-folder-open text-primary mr-2"></i>
                            <?= htmlspecialchars($item_title);?>
                            <span class="badge badge-light border ml-2"><?= htmlspecialchars($item_id);?></span>
                        </h4>
                        <div class="ml-auto">
                            <button class="btn btn-success" type="button" id="add">
                                <i class="fas fa-plus mr-1"></i> เพิ่มไฟล์
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="30%">ไฟล์</th>
                                    <th width="15%">ประเภท</th>
                                    <th width="35%">คำอธิบายไฟล์</th>
                                    <th width="15%" class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="post_data"></tbody>
                        </table>
                    </div>
                    <div id="pagination_link"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="file-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="file-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-warning" id="header-modal">
                <h4 class="modal-title" id="file-header-modalLabel">จัดการไฟล์</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="form-file" enctype="multipart/form-data">
                    <input type="hidden" id="file_id" name="file_id">
                    <input type="hidden" id="item_id" name="item_id" value="<?= htmlspecialchars($item_id);?>">
                    <input type="hidden" name="fn" id="fn">
                    <div class="form-group mb-3">
                        <label for="file_name" class="form-label font-weight-bold">เลือกไฟล์</label>
                        <div class="custom-file">
                            <input class="custom-file-input" type="file" name="file_name" id="file_name">
                            <label class="custom-file-label" for="file_name" id="file-label">เลือกไฟล์...</label>
                        </div>
                        <small id="file-help" class="form-text text-muted">รองรับไฟล์เอกสาร PDF หรือรูปภาพ (ถ้าไม่ต้องการเปลี่ยนไฟล์เดิม ให้เว้นว่างไว้เมื่อแก้ไข)</small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="file_type" class="form-label font-weight-bold">ประเภทไฟล์ <span class="text-danger">*</span></label>
                        <select class="form-control" name="file_type" id="file_type" required>
                            <option value="cover">ปกหน้า (cover)</option>
                            <option value="bcover">ปกหลัง (bcover)</option>
                            <option value="introduction">คำนำ (introduction)</option>
                            <option value="contents">สารบัญ (contents)</option>
                            <option value="file">เนื้อหา (file)</option>
                            <option value="other">อื่น ๆ (other)</option>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="file_description" class="form-label font-weight-bold">คำอธิบายไฟล์</label>
                        <input class="form-control" type="text" name="file_description" id="file_description"
                            placeholder="เช่น ไฟล์ฉบับเต็ม, ปกหน้าความละเอียดสูง...">
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-light" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-success" id="add-file"><i class="fas fa-upload mr-1"></i> เพิ่มไฟล์</button>
                        <button type="submit" class="btn btn-warning" id="edit-file"><i class="fas fa-save mr-1"></i> บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
