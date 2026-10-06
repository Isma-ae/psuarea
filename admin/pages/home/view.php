<?php
// Ensure database connection is available
global $DATABASE;

// Helper to safely get value whether row is array or object
function dashVal($row, $key, $default = '') {
    if (is_array($row)) {
        return isset($row[$key]) ? $row[$key] : $default;
    } elseif (is_object($row)) {
        return isset($row->$key) ? $row->$key : $default;
    }
    return $default;
}

// 1. Total Items (Using QueryString for direct scalar count)
$total_items = (int)$DATABASE->QueryString("SELECT COUNT(item_id) FROM tb_item");

// 2. Total Communities
$total_communities = (int)$DATABASE->QueryString("SELECT COUNT(community_id) FROM tb_community");

// 3. Total Collections
$total_collections = (int)$DATABASE->QueryString("SELECT COUNT(collection_id) FROM tb_collection");

// 4. Total Files Breakdown
$total_files = (int)$DATABASE->QueryString("SELECT COUNT(file_id) FROM tb_file");
$total_docs = (int)$DATABASE->QueryString("SELECT COUNT(file_id) FROM tb_file WHERE file_type = 'file'");
$total_covers = (int)$DATABASE->QueryString("SELECT COUNT(file_id) FROM tb_file WHERE file_type = 'cover'");

// 5. Total Writers (Authors)
$total_writers = (int)$DATABASE->QueryString("SELECT COUNT(DISTINCT CONCAT(writer_fname, ' ', writer_lname)) FROM tb_writer");

// 6. Total Subjects (Keywords)
$total_subjects = (int)$DATABASE->QueryString("SELECT COUNT(DISTINCT subject_name) FROM tb_subject");

// 7. Total Types
$total_types = (int)$DATABASE->QueryString("SELECT COUNT(type_id) FROM tb_type");

// 8. Total Admin Users
$total_users = (int)$DATABASE->QueryString("SELECT COUNT(user_id) FROM tb_user");

