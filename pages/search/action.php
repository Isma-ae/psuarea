<?php
	session_start();
    
	include("../../php/functions.php");
    $fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
	switch ($fn) {
        case 'load_date'	    : echo load_date(); 	    break;
        case 'load_authur'	    : echo load_authur(); 	    break;
        case 'load_subject'	    : echo load_subject(); 	    break;
        case 'load_item'	    : echo load_item(); 	    break;
        case 'search_author'	: echo search_author(); 	break;
        case 'search_subject'	: echo search_subject(); 	break;
		default: break;
	}

    function load_date()  {
        global $DATABASE;
        $sql = "SELECT MAX(item_issued_year) AS max_year, MIN(item_issued_year) AS min_year FROM tb_item";
        $response['data'] = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }


    function search_author() {
        global $DATABASE;
        $search = $DATABASE->Escape($_POST['search']);
        $sql = "SELECT writer_fname, writer_lname, COUNT(DISTINCT CONCAT(writer_fname, ' ', writer_lname)) AS count_author
            FROM tb_writer
            WHERE writer_fname LIKE '%$search%'
                OR writer_lname LIKE '%$search%'
                OR CONCAT(writer_fname, ' ', writer_lname) LIKE '%$search%'
            GROUP BY writer_fname, writer_lname
            ORDER BY writer_fname, writer_lname
        ";
        $data = $DATABASE->QueryObj($sql);
        $response = array();
        $response['data'] = $data;
        return json_encode($response);
    }
    function search_subject() {
        global $DATABASE;
        $search = $DATABASE->Escape($_POST['search']);
        $sql = "SELECT subject_name, COUNT(subject_name) AS count_subject
            FROM tb_subject
            WHERE subject_name  LIKE '%$search%'
            GROUP BY subject_name
            ORDER BY subject_name
        ";
        $data = $DATABASE->QueryObj($sql);
        $response = array();
        $response['data'] = $data;
        return json_encode($response);
    }

    function load_authur() {
        global $DATABASE;
        $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 5;
        $author_in_param = $DATABASE->Escape($_POST['author_in_param']);
        $filter = "";
        if (!empty($author_in_param) && $author_in_param != null) {
            $filter = "WHERE CONCAT(writer_fname, ' ', writer_lname) <> '$author_in_param'";
        }
        $sql = "SELECT writer_fname, writer_lname, COUNT(DISTINCT CONCAT(writer_fname, ' ', writer_lname)) AS count_author
            FROM tb_writer 
            $filter
            GROUP BY writer_fname, writer_lname
            ORDER BY writer_fname, writer_lname
        ";
        $data = $DATABASE->QueryObj($sql." LIMIT $limit OFFSET $offset");
        $all = $DATABASE->QueryNumRow($sql);
        $response = array();
        $response['data'] = $data;
        $response['all'] = $all;
        return json_encode($response);
    }

    function load_subject() {
        global $DATABASE;
        $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;
        $subject_in_param = $DATABASE->Escape($_POST['subject_in_param']);
        $filter = "";
        if (!empty($subject_in_param) && $subject_in_param != null) {
            $filter = "WHERE subject_name <> '$subject_in_param'";
        }
        $sql = "SELECT subject_name, COUNT(subject_name) AS count_subject
            FROM tb_subject
            $filter
            GROUP BY subject_name
            ORDER BY subject_id
            LIMIT $limit OFFSET $offset
        ";
        $response = array();
        $response['data'] = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function load_item() {
        global $DATABASE;
        
        $limit = isset($_POST["limit"]) ? (int)$_POST["limit"] : 10;
        $page = isset($_POST["page"]) && $_POST["page"] > 1 ? (int)$_POST["page"] : 1;
        $start = ($page - 1) * $limit;
        
        $query_param = isset($_POST["query"]) ? trim($DATABASE->Escape($_POST["query"])) : "";
        $writers = isset($_POST["writer"]) ? json_decode($_POST["writer"], true) : [];
        $subjects = isset($_POST["subject"]) ? json_decode($_POST["subject"], true) : [];
    
        $search_query = "WHERE 1=1";
        
        // ค้นหาตามคำค้น
        if (!empty($query_param)) {
            $query_param = $DATABASE->Escape($query_param);
            $search_query .= " AND (i.item_title LIKE '%$query_param%' 
                                OR i.item_abstract LIKE '%$query_param%'
                                OR EXISTS (
                                    SELECT 1 FROM tb_writer w 
                                    WHERE w.item_id = i.item_id 
                                    AND CONCAT(w.writer_fname, ' ', w.writer_lname) LIKE '%$query_param%'
                                )
                                OR EXISTS (
                                    SELECT 1 FROM tb_subject s
                                    WHERE s.item_id = i.item_id 
                                    AND s.subject_name LIKE '%$query_param%'
                                ))";
        }
    
        // ค้นหาตามชื่อ-นามสกุลของ writer
        if (!empty($writers)) {
            $writer_conditions = [];
            foreach ($writers as $writer) {
                $writer = $DATABASE->Escape($writer);
                $writer_conditions[] = "(CONCAT(w.writer_fname, ' ', w.writer_lname) LIKE '%$writer%')";
            }
            if (!empty($writer_conditions)) {
                $search_query .= " AND EXISTS (
                    SELECT 1 FROM tb_writer w 
                    WHERE w.item_id = i.item_id 
                    AND (" . implode(" OR ", $writer_conditions) . ")
                )";
            }
        }
    
        // ค้นหาตามช่วงปี
        $min_year = isset($_POST["min_year"]) ? (int)$DATABASE->Escape($_POST["min_year"]) : null;
        $max_year = isset($_POST["max_year"]) ? (int)$DATABASE->Escape($_POST["max_year"]) : null;
        if (!empty($min_year) && !empty($max_year)) {
            $search_query .= " AND (i.item_issued_year BETWEEN '$min_year' AND '$max_year')";
        }
    
        // ตรวจสอบไฟล์
        if ($DATABASE->Escape($_POST["has_file"]) == 'y') {
            $search_query .= " AND (EXISTS (
                                    SELECT 1 FROM tb_file fi
                                    WHERE i.item_id = fi.item_id 
                                    AND fi.file_type = 'file' AND fi.file_name <> ''
                                )) ";
        }
    
        // ค้นหาตามชื่อ subject
        if (!empty($subjects)) {
            $subject_conditions = [];
            foreach ($subjects as $subject) {
                $subject = $DATABASE->Escape($subject);
                $subject_conditions[] = "(s.subject_name LIKE '%$subject%')";
            }
            if (!empty($subject_conditions)) {
                $search_query .= " AND EXISTS (
                    SELECT 1 FROM tb_subject s
                    WHERE s.item_id = i.item_id 
                    AND (" . implode(" OR ", $subject_conditions) . ")
                )";
            }
        }
        $order_by = $DATABASE->Escape($_POST["order_by"]);
        if($order_by == 1) {
            $order = "i.item_id DESC";
        } elseif ($order_by == 2) {
            $order = "i.item_id";
        } elseif ($order_by == 3) {
            $order = "i.item_title";
        } elseif ($order_by == 4) {
            $order = "i.item_title DESC";
        } elseif ($order_by == 5) {
            $order = "i.item_issued_year";
        } elseif ($order_by == 6) {
            $order = "i.item_issued_year DESC";
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
                ORDER BY $order
                LIMIT $start, $limit";
    
        $obj = $DATABASE->QueryObj($query);
        if (!empty($obj) && is_array($obj)) {
            foreach ($obj as $k => $item) {
                $raw = isset($item['item_abstract']) ? $item['item_abstract'] : '';
                $clean = strip_tags($raw);
                $clean = html_entity_decode($clean, ENT_QUOTES, 'UTF-8');
                $clean = trim(preg_replace('/\s+/', ' ', $clean));
                if (mb_strlen($clean, 'UTF-8') > 350) {
                    $clean = mb_substr($clean, 0, 350, 'UTF-8') . '...';
                }
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
            'sql' => $query,
            'data' => $obj,
            'pagination' => $pagination_html,
            'pagination_info' => $pagination_info
        ]);
    }
    