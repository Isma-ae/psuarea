<?php
global $DATABASE;

$item_id = isset($_GET["item_id"]) ? trim($_GET["item_id"]) : "";

if (empty($item_id)) {
    echo '<div class="container-fluid pt-4"><div class="alert alert-danger">ไม่พบรหัสผลงานที่ต้องการดูข้อมูล</div></div>';
    return;
}

// 1. Fetch item details (direct from tb_item to ensure 100% compatibility)
$item_res = $DATABASE->QueryObj("SELECT * FROM tb_item WHERE item_id = '" . addslashes($item_id) . "' OR MD5(item_id) = '" . addslashes($item_id) . "' LIMIT 1");

if (empty($item_res)) {
    ?>
    <div class="container-fluid pt-4">
        <div class="card text-center p-5">
            <div class="card-body">
                <i data-feather="alert-circle" style="width: 60px; height: 60px; color: #ff4f70;" class="mb-3"></i>
                <h3 class="font-weight-bold">ไม่พบข้อมูลผลงาน</h3>
                <p class="text-muted">ไม่พบข้อมูลผลงานรหัส <strong><?php echo htmlspecialchars($item_id, ENT_QUOTES, 'UTF-8'); ?></strong> ในระบบ อาจถูกลบหรือไม่มีอยู่</p>
                <a href="?p=item-list" class="btn btn-primary mt-2">
                    <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> กลับสู่หน้ารายการผลงาน
                </a>
            </div>
        </div>
    </div>
    <?php
    return;
}

$item = $item_res[0];
$item_id = $item['item_id']; // Use real item_id from DB

// 1.1 Fetch Community & Collection Names safely
$community_title = "-";
if (!empty($item['community_id'])) {
    $c_q = $DATABASE->QueryObj("SELECT community_title FROM tb_community WHERE community_id = '" . addslashes($item['community_id']) . "'");
    if (!empty($c_q)) {
        $community_title = isset($c_q[0]['community_title']) ? $c_q[0]['community_title'] : "-";
    }
}

$collection_name = "-";
if (!empty($item['collection_id'])) {
    $col_q = $DATABASE->QueryObj("SELECT collection_name FROM tb_collection WHERE collection_id = '" . addslashes($item['collection_id']) . "'");
    if (!empty($col_q)) {
        $collection_name = isset($col_q[0]['collection_name']) ? $col_q[0]['collection_name'] : "-";
    }
}

$type_name = "-";
if (isset($item['type_id']) && !empty($item['type_id'])) {
    $t_q = $DATABASE->QueryObj("SELECT type_name FROM tb_type WHERE type_id = '" . addslashes($item['type_id']) . "'");
    if (!empty($t_q)) {
        $type_name = isset($t_q[0]['type_name']) ? $t_q[0]['type_name'] : "-";
    }
}

// 2. Fetch Authors (tb_writer)
$authors = $DATABASE->QueryObj("SELECT * FROM tb_writer WHERE item_id = '" . addslashes($item_id) . "' ORDER BY writer_main ASC, writer_id ASC");
if (empty($authors)) {
    $authors = array();
}

// 3. Fetch Subjects (tb_subject)
$subjects = $DATABASE->QueryObj("SELECT * FROM tb_subject WHERE item_id = '" . addslashes($item_id) . "' ORDER BY subject_id ASC");
if (empty($subjects)) {
    $subjects = array();
}

// 4. Fetch Files (tb_file)
$files = $DATABASE->QueryObj("SELECT * FROM tb_file WHERE item_id = '" . addslashes($item_id) . "' ORDER BY file_type DESC, file_id ASC");
if (empty($files)) {
    $files = array();
}

// Separate cover file and doc files
$cover_file = null;
$doc_files = array();
foreach ($files as $f) {
    if (isset($f['file_type']) && $f['file_type'] === 'cover') {
        if ($cover_file === null) {
            $cover_file = $f;
        }
    } else {
        $doc_files[] = $f;
    }
}

// Format Thai Month
$monthsThai = array(
    1 => "มกราคม", 2 => "กุมภาพันธ์", 3 => "มีนาคม", 4 => "เมษายน",
    5 => "พฤษภาคม", 6 => "มิถุนายน", 7 => "กรกฎาคม", 8 => "สิงหาคม",
    9 => "กันยายน", 10 => "ตุลาคม", 11 => "พฤศจิกายน", 12 => "ธันวาคม"
);

