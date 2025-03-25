<?php
	session_start();
    
	include("../../php/functions.php");
    $fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
    switch ($fn) {
        case 'load_subject'	: echo load_subject(); 	break;
		default: break;
	}

    function load_subject() {
        global $DATABASE;
        $search_query = $_POST["search_query"];
        $condition = "";
        if (!empty($search_query)) {
            $condition = " WHERE type_name LIKE '$search_query'";
        }
        $sql = "SELECT MD5(type_id) AS type_id, type_name FROM tb_type$condition";
        $return["data"] = $DATABASE->QueryObj($sql);
        return json_encode($return);
    }