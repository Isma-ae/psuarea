<style>
.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
    background-color: #5f76e8;
    border-color: #5f76e8;
    color: #fff;
    padding: 3px 10px;
    margin-top: 4px;
    margin-right: 4px;
    border-radius: 4px;
    font-size: 13px;
}
.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff;
    margin-right: 6px;
    font-weight: bold;
}
.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ffcdd2;
}
</style>

<?php
global $DATABASE;
if (!isset($DATABASE) || $DATABASE == null) {
    include_once(dirname(__DIR__, 2) . "/php/functions.php");
}
$item_id = isset($_GET["item_id"]) ? trim($DATABASE->Escape($_GET["item_id"])) : "";
$obj = !empty($item_id) ? $DATABASE->QueryObj("SELECT * FROM tb_item WHERE item_id = '$item_id'") : array();
if (empty($obj)) {
    echo '<div class="container-fluid mt-4">
        <div class="alert alert-warning shadow-sm">
            <h4 class="alert-heading font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i>ไม่พบข้อมูลรายการ</h4>
            <p class="mb-2">ไม่พบรหัสผลงานที่ต้องการแก้ไข หรือยังไม่ได้เลือกรหัสผลงาน</p>
            <hr>
            <a href="?p=item-list" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> กลับไปหน้ารายการ</a>
        </div>
    </div>';
    echo '<script>
        (function() {
            var p = document.querySelector(".preloader");
            if (p) p.style.display = "none";
        })();
    </script>';
    return;
}
$item = $obj[0];