// 9. Items by Year (Top 8 years)
$years_labels = array();
$years_data = array();
$res_years = $DATABASE->QueryObj("SELECT item_issued_year, COUNT(item_id) AS total_count
    FROM tb_item
    WHERE item_issued_year IS NOT NULL AND item_issued_year != '' AND item_issued_year != '0'
    GROUP BY item_issued_year
    ORDER BY item_issued_year DESC
    LIMIT 8");
if (!empty($res_years)) {
    $res_years = array_reverse($res_years);
    foreach ($res_years as $y) {
        $year_val = dashVal($y, 'item_issued_year');
        $count_val = dashVal($y, 'total_count', 0);
        $years_labels[] = "ปี " . $year_val;
        $years_data[] = (int)$count_val;
    }
}

// 10. Items by Community / Scope of Content (ขอบเขตเนื้อหา)
$community_labels = array();
$community_data = array();
$res_comm_dist = $DATABASE->QueryObj("SELECT 
    CASE 
        WHEN c.community_title IS NOT NULL AND c.community_title != '' THEN c.community_title 
        ELSE 'ไม่ระบุขอบเขตเนื้อหา' 
    END AS comm_title,
    COUNT(i.item_id) AS total_count
FROM tb_item AS i
LEFT JOIN tb_community AS c ON i.community_id = c.community_id
GROUP BY i.community_id
ORDER BY total_count DESC
LIMIT 7");
if (!empty($res_comm_dist)) {
    foreach ($res_comm_dist as $c) {
        $comm_val = dashVal($c, 'comm_title');
        $count_val = dashVal($c, 'total_count', 0);
        $community_labels[] = $comm_val;
        $community_data[] = (int)$count_val;
    }
}

// 11. Top 5 Communities with most items (Note: column is community_title)
$top_communities = $DATABASE->QueryObj("SELECT 
    c.community_id,
    c.community_title,
    c.community_img,
    COUNT(i.item_id) AS item_count
FROM tb_community AS c
LEFT JOIN tb_item AS i ON c.community_id = i.community_id
GROUP BY c.community_id
ORDER BY item_count DESC
LIMIT 5");
if (empty($top_communities)) {
    $top_communities = array();
}

// 12. Recent Items (Latest 8 items)
$recent_items = $DATABASE->QueryObj("SELECT 
    i.item_id,
    i.item_title,
    i.item_issued_year,
    c.community_title,
    col.collection_name,
    (SELECT COUNT(f.file_id) FROM tb_file f WHERE f.item_id = i.item_id) AS file_count,
    (SELECT GROUP_CONCAT(DISTINCT CONCAT(w.writer_fname, ' ', w.writer_lname) SEPARATOR ', ') 
     FROM tb_writer w WHERE w.item_id = i.item_id) AS writer_names
FROM tb_item AS i
LEFT JOIN tb_community AS c ON i.community_id = c.community_id
LEFT JOIN tb_collection AS col ON i.collection_id = col.collection_id
ORDER BY i.item_id DESC
LIMIT 8");
if (empty($recent_items)) {
    $recent_items = array();
}

// Format Thai Date
$thai_months = array(
    1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
    5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
    9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
);
$today_thai = date('j') . ' ' . $thai_months[(int)date('n')] . ' ' . (date('Y') + 543);

// Storage & System Health Check
$files_dir = getFilesDir("item");
$is_files_writable = is_dir($files_dir) && is_writable($files_dir);
$php_version = phpversion();
// User Full Name (First name and Last name)
$current_user = "";
if (isset($_SESSION["user_fname"]) && !empty($_SESSION["user_fname"])) {
    $current_user = trim($_SESSION["user_fname"] . (isset($_SESSION["user_lname"]) ? " " . $_SESSION["user_lname"] : ""));
} elseif (isset($_SESSION["user_name"])) {
    $user_q = $DATABASE->QueryObj("SELECT user_fname, user_lname FROM tb_user WHERE user_name = '" . addslashes(trim($_SESSION["user_name"])) . "'");
    if (!empty($user_q)) {
        $fn = dashVal($user_q[0], 'user_fname');
        $ln = dashVal($user_q[0], 'user_lname');
        if (!empty($fn)) {
            $current_user = trim($fn . " " . $ln);
        }
    }
}
if (empty($current_user)) {
    $current_user = isset($_SESSION["user_name"]) ? $_SESSION["user_name"] : "ผู้ดูแลระบบ";
}
?>

<!-- Home Dashboard Custom Styles -->
<link rel="stylesheet" href="pages/home/style.css">

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">แดชบอร์ดภาพรวม</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="./" class="text-primary font-weight-bold">หน้าหลัก</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">แดชบอร์ด</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center text-right">
            <span class="badge badge-light-primary text-primary px-3 py-2" style="font-size: 0.85rem;">
                <i data-feather="calendar" style="width: 14px; height: 14px; margin-right: 4px;"></i> <?php echo $today_thai; ?>
            </span>
        </div>
    </div>
</div>

<div class="container-fluid">

    <!-- 1. Hero Welcome Card with Quick Actions -->
    <div class="dashboard-hero">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-12 mb-3 mb-lg-0">
                <h2 class="hero-title">
                    สวัสดีคุณ <?php echo htmlspecialchars($current_user, ENT_QUOTES, 'UTF-8'); ?> 👋
                </h2>
                <p class="hero-subtitle">
                    ยินดีต้อนรับสู่ระบบบริหารจัดการคลังสารสนเทศดิจิทัล <strong>PSU AREA</strong> ข้อมูลสถิติและสถานะระบบล่าสุดพร้อมให้คุณตรวจสอบ
                </p>
            </div>
            <div class="col-lg-5 col-md-12 text-lg-right">
                <div class="btn-group" role="group">
                    <a href="?p=item-add" class="btn hero-btn hero-btn-primary mr-2">
                        <i data-feather="plus-circle" style="width: 16px; height: 16px; margin-right: 4px; vertical-align: -2px;"></i> เพิ่มผลงานใหม่
                    </a>
                    <a href="?p=community" class="btn hero-btn hero-btn-outline mr-2">
                        <i data-feather="grid" style="width: 16px; height: 16px; margin-right: 4px; vertical-align: -2px;"></i> ชุมชน
                    </a>
                    <a href="../" target="_blank" class="btn hero-btn hero-btn-outline">
                        <i data-feather="external-link" style="width: 16px; height: 16px; margin-right: 4px; vertical-align: -2px;"></i> หน้าเว็บ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. KPI Summary Cards (4 Primary Stat Cards) -->
    <div class="row">
        <!-- Card 1: Total Items -->
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-primary">
                <a href="?p=item-list" class="text-decoration-none">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="kpi-label">รายการผลงานทั้งหมด</div>
                                <div class="kpi-value"><?php echo number_format($total_items); ?></div>
                                <p class="kpi-hint text-primary font-weight-medium">
                                    <i data-feather="arrow-right-circle" style="width: 13px; height: 13px; vertical-align: -1px;"></i> ดูรายการทั้งหมด
                                </p>
                            </div>
                            <div class="kpi-icon-wrap">
                                <i data-feather="file-text"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Card 2: Total Communities -->
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-info">
                <a href="?p=community" class="text-decoration-none">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="kpi-label">ชุมชน / ขอบเขตเนื้อหา</div>
                                <div class="kpi-value"><?php echo number_format($total_communities); ?></div>
                                <p class="kpi-hint text-info font-weight-medium">
                                    <i data-feather="arrow-right-circle" style="width: 13px; height: 13px; vertical-align: -1px;"></i> จัดการชุมชน
                                </p>
                            </div>
                            <div class="kpi-icon-wrap">
                                <i data-feather="grid"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Card 3: Total Collections -->
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-warning">
                <a href="?p=collection" class="text-decoration-none">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="kpi-label">คอลเลกชันจัดเก็บ</div>
                                <div class="kpi-value"><?php echo number_format($total_collections); ?></div>
                                <p class="kpi-hint text-warning font-weight-medium">
                                    <i data-feather="arrow-right-circle" style="width: 13px; height: 13px; vertical-align: -1px;"></i> จัดการคอลเลกชัน
                                </p>
                            </div>
                            <div class="kpi-icon-wrap">
                                <i data-feather="folder"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Card 4: Total Files -->
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-success">
                <a href="?p=item-list" class="text-decoration-none">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="kpi-label">ไฟล์เอกสารแนบ</div>
                                <div class="kpi-value"><?php echo number_format($total_files); ?></div>
                                <p class="kpi-hint text-muted">
                                    เอกสาร <?php echo number_format($total_docs); ?> | หน้าปก <?php echo number_format($total_covers); ?>
                                </p>
                            </div>
                            <div class="kpi-icon-wrap">
                                <i data-feather="paperclip"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Secondary Metrics Bar -->
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="secondary-stat-card">
                <div class="secondary-stat-icon text-primary">
                    <i data-feather="users"></i>
                </div>
                <div>
                    <div class="secondary-stat-value"><?php echo number_format($total_writers); ?></div>
                    <div class="secondary-stat-title">ผู้แต่ง / นักวิจัยในระบบ</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="secondary-stat-card">
                <div class="secondary-stat-icon text-info">
                    <i data-feather="tag"></i>
                </div>
                <div>
                    <div class="secondary-stat-value"><?php echo number_format($total_subjects); ?></div>
                    <div class="secondary-stat-title">คำสำคัญ / หัวเรื่อง</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="secondary-stat-card">
                <div class="secondary-stat-icon text-warning">
                    <i data-feather="book-open"></i>
                </div>
                <div>
                    <div class="secondary-stat-value"><?php echo number_format($total_types); ?></div>
                    <div class="secondary-stat-title">ประเภท / รูปแบบผลงาน</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <a href="?p=user" class="text-decoration-none w-100">
                <div class="secondary-stat-card">
                    <div class="secondary-stat-icon text-success">
                        <i data-feather="shield"></i>
                    </div>
                    <div>
                        <div class="secondary-stat-value"><?php echo number_format($total_users); ?></div>
                        <div class="secondary-stat-title">ผู้ดูแลระบบ (Admin)</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- 4. Interactive Charts Row -->
    <div class="row">
        <!-- Chart 1: Items by Publication Year -->
        <div class="col-lg-8">
            <div class="dash-card">
                <div class="card-header">
                    <div>
                        <h5 class="card-title">
                            <i data-feather="bar-chart-2" style="width: 18px; height: 18px; margin-right: 6px; color: #5f76e8; vertical-align: -2px;"></i>
                            จำนวนผลงานจำแนกตามปีที่เผยแพร่
                        </h5>
                        <p class="card-subtitle">สถิติจำนวนผลงานในแต่ละปีที่บันทึกเข้าสู่คลังสารสนเทศ</p>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($years_labels)): ?>
                        <div class="text-center py-5 text-muted">
                            <i data-feather="bar-chart" style="width: 40px; height: 40px; opacity: 0.4; margin-bottom: 8px;"></i>
                            <p class="mb-0">ยังไม่มีข้อมูลปีที่เผยแพร่ผลงาน</p>
                        </div>
                    <?php else: ?>
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="chart-items-year"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Chart 2: Items by Community / Scope Distribution -->
        <div class="col-lg-4">
            <div class="dash-card">
                <div class="card-header">
                    <div>
                        <h5 class="card-title">
                            <i data-feather="pie-chart" style="width: 18px; height: 18px; margin-right: 6px; color: #20c997; vertical-align: -2px;"></i>
                            สัดส่วนผลงานตามขอบเขตเนื้อหา
                        </h5>
                        <p class="card-subtitle">การกระจายตัวของผลงานในแต่ละขอบเขตเนื้อหา</p>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($community_labels)): ?>
                        <div class="text-center py-5 text-muted">
                            <i data-feather="pie-chart" style="width: 40px; height: 40px; opacity: 0.4; margin-bottom: 8px;"></i>
                            <p class="mb-0">ยังไม่มีข้อมูลขอบเขตเนื้อหา</p>
                        </div>
                    <?php else: ?>
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="chart-items-community"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Recent Items Table & Side Widgets Row -->
    <div class="row">
        <!-- Left Column: Recent Items Table -->
        <div class="col-lg-8">
            <div class="dash-card">
                <div class="card-header">
                    <div>
                        <h5 class="card-title">
                            <i data-feather="clock" style="width: 18px; height: 18px; margin-right: 6px; color: #01caf1; vertical-align: -2px;"></i>
                            ผลงานที่เพิ่มล่าสุด
                        </h5>
                        <p class="card-subtitle">รายการผลงาน 8 รายการล่าสุดในระบบ</p>
                    </div>
                    <div>
                        <a href="?p=item-list" class="btn btn-sm btn-outline-primary font-weight-medium">
                            ดูทั้งหมด (<?php echo number_format($total_items); ?>) <i data-feather="arrow-right" style="width: 14px; height: 14px; vertical-align: -1px;"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover recent-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">ปี</th>
                                    <th>ชื่อผลงาน</th>
                                    <th style="width: 170px;">ชุมชน / คอลเลกชัน</th>
                                    <th style="width: 80px;" class="text-center">ไฟล์</th>
                                    <th style="width: 130px;" class="text-right">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recent_items)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i data-feather="inbox" style="width: 32px; height: 32px; display: block; margin: 0 auto 8px; opacity: 0.5;"></i>
                                            ยังไม่มีรายการผลงานในระบบ
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recent_items as $item): 
                                        $it_id = dashVal($item, 'item_id');
                                        $it_title = dashVal($item, 'item_title');
                                        $it_year = dashVal($item, 'item_issued_year');
                                        $it_comm = dashVal($item, 'community_title');
                                        $it_col = dashVal($item, 'collection_name');
                                        $it_files = (int)dashVal($item, 'file_count', 0);
                                        $it_writers = dashVal($item, 'writer_names');
                                    ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-soft-primary px-2 py-1">
                                                    <?php echo !empty($it_year) ? htmlspecialchars($it_year, ENT_QUOTES, 'UTF-8') : '-'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="?p=item-edit&item_id=<?php echo urlencode($it_id); ?>" class="item-title-link" title="<?php echo htmlspecialchars($it_title, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?php echo htmlspecialchars($it_title, ENT_QUOTES, 'UTF-8'); ?>
                                                </a>
                                                <?php if (!empty($it_writers)): ?>
                                                    <small class="text-muted d-block mt-1">
                                                        <i data-feather="user" style="width: 12px; height: 12px; vertical-align: -1px;"></i>
                                                        <?php echo htmlspecialchars($it_writers, ENT_QUOTES, 'UTF-8'); ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="d-block font-weight-medium text-dark text-truncate" style="max-width: 160px;" title="<?php echo htmlspecialchars($it_comm, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?php echo !empty($it_comm) ? htmlspecialchars($it_comm, ENT_QUOTES, 'UTF-8') : '-'; ?>
                                                </small>
                                                <small class="text-muted text-truncate d-block" style="max-width: 160px;" title="<?php echo htmlspecialchars($it_col, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?php echo !empty($it_col) ? htmlspecialchars($it_col, ENT_QUOTES, 'UTF-8') : '-'; ?>
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($it_files > 0): ?>
                                                    <span class="badge badge-soft-success px-2 py-1">
                                                        <i data-feather="paperclip" style="width: 12px; height: 12px; vertical-align: -1px;"></i> <?php echo $it_files; ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-light px-2 py-1 text-muted">0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="?p=item-view&item_id=<?php echo urlencode($it_id); ?>" class="btn btn-outline-info py-1 px-2" title="ดูข้อมูลผลงาน" data-toggle="tooltip">
                                                        <i data-feather="eye" style="width: 13px; height: 13px;"></i>
                                                    </a>
                                                    <a href="?p=item-edit&item_id=<?php echo urlencode($it_id); ?>" class="btn btn-outline-primary py-1 px-2" title="แก้ไขรายการ" data-toggle="tooltip">
                                                        <i data-feather="edit-2" style="width: 13px; height: 13px;"></i>
                                                    </a>
                                                    <a href="?p=upload&item_id=<?php echo urlencode($it_id); ?>" class="btn btn-outline-success py-1 px-2" title="จัดการไฟล์แนบ" data-toggle="tooltip">
                                                        <i data-feather="upload" style="width: 13px; height: 13px;"></i>
                                                    </a>
                                                    <a href="../?p=items&item_id=<?php echo md5($it_id); ?>" target="_blank" class="btn btn-outline-secondary py-1 px-2" title="ดูหน้าเว็บ" data-toggle="tooltip">
                                                        <i data-feather="external-link" style="width: 13px; height: 13px;"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Top Communities & System Health -->
        <div class="col-lg-4">
            <!-- Widget 1: Top Communities -->
            <div class="dash-card mb-4">
                <div class="card-header">
                    <div>
                        <h5 class="card-title">
                            <i data-feather="award" style="width: 18px; height: 18px; margin-right: 6px; color: #ff8040; vertical-align: -2px;"></i>
                            ชุมชนที่มีผลงานสูงสุด
                        </h5>
                        <p class="card-subtitle">5 ชุมชนที่มีจำนวนผลงานมากที่สุด</p>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($top_communities)): ?>
                        <p class="text-muted text-center py-3 mb-0">ยังไม่มีข้อมูลชุมชน</p>
                    <?php else: ?>
                        <?php 
                        $rank = 1;
                        $first_comm = $top_communities[0];
                        $max_items = (int)dashVal($first_comm, 'item_count', 1);
                        if ($max_items <= 0) $max_items = 1;

                        foreach ($top_communities as $comm): 
                            $comm_title = dashVal($comm, 'community_title');
                            $comm_count = (int)dashVal($comm, 'item_count', 0);
                            $rank_class = ($rank == 1) ? 'rank-1' : (($rank == 2) ? 'rank-2' : (($rank == 3) ? 'rank-3' : 'rank-other'));
                            $pct = round(($comm_count / $max_items) * 100);
                        ?>
                            <div class="top-comm-item">
                                <div class="top-comm-rank <?php echo $rank_class; ?>">
                                    <?php echo $rank; ?>
                                </div>
                                <div class="flex-grow-1 pr-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="top-comm-name text-truncate" style="max-width: 170px;" title="<?php echo htmlspecialchars($comm_title, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo htmlspecialchars($comm_title, ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                        <span class="badge badge-soft-primary"><?php echo number_format($comm_count); ?> รายการ</span>
                                    </div>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $pct; ?>%;" aria-valuenow="<?php echo $pct; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            $rank++;
                        endforeach; 
                        ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Widget 2: System & Server Health Status -->
            <div class="dash-card">
                <div class="card-header">
                    <div>
                        <h5 class="card-title">
                            <i data-feather="server" style="width: 18px; height: 18px; margin-right: 6px; color: #6c757d; vertical-align: -2px;"></i>
                            สถานะระบบและเซิร์ฟเวอร์
                        </h5>
                        <p class="card-subtitle">ข้อมูลการทำงานของระบบคลังสารสนเทศ</p>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted"><i data-feather="folder" style="width: 14px; height: 14px; margin-right: 4px;"></i> ที่เก็บไฟล์ (files/)</span>
                            <?php if ($is_files_writable): ?>
                                <span class="badge badge-soft-success">
                                    <span class="status-dot active"></span> พร้อมใช้งาน (เขียนได้)
                                </span>
                            <?php else: ?>
                                <span class="badge badge-soft-warning">
                                    <span class="status-dot danger"></span> ต้องตั้งค่าสิทธิ์โฟลเดอร์
                                </span>
                            <?php endif; ?>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted"><i data-feather="cpu" style="width: 14px; height: 14px; margin-right: 4px;"></i> PHP Version</span>
                            <span class="font-weight-bold text-dark"><?php echo htmlspecialchars($php_version, ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted"><i data-feather="database" style="width: 14px; height: 14px; margin-right: 4px;"></i> ฐานข้อมูล</span>
                            <span class="badge badge-soft-info">MySQL / MariaDB</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2">
                            <span class="text-muted"><i data-feather="layout" style="width: 14px; height: 14px; margin-right: 4px;"></i> หน้าแรกเว็บไซต์</span>
                            <a href="?p=home-page" class="btn btn-xs btn-outline-primary py-0 px-2 font-weight-medium">
                                จัดการข้อมูล <i data-feather="arrow-right" style="width: 12px; height: 12px;"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Pass PHP Data cleanly to Frontend Charts -->
<script>
    window.DASH_DATA = {
        yearsLabels: <?php echo json_encode($years_labels); ?>,
        yearsData: <?php echo json_encode($years_data); ?>,
        communityLabels: <?php echo json_encode($community_labels); ?>,
        communityData: <?php echo json_encode($community_data); ?>
    };
</script>

<!-- Load Chart.js (CDN first, local bundle fallback) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.bundle.min.js"></script>
<script>
    if (typeof Chart === 'undefined') {
        document.write('<script src="assets/chart.js/Chart.bundle.min.js"><\/script>');
    }
</script>

<!-- Dashboard Script -->
<script src="pages/home/script.js"></script>