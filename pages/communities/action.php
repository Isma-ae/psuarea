<?php
	session_start();
    
	include("../../php/functions.php");
    $fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
	switch ($fn) {
        case 'load_community'	: echo load_community(); 	break;
        case 'load_collection'	: echo load_collection(); 	break;
		default: break;
	}

    function load_community() {
        global $DATABASE;
		$sql = "SELECT * FROM tb_community WHERE MD5(community_id) = '".$DATABASE->Escape($_POST["community_id"])."'";
		$return['data'] = $DATABASE->QueryObj($sql);
        return json_encode( $return );
    }

    function load_collection() {
        global $DATABASE;
        
        $limit = 10;
        $page = isset($_POST["page"]) && $_POST["page"] > 1 ? (int)$_POST["page"] : 1;
        $start = ($page - 1) * $limit;
        $community_id = $DATABASE->Escape($_POST["community_id"]);
        $total_sql = "SELECT COUNT(DISTINCT tb_collection.collection_id) AS total FROM tb_collection
            LEFT JOIN tb_item ON tb_item.collection_id = tb_collection.collection_id 
                              AND MD5(tb_item.community_id) = '$community_id'";
    
        $total_obj = $DATABASE->QueryObj($total_sql);
        $total_data = !empty($total_obj) ? $total_obj[0]['total'] : 0;
        $total_pages = ceil($total_data / $limit);
        $sql = "SELECT MD5(tb_collection.collection_id) AS collection_id, tb_collection.collection_name, COUNT(tb_item.item_id) AS count_collection 
                FROM tb_collection
                LEFT JOIN tb_item ON tb_item.collection_id = tb_collection.collection_id 
                                  AND MD5(tb_item.community_id) = '$community_id'
                GROUP BY tb_collection.collection_id, tb_collection.collection_name
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
    