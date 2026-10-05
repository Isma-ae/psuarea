<script src="pages/upload/script.js"></script>
<?php
    $sql = "SELECT * FROM tb_item WHERE item_id = '".$_GET["item_id"]."'";
    $obj = $DATABASE->QueryObj($sql);
?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">แดชบอร์ด</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="index.html" class="text-muted">แดชบอร์ด</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">รายการ</li>
                        <li class="breadcrumb-item text-muted active" aria-current="page"><?= $obj[0]["item_title"];?></li>
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
                    <div class="d-flex align-items-center mb-4">
                        <h4 class="card-title"><?= $obj[0]["item_title"];?></h4>
                        <div class="ml-auto">
                            <div class="dropdown sub-dropdown">
                                <button class="btn btn-success" type="button" id="add">
                                    เพิ่มไฟล์
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-resposive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th width="200px;">ไฟล์</th>
                                    <th>ชื่อไฟล์</th>
                                    <th width="120px">จัดการ</th>
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

<div id="file-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="file-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-warning" id="header-modal">
                <h4 class="modal-title" id="file-header-modalLabel">Modal Heading</h4>
                <button type="button" class="close" data-backdrop="false" data-dismiss="modal"
                    aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="form-file">
                    <input type="hidden" id="file_id" name="file_id">
                    <input type="hidden" id="item_id" name="item_id" value="<?= $_GET["item_id"];?>">
                    <input type="hidden" name="fn" id="fn">
                    <div class="mb-3">
                        <label for="file_name" class="form-label">ไฟล์</label>
                        <input class="form-control" type="file" name="file_name" id="file_name">
                    </div>
                    <div class="mb-3">
                        <label for="file_type" class="form-label">ประเภทไฟล์</label>
                        <select class="form-control" type="text" name="file_type" id="file_type">
                            <option value="cover">ปกหน้า</option>
                            <option value="bcover">ปกหลัง</option>
                            <option value="introduction">คำนำ</option>
                            <option value="contents">สารบัญ</option>
                            <option value="file">เนื้อหา</option>
                            <option value="other">อื่น ๆ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="file_description" class="form-label">ชื่อไฟล์</label>
                        <input class="form-control" type="text" name="file_description" id="file_description"
                            placeholder="กรุณากรอกคำอธิบายสั้น ๆ...">
                    </div>
                    <button type="submit" class="btn btn-success" id="add-file">เพิ่มไฟล์</button>
                    <button type="submit" class="btn btn-warning" id="edit-file">แก้ไขไฟล์</button>
                </form>
            </div>
        </div>
    </div>
</div>