$issued_month_val = isset($item['item_issued_month']) ? (int)$item['item_issued_month'] : 0;
$issued_month_name = (isset($monthsThai[$issued_month_val])) ? $monthsThai[$issued_month_val] : "";
$issued_year_val = isset($item['item_issued_year']) ? $item['item_issued_year'] : "";
$issued_date_display = trim($issued_month_name . " " . $issued_year_val);
if (empty($issued_date_display)) {
    $issued_date_display = "-";
}
?>

<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">
                <i data-feather="file-text" style="width: 20px; height: 20px; margin-right: 6px; vertical-align: -3px; color: #5f76e8;"></i>
                รายละเอียดผลงาน
            </h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="./" class="text-muted">แดชบอร์ด</a></li>
                        <li class="breadcrumb-item"><a href="?p=item-list" class="text-muted">รายการ</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page"><?php echo htmlspecialchars($item['item_id'], ENT_QUOTES, 'UTF-8'); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-md-5 text-md-right mt-3 mt-md-0">
            <div class="btn-group" role="group">
                <a href="?p=item-list" class="btn btn-outline-secondary font-weight-medium">
                    <i data-feather="arrow-left" style="width: 15px; height: 15px; margin-right: 4px;"></i> ย้อนกลับ
                </a>
                <a href="?p=item-edit&item_id=<?php echo urlencode($item['item_id']); ?>" class="btn btn-warning font-weight-medium">
                    <i data-feather="edit" style="width: 15px; height: 15px; margin-right: 4px;"></i> แก้ไข
                </a>
                <a href="?p=upload&item_id=<?php echo urlencode($item['item_id']); ?>" class="btn btn-info font-weight-medium">
                    <i data-feather="upload" style="width: 15px; height: 15px; margin-right: 4px;"></i> แนบไฟล์
                </a>
                <a href="../?p=items&item_id=<?php echo md5($item['item_id']); ?>" target="_blank" class="btn btn-success font-weight-medium">
                    <i data-feather="external-link" style="width: 15px; height: 15px; margin-right: 4px;"></i> หน้าเว็บจริง
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row">

        <!-- Left Column: Main Item Details -->
        <div class="col-lg-8">

            <!-- Title & Main Overview Card -->
            <div class="card mb-4" style="border-radius: 12px; border: 1px solid #ebedf2; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge badge-primary px-3 py-1 mr-2" style="font-size: 0.82rem;">
                            รหัส: <?php echo htmlspecialchars($item['item_id'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <?php if (!empty($item['item_issued_year'])): ?>
                            <span class="badge badge-light-primary text-primary px-3 py-1 font-weight-bold" style="font-size: 0.82rem;">
                                ปีที่เผยแพร่: <?php echo htmlspecialchars($item['item_issued_year'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <h3 class="font-weight-bold text-dark mb-3" style="line-height: 1.45;">
                        <?php echo htmlspecialchars($item['item_title'], ENT_QUOTES, 'UTF-8'); ?>
                    </h3>

                    <?php if (!empty($item['item_alternative'])): ?>
                        <div class="p-3 mb-3" style="background: #f8fafc; border-left: 4px solid #5f76e8; border-radius: 4px;">
                            <small class="text-muted d-block font-weight-bold">ชื่อเรื่อง (ภาษาอื่น ๆ / Alternative Title):</small>
                            <span class="text-dark font-weight-medium"><?php echo htmlspecialchars($item['item_alternative'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($item['item_description'])): ?>
                        <div class="mt-2 text-muted" style="font-size: 0.95rem;">
                            <strong class="text-dark">คำอธิบาย:</strong> <?php echo nl2br(htmlspecialchars($item['item_description'], ENT_QUOTES, 'UTF-8')); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Abstract Card (บทคัดย่อ) -->
            <div class="card mb-4" style="border-radius: 12px; border: 1px solid #ebedf2; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title font-weight-bold text-dark mb-0">
                        <i data-feather="align-left" style="width: 18px; height: 18px; margin-right: 6px; color: #5f76e8; vertical-align: -2px;"></i>
                        บทคัดย่อ (Abstract)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <?php if (empty($item['item_abstract'])): ?>
                        <p class="text-muted mb-0 font-italic">ไม่มีข้อมูลบทคัดย่อ</p>
                    <?php else: ?>
                        <div class="abstract-content" style="font-size: 1rem; line-height: 1.8; color: #334155; text-align: justify;">
                            <?php echo $item['item_abstract']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Attached Files Card (ไฟล์เอกสารแนบ) -->
            <div class="card mb-4" style="border-radius: 12px; border: 1px solid #ebedf2; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold text-dark mb-0">
                        <i data-feather="paperclip" style="width: 18px; height: 18px; margin-right: 6px; color: #22ca80; vertical-align: -2px;"></i>
                        เอกสารแนบในรายการ (<?php echo count($doc_files); ?> ไฟล์)
                    </h5>
                    <a href="?p=upload&item_id=<?php echo urlencode($item['item_id']); ?>" class="btn btn-sm btn-outline-primary font-weight-medium">
                        <i data-feather="plus" style="width: 14px; height: 14px;"></i> จัดการไฟล์แนบ
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($doc_files)): ?>
                        <div class="text-center py-4 text-muted">
                            <i data-feather="file" style="width: 36px; height: 36px; opacity: 0.4; margin-bottom: 6px;"></i>
                            <p class="mb-0">ยังไม่มีไฟล์เอกสารแนบในรายการนี้</p>
                            <a href="?p=upload&item_id=<?php echo urlencode($item['item_id']); ?>" class="btn btn-sm btn-primary mt-2">
                                <i data-feather="upload" style="width: 14px; height: 14px;"></i> อัปโหลดไฟล์เอกสาร
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fafbfe;">
                                    <tr>
                                        <th style="width: 45px;">#</th>
                                        <th>ชื่อไฟล์</th>
                                        <th>คำอธิบาย</th>
                                        <th style="width: 120px;" class="text-center">ขนาด</th>
                                        <th style="width: 120px;" class="text-right">ดาวน์โหลด / เปิด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $f_idx = 1;
                                    foreach ($doc_files as $f): 
                                        $fname = isset($f['file_name']) ? $f['file_name'] : "";
                                        $fdesc = isset($f['file_description']) ? $f['file_description'] : "ไฟล์เอกสาร";
                                        $fext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                                        
                                        $fpath = getFilesDir("item/" . $item['item_id'] . "/" . $fname);
                                        $fsize_display = "-";
                                        if (file_exists($fpath)) {
                                            $bytes = filesize($fpath);
                                            if ($bytes >= 1048576) {
                                                $fsize_display = number_format($bytes / 1048576, 2) . ' MB';
                                            } elseif ($bytes >= 1024) {
                                                $fsize_display = number_format($bytes / 1024, 2) . ' KB';
                                            } else {
                                                $fsize_display = $bytes . ' B';
                                            }
                                        }

                                        $file_url = "../files/item/" . rawurlencode($item['item_id']) . "/" . rawurlencode($fname);
                                    ?>
                                        <tr>
                                            <td><?php echo $f_idx++; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($fext === 'pdf'): ?>
                                                        <i class="fas fa-file-pdf text-danger mr-2" style="font-size: 1.3rem;"></i>
                                                    <?php elseif (in_array($fext, ['doc', 'docx'])): ?>
                                                        <i class="fas fa-file-word text-primary mr-2" style="font-size: 1.3rem;"></i>
                                                    <?php elseif (in_array($fext, ['jpg', 'jpeg', 'png', 'webp'])): ?>
                                                        <i class="fas fa-file-image text-success mr-2" style="font-size: 1.3rem;"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-file-alt text-secondary mr-2" style="font-size: 1.3rem;"></i>
                                                    <?php endif; ?>
                                                    <span class="font-weight-medium text-dark"><?php echo htmlspecialchars($fname, ENT_QUOTES, 'UTF-8'); ?></span>
                                                </div>
                                            </td>
                                            <td class="text-muted"><?php echo htmlspecialchars($fdesc, ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td class="text-center"><span class="badge badge-light"><?php echo $fsize_display; ?></span></td>
                                            <td class="text-right">
                                                <a href="<?php echo $file_url; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i data-feather="external-link" style="width: 14px; height: 14px;"></i> เปิดดู
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Citation Card (การอ้างอิง) -->
            <?php if (!empty($item['item_citation'])): ?>
                <div class="card mb-4" style="border-radius: 12px; border: 1px solid #ebedf2; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i data-feather="bookmark" style="width: 18px; height: 18px; margin-right: 6px; color: #ff8040; vertical-align: -2px;"></i>
                            การอ้างอิง (Citation)
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-copy-citation" data-clipboard-text="<?php echo htmlspecialchars($item['item_citation'], ENT_QUOTES, 'UTF-8'); ?>">
                            <i data-feather="copy" style="width: 13px; height: 13px;"></i> คัดลอก
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <blockquote class="blockquote mb-0" style="font-size: 0.95rem; color: #475569; background: #f8fafc; padding: 15px; border-radius: 6px; border-left: 4px solid #fd7e14;">
                            <?php echo nl2br(htmlspecialchars($item['item_citation'], ENT_QUOTES, 'UTF-8')); ?>
                        </blockquote>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <!-- Right Column: Sidebar & Metadata -->
        <div class="col-lg-4">

            <!-- Cover Image Card -->
            <div class="card mb-4" style="border-radius: 12px; border: 1px solid #ebedf2; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title font-weight-bold text-dark mb-0">
                        <i data-feather="image" style="width: 18px; height: 18px; margin-right: 6px; color: #01caf1; vertical-align: -2px;"></i>
                        ภาพหน้าปก
                    </h5>
                </div>
                <div class="card-body text-center p-3">
                    <?php 
                    if ($cover_file !== null): 
                        $cover_url = "../files/item/" . rawurlencode($item['item_id']) . "/" . rawurlencode($cover_file['file_name']);
                    ?>
                        <img src="<?php echo $cover_url; ?>" alt="ภาพหน้าปกผลงาน" class="img-fluid rounded shadow-sm mb-2" style="max-height: 280px; width: auto; object-fit: cover;">
                        <div class="mt-2">
                            <a href="<?php echo $cover_url; ?>" target="_blank" class="btn btn-xs btn-outline-secondary">
                                <i data-feather="maximize-2" style="width: 12px; height: 12px;"></i> ดูภาพขนาดเต็ม
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="py-5 bg-light rounded text-muted">
                            <i data-feather="image" style="width: 48px; height: 48px; opacity: 0.3; margin-bottom: 6px;"></i>
                            <p class="mb-0" style="font-size: 0.88rem;">ไม่มีภาพหน้าปก</p>
                            <a href="?p=upload&item_id=<?php echo urlencode($item['item_id']); ?>" class="btn btn-xs btn-outline-primary mt-2">
                                <i data-feather="upload" style="width: 12px; height: 12px;"></i> อัปโหลดหน้าปก
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Authors / Researchers Card -->
            <div class="card mb-4" style="border-radius: 12px; border: 1px solid #ebedf2; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title font-weight-bold text-dark mb-0">
                        <i data-feather="users" style="width: 18px; height: 18px; margin-right: 6px; color: #5f76e8; vertical-align: -2px;"></i>
                        คณะผู้เขียน / นักวิจัย
                    </h5>
                </div>
                <div class="card-body p-3">
                    <?php if (empty($authors)): ?>
                        <p class="text-muted mb-0 font-italic text-center py-2">ไม่มีข้อมูลผู้เขียน</p>
                    <?php else: ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($authors as $w): 
                                $is_main = (isset($w['writer_main']) && (int)$w['writer_main'] === 1);
                                $w_name = trim((isset($w['writer_fname']) ? $w['writer_fname'] : '') . ' ' . (isset($w['writer_lname']) ? $w['writer_lname'] : ''));
                            ?>
                                <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="font-weight-medium text-dark">
                                        <i data-feather="user" style="width: 14px; height: 14px; margin-right: 5px; color: #64748b;"></i>
                                        <?php echo htmlspecialchars($w_name, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <?php if ($is_main): ?>
                                        <span class="badge badge-primary px-2 py-1">ผู้เขียนหลัก</span>
                                    <?php else: ?>
                                        <span class="badge badge-light px-2 py-1 text-muted">ผู้เขียนร่วม</span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Metadata Specifications Card -->
            <div class="card mb-4" style="border-radius: 12px; border: 1px solid #ebedf2; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title font-weight-bold text-dark mb-0">
                        <i data-feather="info" style="width: 18px; height: 18px; margin-right: 6px; color: #01caf1; vertical-align: -2px;"></i>
                        ข้อมูลเมทาดาทา
                    </h5>
                </div>
                <div class="card-body p-3">
                    <table class="table table-borderless table-sm mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted" style="width: 40%;"><i data-feather="grid" style="width: 13px; height: 13px;"></i> ขอบเขตเนื้อหา:</td>
                                <td class="font-weight-bold text-dark">
                                    <?php echo htmlspecialchars($community_title, ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted"><i data-feather="folder" style="width: 13px; height: 13px;"></i> คอลเลกชัน:</td>
                                <td class="font-weight-bold text-dark">
                                    <?php echo htmlspecialchars($collection_name, ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                            </tr>
                            <?php if ($type_name !== "-"): ?>
                            <tr>
                                <td class="text-muted"><i data-feather="book" style="width: 13px; height: 13px;"></i> ประเภทผลงาน:</td>
                                <td class="font-weight-bold text-dark">
                                    <?php echo htmlspecialchars($type_name, ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="text-muted"><i data-feather="calendar" style="width: 13px; height: 13px;"></i> วันที่เผยแพร่:</td>
                                <td class="font-weight-bold text-dark"><?php echo htmlspecialchars($issued_date_display, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted"><i data-feather="briefcase" style="width: 13px; height: 13px;"></i> สำนักพิมพ์:</td>
                                <td class="font-weight-medium text-dark">
                                    <?php echo !empty($item['item_publisher']) ? htmlspecialchars($item['item_publisher'], ENT_QUOTES, 'UTF-8') : '-'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted"><i data-feather="award" style="width: 13px; height: 13px;"></i> หน่วยสนับสนุน:</td>
                                <td class="font-weight-medium text-dark">
                                    <?php echo !empty($item['item_sponsorship']) ? htmlspecialchars($item['item_sponsorship'], ENT_QUOTES, 'UTF-8') : '-'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted"><i data-feather="link" style="width: 13px; height: 13px;"></i> E-Book / URI:</td>
                                <td>
                                    <?php if (!empty($item['item_uri'])): ?>
                                        <a href="<?php echo htmlspecialchars($item['item_uri'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="text-primary font-weight-medium text-truncate d-inline-block" style="max-width: 160px;" title="<?php echo htmlspecialchars($item['item_uri'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo htmlspecialchars($item['item_uri'], ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subjects / Keywords Card (คำสำคัญ) -->
            <div class="card mb-4" style="border-radius: 12px; border: 1px solid #ebedf2; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title font-weight-bold text-dark mb-0">
                        <i data-feather="tag" style="width: 18px; height: 18px; margin-right: 6px; color: #ff8040; vertical-align: -2px;"></i>
                        คำสำคัญ / หัวเรื่อง (Keywords)
                    </h5>
                </div>
                <div class="card-body p-3">
                    <?php if (empty($subjects)): ?>
                        <p class="text-muted mb-0 font-italic text-center py-2">ไม่มีข้อมูลคำสำคัญ</p>
                    <?php else: ?>
                        <div class="d-flex flex-wrap" style="gap: 6px;">
                            <?php foreach ($subjects as $sbj): 
                                $s_name = isset($sbj['subject_name']) ? $sbj['subject_name'] : '';
                                if (empty($s_name)) continue;
                            ?>
                                <span class="badge badge-light-primary text-primary px-3 py-2" style="font-size: 0.85rem; border-radius: 20px;">
                                    # <?php echo htmlspecialchars($s_name, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
$(document).ready(function () {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Copy Citation Button
    $('#btn-copy-citation').click(function () {
        var text = $(this).attr('data-clipboard-text');
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'คัดลอกสำเร็จ',
                    text: 'คัดลอกข้อความการอ้างอิงไปยังคลิปบอร์ดแล้ว',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        } else {
            var temp = $("<textarea>");
            $("body").append(temp);
            temp.val(text).select();
            document.execCommand("copy");
            temp.remove();
            Swal.fire({
                icon: 'success',
                title: 'คัดลอกสำเร็จ',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
});
</script>
