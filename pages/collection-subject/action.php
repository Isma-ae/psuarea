<?php
	session_start();
    
	include("../../php/functions.php");
    $fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
    switch ($fn) {
        case 'load_collection'	: echo load_collection(); 	break;
        case 'load_community'	: echo load_community(); 	break;
        case 'load_subject'	    : echo load_subject(); 	    break;
		default: break;
	}

    function load_collection() {
        global $DATABASE;
        $sql = "SELECT * FROM tb_collection WHERE MD5(collection_id) = '".$DATABASE->Escape($_POST["collection_id"])."'";
        $response = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function load_community() {
        global $DATABASE;
        $sql = "SELECT * FROM tb_community WHERE MD5(community_id) = '".$DATABASE->Escape($_POST["community_id"])."'";
        $response = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function load_subject() {
        global $DATABASE;
        $limit = 10;
        $page = isset($_POST["page"]) && $_POST["page"] > 1 ? (int)$_POST["page"] : 1;
        $start = ($page - 1) * $limit;
        $community_id = $DATABASE->Escape($_POST["community_id"]);
        $collection_id = $DATABASE->Escape($_POST["collection_id"]);
        $search_query = $DATABASE->Escape($_POST["search_query"]);
        $search_filter = "WHERE 1=1";
        
        if (!empty($community_id)) {
            $search_filter .= " AND MD5(community_id) = '$community_id'";
        }

        if (!empty($collection_id)) {
            $search_filter .= " AND MD5(collection_id) = '$collection_id'";
        }

        if (!empty($search_query)) {
            $search_filter .= " AND subject_name LIKE '%$search_query%'";
        }
    
        $total_sql = "SELECT COUNT(subject_id) AS count_subject 
                      FROM tb_subject
                      INNER JOIN tb_item ON tb_subject.item_id = tb_item.item_id
                      $search_filter";
    
        $total_obj = $DATABASE->QueryObj($total_sql);
        $total_data = isset($total_obj[0]['count_subject']) ? $total_obj[0]['count_subject'] : 0;
        $total_pages = ($total_data > 0) ? ceil($total_data / $limit) : 1;
        $sql = "SELECT
                    COUNT(*) AS count_subject,
                    subject_name
                FROM tb_subject
                INNER JOIN tb_item ON tb_subject.item_id = tb_item.item_id
                $search_filter
                GROUP BY subject_name
                LIMIT $start, $limit";
    
        $obj = $DATABASE->QueryObj($sql);
    
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
            'pagination_info' => $pagination_info,
            'total_data' => $total_data
        ]);
    }
    