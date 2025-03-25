<?php
	session_start();
    
	include("../../php/functions.php");
    $fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";

    $limit = 10;
    $page = isset($_POST["page"]) && $_POST["page"] > 1 ? (int)$_POST["page"] : 1;
    $start = ($page - 1) * $limit;
    
    $community_id = $_POST["community_id"];
    $item_issued_year = $_POST["item_issued_year"];
    $item_issued_month = $_POST["item_issued_month"];
    $item_issued_day = $_POST["item_issued_day"];

    $search_query = "WHERE 1=1";
    
    if (!empty($community_id)) {
        $search_query .= " AND MD5(i.community_id) = '$community_id'";
    }

    if (!empty($item_issued_year)) {
        $search_query .= " AND i.item_issued_year = '$item_issued_year'";
    }

    if (!empty($item_issued_month)) {
        $search_query .= " AND i.item_issued_month = '$item_issued_month'";
    }

    if (!empty($item_issued_day)) {
        $search_query .= " AND i.item_issued_day = '$item_issued_day'";
    }

    // คำนวณจำนวนทั้งหมด
    $total_query = "SELECT COUNT(DISTINCT i.item_id) AS total FROM tb_item AS i $search_query";
    $total_result = $DATABASE->QueryObj($total_query);
    $total_data = $total_result[0]['total'] ?? 0;
    $total_pages = ceil($total_data / $limit);

    // คำสั่ง SQL สำหรับดึงข้อมูล
    $query = "SELECT 
                i.item_id,
                MD5(i.item_id) AS item_id_md5,
                i.item_title, 
                i.item_issued_year,
                i.item_publisher,
                i.item_abstract,
                GROUP_CONCAT(DISTINCT CONCAT('<a href=\"?p=search&search_term=', w.writer_prefix, w.writer_fname, ' ', w.writer_lname, '\">', w.writer_prefix, w.writer_fname, ' ', w.writer_lname, '</a>') 
                ORDER BY w.writer_main SEPARATOR ', ') AS writer_names,
                GROUP_CONCAT(DISTINCT s.subject_name ORDER BY s.subject_name SEPARATOR ', ') AS subject_names,
                co.file_name AS cover_name
            FROM tb_item AS i
            LEFT JOIN tb_writer AS w ON i.item_id = w.item_id
            LEFT JOIN tb_subject AS s ON i.item_id = s.item_id
            LEFT JOIN tb_file AS co ON i.item_id = co.item_id AND co.file_type = 'cover'
            $search_query
            GROUP BY i.item_id
            ORDER BY i.item_id DESC
            LIMIT $start, $limit";

    $obj = $DATABASE->QueryObj($query);

    // สร้าง Pagination
    $pagination_html = '<div align="center"><ul class="pagination">';

    if ($page > 1) {
        $pagination_html .= '<li class="page-item prev"><a class="page-link" href="#" data-page="' . ($page - 1) . '"><i class="ti-angle-left"></i></a></li>';
    } else {
        $pagination_html .= '<li class="page-item prev disabled"><a class="page-link" href="#"><i class="ti-angle-left"></i></a></li>';
    }

    for ($count = 1; $count <= $total_pages; $count++) {
        $active = $count == $page ? ' active' : '';
        $pagination_html .= '<li class="page-item' . $active . '"><a class="page-link" href="#" data-page="' . $count . '">' . $count . '</a></li>';
    }

    if ($page < $total_pages) {
        $pagination_html .= '<li class="page-item next"><a class="page-link" href="#" data-page="' . ($page + 1) . '"><i class="ti-angle-right"></i></a></li>';
    } else {
        $pagination_html .= '<li class="page-item next disabled"><a class="page-link" href="#"><i class="ti-angle-right"></i></a></li>';
    }

    $pagination_html .= '</ul></div>';

    $showing_from = ($start + 1);
    $showing_to = ($start + count($obj));
    $pagination_info = "Now showing $showing_from - $showing_to of $total_data";

    echo json_encode([
        'data' => $obj,
        'pagination' => $pagination_html,
        'pagination_info' => $pagination_info
    ]);