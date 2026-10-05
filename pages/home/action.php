<?php
	session_start();
    
	include("../../php/functions.php");
    $fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
	switch ($fn) {
        case 'load_page'		: echo load_page(); 		break;
        case 'load_community'	: echo load_community(); 	break;
        case 'load_item'	    : echo load_item(); 	    break;
		default: break;
	}

    function load_page() {
        global $DATABASE;
		$sql = "SELECT * FROM tb_page";
		$return['data'] = $DATABASE->QueryObj($sql);
        return json_encode( $return );
    }

    function load_community() {
        global $DATABASE;
        $limit = 8;
        $page = isset($_POST["page"]) && $_POST["page"] > 1 ? (int)$_POST["page"] : 1;
        $start = ($page - 1) * $limit;

        $total_sql = "SELECT COUNT(community_id) AS total FROM tb_community";
        $total_obj = $DATABASE->QueryObj($total_sql);
        $total_data = !empty($total_obj) ? $total_obj[0]['total'] : 0;
        $total_pages = ceil($total_data / $limit);
        $sql = "SELECT MD5(community_id) AS community_id, community_title, community_description, community_img
                FROM tb_community
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
        echo json_encode([
            'data' => $obj,
            'pagination' => $pagination_html,
            'pagination_info' => $pagination_info
        ]);
    }

    function load_item() {
        global $DATABASE;
		$sql = "SELECT 
            i.item_id,
            MD5(i.item_id) AS item_id_md5,
            i.item_title, 
            i.item_issued_year,
            i.item_publisher,
            i.item_abstract,
            GROUP_CONCAT(DISTINCT CONCAT('<a href=\"?p=search&author_name=', w.writer_fname, ' ', w.writer_lname, '\">', w.writer_fname, ' ', w.writer_lname, '</a>') 
                ORDER BY w.writer_fname SEPARATOR ', ') AS writer_names,
            f.file_name
        FROM tb_item AS i
        LEFT JOIN tb_writer AS w ON i.item_id = w.item_id
        LEFT JOIN tb_file AS f 
            ON i.item_id = f.item_id 
            AND f.file_id = (
                SELECT MAX(file_id) 
                FROM tb_file 
                WHERE item_id = i.item_id 
                AND file_type = 'cover'
            )
        GROUP BY i.item_id
        ORDER BY i.item_id DESC
        LIMIT 5";
		$return = array();
		$return["data"] = $DATABASE->QueryObj($sql);
        return json_encode( $return );
    }
    
    
    
    