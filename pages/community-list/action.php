<?php
	session_start();
    
	include("../../php/functions.php");
    $fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
    switch ($fn) {
        case 'load_community'	    : echo load_community(); 	    break;
        case 'load_collection'	    : echo load_collection(); 	    break;
        case 'load_collections'	    : echo load_collections(); 	    break;
		default: break;
	}

    function load_community() {
        global $DATABASE;
		$sql = "SELECT 
                    tb_community.community_id,
                    MD5(tb_community.community_id) AS community_md5_id,
                    community_title,
                    community_description,
                    COUNT(item_id) AS count_community
                FROM tb_item
                RIGHT JOIN tb_community ON tb_community.community_id = tb_item.community_id
                GROUP BY tb_community.community_id";

        $obj = $DATABASE->QueryObj($sql);
        return json_encode($obj);
    }

    function load_collection() {
        global $DATABASE;
		$community_id = $DATABASE->Escape($_POST["community_id"]);

        $sql = "SELECT 
                    MD5(tb_collection.collection_id) AS collection_id, 
                    tb_collection.collection_name, 
                    COUNT(tb_item.item_id) AS count_collection 
                FROM tb_collection
                LEFT JOIN tb_item ON tb_item.collection_id = tb_collection.collection_id 
                                AND MD5(tb_item.community_id) = '$community_id'
                GROUP BY tb_collection.collection_id, tb_collection.collection_name";

        $obj = $DATABASE->QueryObj($sql);
        return json_encode($obj);
    }

    function load_collections() {
        global $DATABASE;
		$sql = "SELECT 
                    MD5(tb_collection.collection_id) AS collection_id,
                    tb_collection.collection_name,
                    COUNT(tb_item.item_id) AS count_collection 
                FROM tb_collection
                LEFT JOIN tb_item ON tb_item.collection_id = tb_collection.collection_id 
                GROUP BY tb_collection.collection_id, tb_collection.collection_name";

        $obj = $DATABASE->QueryObj($sql);
        return json_encode($obj);
    }