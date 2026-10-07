<?php
	session_start();
    
	include("../../php/functions.php");
    $fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
    switch ($fn) {
        case 'load_collection'	: echo load_collection(); 	break;
        case 'load_community'	: echo load_community(); 	break;
        case 'load_year'	    : echo load_year(); 	    break;
        case 'load_item'	    : echo load_item(); 	    break;
		default: break;
	}

    function load_collection() {
        global $DATABASE;
        $collection_id = $DATABASE->Escape($_POST["collection_id"]);
        $sql = "SELECT * FROM tb_collection WHERE MD5(collection_id) = '$collection_id'";
        $response = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function load_community() {
        global $DATABASE;
        $community_id = $DATABASE->Escape($_POST["community_id"]);
        $sql = "SELECT * FROM tb_community WHERE MD5(community_id) = '$community_id'";
        $response = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function load_year() {
        global $DATABASE;
        $sql = "SELECT item_issued_year FROM tb_item GROUP BY item_issued_year ORDER BY item_issued_year DESC";
        $response = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function load_item() {
        global $DATABASE;
        $limit = 10;
        $page = isset($_POST["page"]) && $_POST["page"] > 1 ? (int)$_POST["page"] : 1;
        $start = ($page - 1) * $limit;
        
        $community_id = $DATABASE->Escape($_POST["community_id"]);
        $collection_id = $DATABASE->Escape($_POST["collection_id"]);
        $year_start = !empty($_POST["year_start"]) && $_POST["year_start"] != 'null' ? $DATABASE->Escape($_POST["year_start"]) : '0';
        $year_end = !empty($_POST["year_end"]) && $_POST["year_end"] != 'null' ? $DATABASE->Escape($_POST["year_end"]) : '9223372036854775807';

        $search_query = "WHERE i.item_issued_year BETWEEN '$year_start ' AND '$year_end'";

        if (!empty($collection_id)) {
            $search_query .= " AND MD5(i.collection_id) = '$collection_id'";
        }
        
        if (!empty($community_id)) {
            $search_query .= " AND MD5(i.community_id) = '$community_id'";
        }

        // คำนวณจำนวนทั้งหมด
        $total_query = "SELECT COUNT(DISTINCT i.item_id) AS total FROM tb_item AS i $search_query";
        $total_result = $DATABASE->QueryObj($total_query);
        $total_data = isset($total_result[0]['total']) ? $total_result[0]['total'] : 0;
        $total_pages = ceil($total_data / $limit);

        // คำสั่ง SQL สำหรับดึงข้อมูล
        $query = "SELECT 
                    i.item_id,
                    MD5(i.item_id) AS item_id_md5,
                    i.item_title, 
                    i.item_issued_year,
                    i.item_publisher,
                    i.item_abstract,
                    GROUP_CONCAT(DISTINCT CONCAT('<a href=\"?p=search&author_name=', w.writer_fname, ' ', w.writer_lname, '\">', w.writer_fname, ' ', w.writer_lname, '</a>') 
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
        if (!empty($obj) && is_array($obj)) {
            foreach ($obj as $k => $item) {
                $raw = isset($item['item_abstract']) ? $item['item_abstract'] : '';
                $clean = strip_tags($raw);
                $clean = html_entity_decode($clean, ENT_QUOTES, 'UTF-8');
                $clean = trim(preg_replace('/\s+/', ' ', $clean));
                $obj[$k]['item_abstract'] = $clean;
                $obj[$k]['item_abstract_clean'] = $clean;
            }
        }

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

        return json_encode([
            'data' => $obj,
            'pagination' => $pagination_html,
            'pagination_info' => $pagination_info
        ]);
    }