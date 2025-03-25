<?php
	session_start();
    
	include("../../php/functions.php");

    $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 5;
    $item_id = $_POST["item_id"];
    $sql = "SELECT *
        FROM tb_file
        WHERE MD5(item_id) = '$item_id'
        LIMIT $limit OFFSET $offset
    ";
    $response = array();
    $response['data'] = $DATABASE->QueryObj($sql);
    echo json_encode($response);