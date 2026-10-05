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
        case 'load_file'		: echo load_file(); 		break;
        case 'add_file'		    : echo add_file(); 	        break;
        case 'edit_file'	    : echo edit_file(); 	    break;
        case 'delete_file'	    : echo delete_file(); 	    break;
		default: break;
	}

    function load_file() {
        global $DATABASE;
        $sql = "SELECT * FROM tb_file WHERE item_id = '".$_POST["item_id"]."'";
        $return["data"] = $DATABASE->QueryObj($sql);
        return json_encode($return);
    }

    function add_file() {
        global $DATABASE;
        $item_id = $_POST["item_id"];
        $dir = "../../../files/item/".$item_id."/";
        $file_id = $DATABASE->QueryMaxId("tb_file","file_id");
        $file = $_FILES["file_name"];
        $file_name = uploadFile($dir,$file,"file_".$file_id);
        $insert = $DATABASE->QueryInsert('tb_file',[
            'file_id' => $file_id,
            'file_name' => $file_name,
            'file_type' => $_POST["file_type"],
            'file_description' => $_POST["file_description"],
            'item_id' => $_POST["item_id"]
        ]);
        if ($insert) {
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"เพิ่มไฟล์เรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            return json_encode([
                "data"=>"y",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถเพิ่มไฟล์ได้",
                "icon"=>"error"
            ]);
        }
        
    }

    function edit_file() {
        global $DATABASE;
        $item_id = $_POST["item_id"];
        $dir = "../../../files/item/".$item_id."/";
        $file_id = $_POST["file_id"];
        $file = $_FILES["file_name"];
        $file_name = uploadFile($dir,$file,"file_".$file_id);
        if ($file_name=="") {
            $update = $DATABASE->QueryUpdate("tb_file",[
                'file_type' => $_POST["file_type"],
                'file_description' => $_POST["file_description"]
            ],"file_id = ".$file_id."");
        } else {
            $update = $DATABASE->QueryUpdate("tb_file",[
                'file_type' => $_POST["file_type"],
                'file_description' => $_POST["file_description"],
                'file_name' => $file_name
            ],"file_id = ".$file_id."");
        }
        if ($update) {
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"แก้ไขไฟล์เรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            return json_encode([
                "data"=>"y",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถแก้ไขไฟล์ได้",
                "icon"=>"error"
            ]);
        }
    }

    function delete_file() {
        global $DATABASE;
        $item_id = $_POST["item_id"];
        $dir = "../../../files/item/".$item_id."/";
        $obj = $DATABASE->QueryObj("SELECT * FROM tb_file WHERE file_id = '".$_POST["file_id"]."'");
        $delete = $DATABASE->QueryDelete("tb_file","file_id = '".$_POST["file_id"]."'");
        if ($delete) {
            deleteFile($dir,$obj[0]["file_name"]);
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"ลบไฟล์เรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            return json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถลบไฟล์นี้",
                "icon"=>"error"
            ]);
        }
        
    }