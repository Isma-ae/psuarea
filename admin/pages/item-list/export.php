<?php
session_start();

if (!isset($_SESSION["user_name"])) {
    header("Location: ../../login/");
    exit();
}

include("../../../php/functions.php");

$format = isset($_GET["format"]) ? strtolower(trim($_GET["format"])) : "excel";
$query = isset($_GET["query"]) ? trim($_GET["query"]) : "";

$where = "";
if (!empty($query)) {
    $clean_q = addslashes($query);
    $where = " WHERE (i.item_title LIKE '%$clean_q%' 
                OR c.community_title LIKE '%$clean_q%' 
                OR col.collection_name LIKE '%$clean_q%' 
                OR i.item_issued_year LIKE '%$clean_q%'
                OR EXISTS (
                    SELECT 1 FROM tb_writer w2 
                    WHERE w2.item_id = i.item_id 
                    AND (w2.writer_fname LIKE '%$clean_q%' OR w2.writer_lname LIKE '%$clean_q%')
                ))";
}

$sql = "SELECT 
            i.item_id, 
            i.item_title, 
            i.item_issued_year,
            IFNULL(c.community_title, '-') AS community_title,
            IFNULL(col.collection_name, '-') AS collection_name,
            GROUP_CONCAT(DISTINCT CONCAT(w.writer_fname, ' ', w.writer_lname) 
                ORDER BY w.writer_main ASC, w.writer_fname ASC SEPARATOR ', ') AS writer_names
        FROM tb_item AS i
        LEFT JOIN tb_community AS c ON i.community_id = c.community_id
        LEFT JOIN tb_collection AS col ON i.collection_id = col.collection_id
        LEFT JOIN tb_writer AS w ON i.item_id = w.item_id
        $where
        GROUP BY 
            i.item_id, 
            i.item_title, 
            i.item_issued_year,
            c.community_title,
            col.collection_name
        ORDER BY i.item_id DESC";

$data = $DATABASE->QueryObj($sql);

$user_full_name = isset($_SESSION["user_fname"]) && !empty($_SESSION["user_fname"]) 
    ? $_SESSION["user_fname"] . " " . (isset($_SESSION["user_lname"]) ? $_SESSION["user_lname"] : "")
    : $_SESSION["user_name"];

$thai_year = date('Y') + 543;
$export_date = date('d/m/') . $thai_year . ' เวลา ' . date('H:i') . ' น.';
$file_timestamp = date('Y-m-d_His');

// ==========================================
// 1. FORMAT: CSV (.csv)
// ==========================================
if ($format === 'csv') {
    $filename = "export_items_" . $file_timestamp . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF"); // UTF-8 BOM
    
    $headers = ['ลำดับ', 'รหัสผลงาน', 'ชื่อรายการผลงาน', 'ขอบเขตเนื้อหา', 'คอลเลกชัน', 'ปีที่พิมพ์', 'รายชื่อผู้แต่ง'];
    fputcsv($output, $headers);
    
    $no = 1;
    foreach ($data as $row) {
        $writer_names = (!empty($row['writer_names'])) ? $row['writer_names'] : '-';
        $community = (!empty($row['community_title'])) ? $row['community_title'] : '-';
        $collection = (!empty($row['collection_name'])) ? $row['collection_name'] : '-';
        $year = (!empty($row['item_issued_year'])) ? $row['item_issued_year'] : '-';
        
        fputcsv($output, [
            $no++,
            $row['item_id'],
            $row['item_title'],
            $community,
            $collection,
            $year,
            $writer_names
        ]);
    }
    fclose($output);
    exit;
}

