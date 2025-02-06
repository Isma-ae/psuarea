<?php
	session_start();

	if (!isset($_SESSION["user_name"])) {
		echo json_encode([
            "data"=>"n",
            "title"=>"ไม่สำเร็จ",
            "message"=>"Session หมดอายุ",
            "icon"=>"error",
            "url"=>"./login/"
        ]);
        exit();
	}

    
	include("../../../php/functions.php");
	$fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
	switch ($fn) {
        case 'load_community'		: echo load_community(); 		break;
        case 'add_banner'		: echo add_banner(); 	    break;
        case 'delete_banner'	: echo delete_banner(); 	break;
        case 'edit_title'	    : echo edit_title(); 	    break;
		default: break;
	}

    function load_community() {
        global $DATABASE;
        $limit = 5;
        $page = 1;
        if($_POST["page"] > 1) {
            $start = (($_POST["page"] - 1) * $limit);
            $page = $_POST["page"];
        } else {
            $start = 0;
        }
        if($_POST["query"] != '') {
            $condition = preg_replace('/[^A-Za-z0-9\- ]/', '', $_POST["query"]);
            $condition = trim($condition);
            $condition = str_replace(" ", "%", $condition);
            $sample_data = array(
                ':community_title'			=>	'%' . $condition . '%',
                ':community_description'		=>	'%'	. $condition . '%'
            );

            $query = "SELECT *
                FROM tb_community 
                WHERE community_title LIKE :community_title 
                OR community_description LIKE :community_description 
                ORDER BY id DESC
            ";
        }
    }