// ดึงชุมชนที่เลือกไว้สำหรับรายการนี้ (จาก tb_item_community หรือ fallback tb_item.community_id)
$selected_communities = array();
$item_comms = $DATABASE->QueryObj("SELECT community_id FROM tb_item_community WHERE item_id = '$item_id'");
if (!empty($item_comms)) {
    $selected_communities = array_column($item_comms, 'community_id');
} elseif (!empty($item['community_id'])) {
    $selected_communities = array($item['community_id']);
}
?>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">แดชบอร์ด</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="./" class="text-muted">แดชบอร์ด</a></li>
                        <li class="breadcrumb-item"><a href="?p=community" class="text-muted">ชุมชน</a></li>
                        <li class="breadcrumb-item"><a href="?p=collection" class="text-muted">คอลเลกชัน</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">แก้ไขรายการ</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-dark">แก้ไขรายการผลงาน (<?= htmlspecialchars($item_id); ?>)</h4>
                    <form class="mt-4" id="form-item" enctype="multipart/form-data">
                        <input type="hidden" name="fn" value="edit_item">
                        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item_id); ?>">

                        <!-- 1. ชุมชน (อยู่บนสุด เลือกได้หลายชุมชนต่อ 1 รายการ) -->
                        <div class="form-group" id="section-community">
                            <label for="community_id" class="font-weight-medium text-dark">
                                ชุมชน <span class="text-danger">* (สามารถเลือกได้หลายชุมชนต่อ 1 รายการ)</span>
                            </label>
                            <select class="form-control select2" id="community_id" name="community_id[]" multiple="multiple" style="width: 100%;">
                                <?php
                                $all_communities = $DATABASE->QueryObj("SELECT * FROM tb_community ORDER BY community_id ASC");
                                foreach ($all_communities as $c) {
                                    $is_selected = in_array($c['community_id'], $selected_communities) ? ' selected' : '';
                                    echo '<option value="' . htmlspecialchars($c['community_id']) . '"' . $is_selected . '>' . htmlspecialchars($c['community_title']) . '</option>';
                                }
                                ?>
                            </select>
                            <small class="form-text text-muted">คลิกเพื่อเลือกหรือพิมพ์ค้นหาชื่อชุมชน สามารถเลือกได้มากกว่า 1 ชุมชน</small>
                        </div>

                        <!-- 2. คอลเล็กชัน (ถัดมาเป็นคอลเล็กชัน) -->
                        <div class="form-group" id="section-collection">
                            <label for="collection_id" class="font-weight-medium text-dark">
                                คอลเล็กชัน <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="collection_id" name="collection_id" style="width: 100%;">
                                <option value="0">-- กรุณาเลือกคอลเล็กชัน --</option>
                                <?php
                                $target_ids = "'COM00000000001','COM00000000002','COM00000000004','COM00000000005','COM00000000006'";
                                $collections = $DATABASE->QueryObj("SELECT * FROM tb_collection WHERE collection_id IN ($target_ids) ORDER BY FIELD(collection_id, 'COM00000000001','COM00000000002','COM00000000004','COM00000000005','COM00000000006')");
                                foreach ($collections as $col) {
                                    $is_selected = ($col['collection_id'] == $item['collection_id']) ? ' selected' : '';
                                    echo '<option value="' . htmlspecialchars($col['collection_id']) . '"' . $is_selected . '>' . htmlspecialchars($col['collection_name']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <hr class="my-4">

                        <!-- ฟิลด์ด้านล่างตามคอลเล็กชันที่เลือก -->
                        <div id="dynamic-fields">
                            <!-- Title -->
                            <div class="form-group" id="section-title">
                                <label for="item_title" class="font-weight-medium text-dark">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="item_title" name="item_title"
                                    value="<?= htmlspecialchars($item['item_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    placeholder="กรุณากรอก Title...">
                            </div>

                            <!-- Title Alternative -->
                            <div class="form-group" id="section-alternative">
                                <label for="item_alternative" class="font-weight-medium text-dark">Title Alternative</label>
                                <input type="text" class="form-control" id="item_alternative" name="item_alternative"
                                    value="<?= htmlspecialchars($item['item_alternative'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    placeholder="กรุณากรอก Title Alternative...">
                            </div>

                            <!-- Creater -->
                            <div class="form-group" id="section-creator">
                                <label class="font-weight-medium text-dark">Creater <span class="text-danger">*</span></label>
                                <div id="creators-list">
                                    <?php
                                    $authors = $DATABASE->QueryObj("SELECT * FROM tb_writer WHERE item_id = '$item_id' ORDER BY writer_main ASC, writer_id ASC");
                                    if (empty($authors)) {
                                        $authors = array(array('writer_fname' => '', 'writer_lname' => '', 'writer_organization' => ''));
                                    }
                                    foreach ($authors as $idx => $writer) {
                                        $w_name = trim(($writer['writer_fname'] ?? '') . ' ' . ($writer['writer_lname'] ?? ''));
                                        $w_org = $writer['writer_organization'] ?? '';
                                    ?>
                                    <div class="row creator-item mb-2 align-items-center">
                                        <div class="col-md-6 creator-name-col">
                                            <label class="small text-muted mb-1">Name</label>
                                            <input type="text" class="form-control" name="creator_name[]"
                                                value="<?= htmlspecialchars($w_name, ENT_QUOTES, 'UTF-8'); ?>"
                                                placeholder="กรุณากรอก Name...">
                                        </div>
                                        <div class="col-md-6 creator-org-col">
                                            <label class="small text-muted mb-1">Organization</label>
                                            <input type="text" class="form-control" name="creator_org[]"
                                                value="<?= htmlspecialchars($w_org, ENT_QUOTES, 'UTF-8'); ?>"
                                                placeholder="กรุณากรอก Organization...">
                                        </div>
                                        <div class="col-md-2 creator-action-col pt-4" style="display: none;">
                                            <button type="button" class="btn btn-outline-danger btn-block btn-remove-creator" style="<?= ($idx === 0 && count($authors) === 1) ? 'display: none;' : '' ?>">
                                                <i class="fas fa-trash-alt mr-1"></i> ลบ
                                            </button>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="creator-add-wrapper mt-2" id="creator-add-wrapper" style="display: none;">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-creator">
                                        <i class="fas fa-plus mr-1"></i> เพิ่ม Creater
                                    </button>
                                </div>
                            </div>

                            <!-- contributor (COM00000000001, COM00000000002) -->
                            <div class="form-group" id="section-contributor" style="display: none;">
                                <label class="font-weight-medium text-dark">contributor</label>
                                <div id="contributors-list">
                                    <?php
                                    $contributors = $DATABASE->QueryObj("SELECT * FROM tb_contributor WHERE item_id = '$item_id' ORDER BY contributor_id ASC");
                                    if (empty($contributors)) {
                                        $contributors = array(array('contributor_name' => '', 'contributor_role' => ''));
                                    }
                                    foreach ($contributors as $c_idx => $cnt) {
                                        $c_name = $cnt['contributor_name'] ?? '';
                                        $c_role = $cnt['contributor_role'] ?? '';
                                    ?>
                                    <div class="row contributor-item mb-2 align-items-center">
                                        <div class="col-md-5">
                                            <label class="small text-muted mb-1">Name</label>
                                            <input type="text" class="form-control" name="contributor_name[]"
                                                value="<?= htmlspecialchars($c_name, ENT_QUOTES, 'UTF-8'); ?>"
                                                placeholder="กรุณากรอก Name...">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="small text-muted mb-1">Role</label>
                                            <input type="text" class="form-control" name="contributor_role[]"
                                                value="<?= htmlspecialchars($c_role, ENT_QUOTES, 'UTF-8'); ?>"
                                                placeholder="กรุณากรอก Role (เช่น อาจารย์ที่ปรึกษา, ผู้ร่วมวิจัย)...">
                                        </div>
                                        <div class="col-md-2 pt-4">
                                            <button type="button" class="btn btn-outline-danger btn-block btn-remove-contributor" style="<?= ($c_idx === 0 && count($contributors) === 1) ? 'display: none;' : '' ?>">
                                                <i class="fas fa-trash-alt mr-1"></i> ลบ
                                            </button>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="contributor-add-wrapper mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-contributor">
                                        <i class="fas fa-plus mr-1"></i> เพิ่ม contributor
                                    </button>
                                </div>
                            </div>

                            <!-- Journal & Volume (COM00000000004) -->
                            <div id="section-journal-volume" class="row" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="item_journal" class="font-weight-medium text-dark">Journal</label>
                                        <input type="text" class="form-control" id="item_journal" name="item_journal"
                                            value="<?= htmlspecialchars($item['item_journal'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                            placeholder="กรุณากรอก Journal...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="item_volume" class="font-weight-medium text-dark">Volume</label>
                                        <input type="text" class="form-control" id="item_volume" name="item_volume"
                                            value="<?= htmlspecialchars($item['item_volume'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                            placeholder="กรุณากรอก Volume...">
                                    </div>
                                </div>
                            </div>

                            <!-- ISBN (COM00000000005) -->
                            <div class="form-group" id="section-isbn" style="display: none;">
                                <label for="item_isbn" class="font-weight-medium text-dark">ISBN</label>
                                <input type="text" class="form-control" id="item_isbn" name="item_isbn"
                                    value="<?= htmlspecialchars($item['item_isbn'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    placeholder="กรุณากรอก ISBN...">
                            </div>

                            <!-- year -->
                            <div class="form-group" id="section-year">
                                <label for="item_issued_year" class="font-weight-medium text-dark">year</label>
                                <input type="text" class="form-control" id="item_issued_year" name="item_issued_year"
                                    value="<?= htmlspecialchars($item['item_issued_year'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    placeholder="กรุณากรอก year (เช่น 2567)...">
                            </div>

                            <!-- Description -->
                            <div class="form-group" id="section-description">
                                <label for="item_abstract" class="font-weight-medium text-dark">Description</label>
                                <textarea id="item_abstract"><?= htmlspecialchars($item['item_abstract'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                <input type="hidden" name="item_abstract" value="<?= htmlspecialchars($item['item_abstract'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <!-- Subject -->
                            <div class="form-group" id="section-subject">
                                <label class="font-weight-medium text-dark">Subject</label>
                                <div id="subjects-list">
                                    <?php
                                    $subjects = $DATABASE->QueryObj("SELECT * FROM tb_subject WHERE item_id = '$item_id' ORDER BY subject_id ASC");
                                    if (empty($subjects)) {
                                        $subjects = array(array('subject_name' => ''));
                                    }
                                    foreach ($subjects as $s_idx => $s) {
                                        $s_name = $s['subject_name'] ?? '';
                                    ?>
                                    <div class="row subject-item mb-2 align-items-center">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="subject_name[]"
                                                value="<?= htmlspecialchars($s_name, ENT_QUOTES, 'UTF-8'); ?>"
                                                placeholder="กรุณากรอก Subject...">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-danger btn-block btn-remove-subject" style="<?= ($s_idx === 0 && count($subjects) === 1) ? 'display: none;' : '' ?>">
                                                <i class="fas fa-trash-alt mr-1"></i> ลบ
                                            </button>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="subject-add-wrapper mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-subject">
                                        <i class="fas fa-plus mr-1"></i> เพิ่ม Subject
                                    </button>
                                </div>
                            </div>

                            <!-- Click to view -->
                            <div class="form-group" id="section-uri">
                                <label for="item_uri" class="font-weight-medium text-dark">Click to view</label>
                                <input type="text" class="form-control" id="item_uri" name="item_uri"
                                    value="<?= htmlspecialchars($item['item_uri'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    placeholder="กรุณากรอก Click to view (เช่น https://...)">
                            </div>

                            <!-- file -->
                            <div class="form-group" id="section-file">
                                <label for="file_name" class="font-weight-medium text-dark">file</label>
                                <?php
                                $file = $DATABASE->QueryObj("SELECT * FROM tb_file WHERE file_type = 'file' AND item_id = '$item_id'");
                                if (!empty($file)) {
                                ?>
                                    <div class="file-show mb-2 p-2 border rounded bg-light d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-file-alt text-primary mr-2"></i>
                                            <a href="../files/item/<?= $item_id ?>/<?= $file[0]["file_name"] ?>" target="_blank" class="font-weight-medium text-primary">
                                                <?= htmlspecialchars($file[0]["file_name"]) ?>
                                            </a>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="del-file" file-id="<?= $file[0]["file_id"] ?>">
                                            <i class="fas fa-trash-alt mr-1"></i> ลบไฟล์
                                        </button>
                                    </div>
                                <?php } ?>
                                <div class="file-add">
                                    <input type="file" class="form-control-file border p-2 rounded w-100 bg-white" id="file_name" name="file_name">
                                    <small class="form-text text-muted">
                                        <?= !empty($file) ? 'เลือกไฟล์ใหม่หากต้องการเปลี่ยนแทนที่ไฟล์เดิม ' : '' ?>รองรับไฟล์เอกสาร PDF หรือรูปภาพ (แนะนำขนาดไม่เกิน 40 MB)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="form-group mb-0">
                            <button type="button" class="btn btn-warning px-4 font-weight-medium" id="edit-item">
                                <i class="fas fa-save mr-1"></i> บันทึกการแก้ไข
                            </button>
                            <a href="?p=item-list" class="btn btn-secondary px-4 ml-2">ยกเลิก</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="pages/item-edit/script.js"></script>
<script>
    (function() {
        function removePreloader() {
            var preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                preloader.style.transition = 'opacity 0.2s ease';
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 200);
            }
        }
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            removePreloader();
        } else {
            window.addEventListener('DOMContentLoaded', removePreloader);
            window.addEventListener('load', removePreloader);
        }
        setTimeout(removePreloader, 600);
    })();
</script>