// ==========================================
// 2. FORMAT: EXCEL (.xls)
// ==========================================
if ($format === 'excel') {
    $filename = "export_items_" . $file_timestamp . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo "\xEF\xBB\xBF"; // UTF-8 BOM
    ?>
    <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!--[if gte mso 9]>
        <xml>
            <x:ExcelWorkbook>
                <x:ExcelWorksheets>
                    <x:ExcelWorksheet>
                        <x:Name>รายการผลงาน PSU AREA</x:Name>
                        <x:WorksheetOptions>
                            <x:DisplayGridlines/>
                        </x:WorksheetOptions>
                    </x:ExcelWorksheet>
                </x:ExcelWorksheets>
            </x:ExcelWorkbook>
        </xml>
        <![endif]-->
        <style>
            body { font-family: 'Angsana New', 'Cordia New', Tahoma, sans-serif; font-size: 14pt; }
            table { border-collapse: collapse; width: 100%; }
            th { 
                background-color: #004d99; 
                color: #ffffff; 
                border: 1px solid #003366; 
                font-weight: bold; 
                padding: 8px 10px; 
                text-align: center; 
                vertical-align: middle;
            }
            td { 
                border: 1px solid #cccccc; 
                padding: 6px 8px; 
                vertical-align: top; 
            }
            .text-center { text-align: center; }
            .num-text { mso-number-format: "\@"; }
            .title-cell { font-size: 16pt; font-weight: bold; color: #003366; padding-bottom: 5px; }
            .info-cell { font-size: 12pt; color: #555555; padding-bottom: 15px; }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <td colspan="7" class="title-cell">รายงานรายการผลงาน - ระบบคลังสารสนเทศดิจิทัล PSU AREA</td>
            </tr>
            <tr>
                <td colspan="7" class="info-cell">
                    วันที่ส่งออก: <?php echo $export_date; ?> | 
                    ผู้ส่งออก: <?php echo htmlspecialchars($user_full_name, ENT_QUOTES, 'UTF-8'); ?> | 
                    จำนวนทั้งหมด: <?php echo number_format(count($data)); ?> รายการ
                    <?php if (!empty($query)): ?> | เงื่อนไขค้นหา: "<?php echo htmlspecialchars($query, ENT_QUOTES, 'UTF-8'); ?>"<?php endif; ?>
                </td>
            </tr>
            <thead>
                <tr>
                    <th style="width: 60px;">ลำดับ</th>
                    <th style="width: 140px;">รหัสผลงาน</th>
                    <th style="width: 320px;">ชื่อรายการผลงาน</th>
                    <th style="width: 200px;">ขอบเขตเนื้อหา (Community)</th>
                    <th style="width: 200px;">คอลเลกชัน (Collection)</th>
                    <th style="width: 90px;">ปีที่พิมพ์</th>
                    <th style="width: 260px;">รายชื่อผู้แต่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($data as $row): 
                    $writer_names = !empty($row['writer_names']) ? $row['writer_names'] : '-';
                    $community = !empty($row['community_title']) ? $row['community_title'] : '-';
                    $collection = !empty($row['collection_name']) ? $row['collection_name'] : '-';
                    $year = !empty($row['item_issued_year']) ? $row['item_issued_year'] : '-';
                ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td class="text-center num-text"><?php echo htmlspecialchars($row['item_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['item_title'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($community, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($collection, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="text-center num-text"><?php echo htmlspecialchars($year, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($writer_names, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php
    exit;
}

// ==========================================
// 3. FORMAT: PDF / PRINT VIEW (.pdf)
// ==========================================
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานรายการผลงาน (PSU AREA) - <?php echo date('Ymd_His'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../admin/dist/css/style.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Sarabun', 'TH Sarabun New', Tahoma, sans-serif;
            background-color: #f4f6f9;
            color: #222;
            margin: 0;
            padding: 0;
            font-size: 13px;
            line-height: 1.5;
        }
        .top-action-bar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 12px 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .report-page-container {
            max-width: 1200px;
            margin: 25px auto;
            background: #ffffff;
            padding: 30px 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-radius: 6px;
        }
        .report-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 18px;
            border-bottom: 2px solid #004d99;
            margin-bottom: 18px;
        }
        .header-left {
            display: flex;
            align-items: center;
        }
        .header-logo {
            height: 52px;
            margin-right: 18px;
        }
        .header-text h3 {
            font-size: 20px;
            font-weight: 700;
            color: #003366;
            margin: 0 0 3px 0;
        }
        .header-text p {
            font-size: 12px;
            color: #666;
            margin: 0;
        }
        .header-right {
            text-align: right;
            font-size: 12px;
            color: #555;
        }
        .meta-strip {
            display: flex;
            justify-content: space-between;
            background-color: #f7f9fc;
            border: 1px solid #e3e8ee;
            border-radius: 4px;
            padding: 8px 14px;
            margin-bottom: 18px;
            font-size: 12px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .report-table th {
            background-color: #004d99;
            color: #ffffff;
            border: 1px solid #003366;
            padding: 8px 6px;
            text-align: center;
            font-weight: 600;
            white-space: nowrap;
        }
        .report-table td {
            border: 1px solid #d0d7de;
            padding: 6px 8px;
            vertical-align: top;
        }
        .report-table tbody tr:nth-child(even) {
            background-color: #fafbfc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-weight-bold { font-weight: 600; }
        
        .badge-scope {
            display: inline-block;
            background-color: #e8f4fd;
            color: #0d6efd;
            border-radius: 3px;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: 500;
        }
        .badge-col {
            display: inline-block;
            background-color: #f0f7ed;
            color: #2e7d32;
            border-radius: 3px;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .report-footer {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #777;
        }

        @page {
            size: A4 landscape;
            margin: 10mm 12mm 10mm 12mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 11px !important;
            }
            .report-page-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            .report-table {
                page-break-inside: auto;
            }
            .report-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            .report-table thead {
                display: table-header-group;
            }
            .report-table th {
                background-color: #004d99 !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-scope, .badge-col {
                background: none !important;
                padding: 0 !important;
                color: #000 !important;
                border: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- TOP ACTION BAR (Hidden when printing/PDF) -->
    <div class="top-action-bar no-print">
        <div class="d-flex align-items-center">
            <button onclick="window.print()" class="btn btn-primary mr-2" style="font-weight: 600; padding: 7px 18px;">
                <i class="fas fa-print mr-1"></i> พิมพ์ / บันทึกเป็น PDF
            </button>
            <a href="export.php?format=excel<?php echo !empty($query) ? '&query='.urlencode($query) : ''; ?>" class="btn btn-outline-success mr-2" style="padding: 7px 14px;">
                <i class="far fa-file-excel mr-1"></i> ดาวน์โหลดเป็น Excel (.xls)
            </a>
            <button onclick="window.close()" class="btn btn-outline-secondary" style="padding: 7px 14px;">
                <i class="fas fa-times mr-1"></i> ปิดหน้าต่าง
            </button>
        </div>
        <div class="text-muted" style="font-size: 13px;">
            <i class="fas fa-info-circle text-info mr-1"></i>
            คำแนะนำ: ในหน้าพิมพ์ ให้เลือกปลายทางเป็น <strong>"Save as PDF" (บันทึกเป็น PDF)</strong> และวางแนวเป็น <strong>"แนวนอน (Landscape)"</strong>
        </div>
    </div>

    <!-- MAIN REPORT CONTAINER -->
    <div class="report-page-container">
        <!-- HEADER -->
        <div class="report-header">
            <div class="header-left">
                <img src="../../../img/Agricultural.png" alt="Logo" class="header-logo" onerror="this.style.display='none'">
                <div class="header-text">
                    <h3>รายงานรายการผลงาน (Item List Report)</h3>
                    <p>ระบบคลังสารสนเทศดิจิทัล PSU AREA | สำนักทรัพยากรการเรียนรู้คุณหญิงหลง อรรถกระวีสุนทร มหาวิทยาลัยสงขลานครินทร์</p>
                </div>
            </div>
            <div class="header-right">
                <div><strong>วันที่ออกรายงาน:</strong> <?php echo $export_date; ?></div>
                <div><strong>ผู้จัดทำรายงาน:</strong> <?php echo htmlspecialchars($user_full_name, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        </div>

        <!-- META BAR -->
        <div class="meta-strip">
            <div>
                <strong>จำนวนรายการทั้งหมด:</strong> <?php echo number_format(count($data)); ?> รายการ
            </div>
            <?php if (!empty($query)): ?>
            <div>
                <strong>เงื่อนไขการค้นหา:</strong> "<?php echo htmlspecialchars($query, ENT_QUOTES, 'UTF-8'); ?>"
            </div>
            <?php endif; ?>
            <div>
                <strong>สถานะ:</strong> ข้อมูลล่าสุดในระบบ
            </div>
        </div>

        <!-- TABLE -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 45px;">#</th>
                    <th style="width: 95px;">รหัสผลงาน</th>
                    <th>ชื่อรายการผลงาน</th>
                    <th style="width: 175px;">ขอบเขตเนื้อหา (Community)</th>
                    <th style="width: 165px;">คอลเลกชัน (Collection)</th>
                    <th style="width: 60px;">ปี</th>
                    <th style="width: 200px;">รายชื่อผู้แต่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php 
                    $no = 1; 
                    foreach ($data as $row): 
                        $writer_names = (!empty($row['writer_names'])) ? $row['writer_names'] : '-';
                        $community = (!empty($row['community_title'])) ? $row['community_title'] : '-';
                        $collection = (!empty($row['collection_name'])) ? $row['collection_name'] : '-';
                        $year = (!empty($row['item_issued_year'])) ? $row['item_issued_year'] : '-';
                    ?>
                    <tr>
                        <td class="text-center font-weight-bold"><?php echo $no++; ?></td>
                        <td class="text-center font-weight-bold" style="color: #004d99;"><?php echo htmlspecialchars($row['item_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><strong><?php echo htmlspecialchars($row['item_title'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                        <td><span class="badge-scope"><?php echo htmlspecialchars($community, ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td><span class="badge-col"><?php echo htmlspecialchars($collection, ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td class="text-center"><?php echo htmlspecialchars($year, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($writer_names, ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 30px; color: #888;">
                            ไม่พบข้อมูลรายการผลงานตามเงื่อนไขที่ระบุ
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="report-footer">
            <div>PSU AREA - Prince of Songkla University Institutional Repository</div>
            <div>หน้า 1 / 1 (จัดพิมพ์โดยอัตโนมัติจากระบบ)</div>
        </div>
    </div>

    <script>
        // Trigger print dialog automatically after page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>