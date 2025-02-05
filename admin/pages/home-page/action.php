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
        case 'load_page'		: echo load_page(); 		break;
        case 'add_banner'		: echo add_banner(); 	    break;
        case 'delete_banner'	: echo delete_banner(); 	break;
		default: break;
	}

    function load_page() {
        global $DATABASE;
		$sql = "SELECT * FROM tb_page";
		$return['data'] = $DATABASE->QueryObj($sql);
        return json_encode( $return );
    }

    function add_banner() {
        global $DATABASE;
        $dir = "../../../file/banner/";
        $img = @$_FILES["page_banner"];
        $page_banner = uploadFile($dir,$img,"banner");
        if ($page_banner != "") {
            $add_banner = $DATABASE->QueryUpdate('tb_page',['page_banner' => $page_banner],'page_id = 1');
            if ($add_banner) {
                return json_encode([
                    "data"=>"y",
                    "title"=>"สำเร็จ",
                    "message"=>"เพิ่มแบนเนอร์เรียบร้อย",
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
            
        } else {
            return json_encode([
                "data"=>"y",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่พบไฟล์",
                "icon"=>"error"
            ]);
        }
    }

    function delete_banner() {
        global $DATABASE;
        $dir = "../../../file/banner/";
        $obj = $DATABASE->QueryObj("SELECT * FROM tb_page WHERE page_id = 1");
        $delete = $DATABASE->QueryUpdate('tb_page',['page_banner' => ''],'page_id = 1');
        if ($delete) {
            deleteFile($dir,$obj[0]["page_banner"]);
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"ลบแบนเนอร์เรียบร้อย",
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