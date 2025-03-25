<?php
	session_start();
    
	include("../../php/functions.php");
    $fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
    switch ($fn) {
        case 'load_author'	: echo load_author(); 	break;
        //case 'load_collection'	: echo load_collection(); 	break;
		default: break;
	}

    function load_author() {
        global $DATABASE;
        $limit = 10;
        $page = isset($_POST["page"]) && $_POST["page"] > 1 ? (int)$_POST["page"] : 1;
        $start = ($page - 1) * $limit;
        $community_id = $_POST["community_id"];
        $collection_id = $_POST["collection_id"];
        $search_query = $_POST["search_query"];
        $search_query = $DATABASE->Escape($search_query);
        $search_filter = "WHERE 1=1";

        if (!empty($collection_id)) {
            $search_filter .= " AND MD5(collection_id) = '$collection_id'";
        }
        
        if (!empty($community_id)) {
            $search_filter .= " AND MD5(community_id) = '$community_id'";
        }

        if (!empty($search_query)) {
            $search_filter .= " AND CONCAT(writer_prefix, writer_fname, ' ', writer_lname) LIKE '%$search_query%'";
        }
    
        $total_sql = "SELECT COUNT(DISTINCT CONCAT(writer_prefix, writer_fname, ' ', writer_lname)) AS count_author 
                      FROM tb_writer
                      INNER JOIN tb_item ON tb_writer.item_id = tb_item.item_id
                      $search_filter";
    
        $total_obj = $DATABASE->QueryObj($total_sql);
        $total_data = $total_obj[0]["count_author"] ?? 0;
        $total_pages = ($total_data > 0) ? ceil($total_data / $limit) : 1;
        $sql = "SELECT
                    COUNT(*) AS count_author,
                    CONCAT(writer_prefix, writer_fname, ' ', writer_lname) AS author_name
                FROM tb_writer
                INNER JOIN tb_item ON tb_writer.item_id = tb_item.item_id
                $search_filter
                GROUP BY writer_prefix, writer_fname, writer_lname
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
    