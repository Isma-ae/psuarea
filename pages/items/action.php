<?php
	session_start();
    
	include("../../php/functions.php");
    $fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
	switch ($fn) {
        case 'load_item'	    : echo load_item(); 	    break;
        case 'load_files'	    : echo load_files(); 	    break;
		default: break;
	}

    function load_item() {
        global $DATABASE;
        $item_id = $_POST["item_id"];
        $item_id = $DATABASE->Escape($item_id);
        $sql = "SELECT * FROM tb_item
                INNER JOIN tb_collection ON tb_item.collection_id = tb_collection.collection_id
                WHERE MD5(item_id) = '$item_id'";
        $sql2 = "SELECT * FROM tb_file WHERE file_type = 'cover' AND MD5(item_id) = '$item_id' ORDER BY file_id DESC LIMIT 1";
        $sql3 = "SELECT * FROM tb_writer WHERE MD5(item_id) = '$item_id'";
        $sql4 = "SELECT * FROM tb_subject WHERE MD5(item_id) = '$item_id'";
        $response = array();
        $response["item"] = $DATABASE->QueryObj($sql);
        $response["cover"] = $DATABASE->QueryObj($sql2);
        $response["author"] = $DATABASE->QueryObj($sql3);
        $response["sbj"] = $DATABASE->QueryObj($sql4);
        return json_encode($response);
    }

    function load_files() {
        global $DATABASE;
        $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 5;
        $item_id = $_POST["item_id"];
        $item_id = $DATABASE->Escape($item_id);
        $sql = "SELECT *
            FROM tb_file
            WHERE MD5(item_id) = '$item_id'
            LIMIT $limit OFFSET $offset
        ";
        $response = array();
        $response['data'] = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }