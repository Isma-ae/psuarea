<?php
// 1. Sample Data (This could come from a database query)
include("../../../php/functions.php");
$sql = "SELECT 
                i.item_id, 
                i.item_title, 
                i.item_issued_year,
                GROUP_CONCAT(DISTINCT CONCAT(w.writer_fname, ' ', w.writer_lname) 
                    ORDER BY w.writer_fname SEPARATOR ', ') AS writer_names
            FROM tb_item AS i
            LEFT JOIN tb_writer AS w ON i.item_id = w.item_id
            GROUP BY 
                i.item_id, 
                i.item_title, 
                i.item_issued_year";
                
    // 4. สั่ง Query ข้อมูล
$data = $DATABASE->QueryObj($sql);
// 2. Set headers to force download
$filename = "export_books_" . date('Y-m-d_H-i-s') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// 3. Open the output stream (acts like a file pointer to the browser)
$output = fopen('php://output', 'w');
fwrite($output, "\xEF\xBB\xBF");
$headers = ['รหัส', 'รายการ', 'ปีที่พิมพ์','รายชื่อผู้แต่ง'];
fputcsv($output, $headers);

// 4. Loop through the data and write to the stream
foreach ($data as $row) {
    $writer_names = (isset($row['writer_names']) && $row['writer_names'] !== null) ? $row['writer_names'] : '-';
    fputcsv($output, [
        $row['item_id'],
        $row['item_title'],
        $row['item_issued_year'],
        $writer_names // ถ้าไม่มีผู้แต่งให้ใส่เครื่องหมาย -
    ]);
}

// 5. Close the stream
fclose($output);
exit;
?>