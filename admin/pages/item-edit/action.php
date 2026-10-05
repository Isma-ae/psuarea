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
        case 'delete_writer'		: echo delete_writer(); 		break;
        case 'delete_subject'		: echo delete_subject(); 		break;
        case 'delete_file'		    : echo delete_file(); 		    break;
        case 'edit_item'		    : echo edit_item(); 		    break;
		default: break;
	}

    function delete_writer() {
        global $DATABASE;
        $delete = $DATABASE->QueryDelete("tb_writer", "writer_id = '".$_POST["writer_id"]."'");
        if ($delete) {
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"ลบผู้เขียนเรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            return json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถลบผู้เขียน",
                "icon"=>"error"
            ]);
        }
    }

    function delete_subject() {
        global $DATABASE;
        $delete = $DATABASE->QueryDelete("tb_subject", "subject_id = ".$_POST["subject_id"]."");
        if ($delete) {
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"ลบหัวเรื่องเรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            return json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถลบหัวเรื่อง",
                "icon"=>"error"
            ]);
        }
    }

    function delete_file() {
        global $DATABASE;
        $dir = "../../../files/item/".$_POST["item_id"]."/";
        $obj = $DATABASE->QueryObj("SELECT * FROM tb_file WHERE file_id = ".$_POST["file_id"]."");
        $delete = $DATABASE->QueryDelete("tb_file","file_id = ".$_POST["file_id"]."");
        if ($delete) {
            deleteFile($dir,$obj[0]["file_name"]);
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

    function edit_item() {
        global $DATABASE;
        $item_id = $_POST["item_id"];
        $dir = "../../../files/item/".$_POST["item_id"]."/";
        if (!empty($_FILES["file_name"])) {
            $file_id = $DATABASE->QueryMaxId("tb_file","file_id");
            $file = $_FILES["file_name"];
            $file_name = uploadFile($dir,$file,"file_".$file_id);
            if ($file_name != "") {
                $DATABASE->QueryInsert('tb_file',[
                    'file_id' => $file_id,
                    'file_name' => $file_name,
                    'file_type' => 'file',
                    'file_description' => 'ไฟล์เนื้อหา',
                    'item_id' => $item_id
                ]);
            }
        }
        $update = $DATABASE->QueryUpdate('tb_item',[
            'item_title' => $_POST["item_title"],
            'community_id' => $_POST["community_id"],
            'collection_id' => $_POST["collection_id"],
            'item_alternative' => $_POST["item_alternative"],
            'item_issued_month' => $_POST["item_issued_month"],
            'item_issued_year' => $_POST["item_issued_year"],
            'item_description' => $_POST["item_description"],
            'item_abstract' => $_POST["item_abstract"],
            'item_sponsorship' => $_POST["item_sponsorship"],
            'item_citation' => $_POST["item_citation"],
            'item_uri' => $_POST["item_uri"],
            'item_publisher' => $_POST["item_publisher"]
        ], "item_id = '".$item_id."'");
        if ($update) {
            if (!empty($_POST['writer_id'])) {
                $writer_ids = $_POST['writer_id'];
                $writer_fnames = $_POST['writer_fname'];
                $writer_lnames = $_POST['writer_lname'];
                $writer_mains = $_POST['writer_main'];
                foreach ($writer_fnames as $index => $writer_fname) {
                    if (!isset( $writer_lnames[$index])) {
                        continue;
                    }
                    $writer_main = isset($writer_mains[$index]) ? $writer_mains[$index] : 2;
                    $DATABASE->QueryUpdate("tb_writer", [
                        'writer_fname' => $writer_fname,
                        'writer_lname' => $writer_lnames[$index],
                        'writer_main' => $writer_main
                    ], "writer_id = '".$writer_ids[$index]."'");
                }
            }
            if (!empty($_POST['edit_fname'])) {
                $edit_prefixs = $_POST['edit_prefix'];
                $edit_fnames = $_POST['edit_fname'];
                $edit_lnames = $_POST['edit_lname'];
                $edit_mains = isset($_POST['edit_main']) ? $_POST['edit_main'] : [];
                foreach ($edit_fnames as $i => $edit_fname) {
                    $writer_ids = $DATABASE->QueryMaxId("tb_writer","writer_id",'WRT',11);
                    if (!isset($edit_prefixs[$i], $edit_lnames[$i])) {
                        continue;
                    }
                    $edit_main = isset($edit_mains[$i]) ? $edit_mains[$i] : 2;
                    $DATABASE->QueryInsert('tb_writer',[
                        'writer_id' => $writer_ids,
                        'writer_prefix' => $edit_prefixs[$i],
                        'writer_fname' => $edit_fname,
                        'writer_lname' => $edit_lnames[$i],
                        'item_id' => $item_id,
                        'writer_main' => $edit_main
                    ]);
                }
            }


            if (!empty($_POST['subject_id']) && !empty($_POST['subject_name'])) {
                $subject_ids = $_POST['subject_id'];
                $subject_names = $_POST['subject_name'];
                foreach ($subject_names as $i2 => $subject_name) {
                    $DATABASE->QueryUpdate("tb_subject", [
                        'subject_name' => $subject_name
                    ], "subject_id = '".$subject_ids[$i2]."'");
                }
            }
            if (!empty($_POST['edit_name'])) {
                $edit_names = $_POST['edit_name'];
                foreach ($edit_names as $i3 => $edit_name) {
                    $subject_ids = $DATABASE->QueryMaxId("tb_subject","subject_id");
                    $DATABASE->QueryInsert('tb_subject',[
                        'subject_id' => $subject_ids,
                        'subject_name' => $edit_name,
                        'item_id' => $item_id
                    ]);
                }
            }
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"แก้ไขรายการเรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            return json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถแก้ไขรายการนี้ได้",
                "icon"=>"error"
            ]);
        }
        
    }