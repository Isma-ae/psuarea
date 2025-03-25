<?php
	session_start();

	if (!isset($_SESSION["user_fname"])) {
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
        case 'load_collection'		: echo load_collection(); 		break;
        case 'add_item'		        : echo add_item(); 	            break;
		default: break;
	}

    function load_community()  {
        global $DATABASE;
        $sql = "SELECT * FROM tb_community";
        $response['data'] = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function load_collection()  {
        global $DATABASE;
        $sql = "SELECT * FROM tb_collection";
        $response['data'] = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function add_item() {
        global $DATABASE;
        if ($_POST["community_id"] == 0) {
            echo json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"กรุณาเลือกชุมชน",
                "icon"=>"error"
            ]);
            exit();
        }
        if ($_POST["collection_id"] == 0) {
            echo json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"กรุณาเลือกคอลเลกชัน",
                "icon"=>"error"
            ]);
            exit();
        }
        $item_id = $DATABASE->QueryMaxId("tb_item","item_id",'ITM',11);
        $file_id = $DATABASE->QueryMaxId("tb_file","file_id");
        $parentFolder = "../../../files/item/";
        $newdir = $parentFolder . DIRECTORY_SEPARATOR . $item_id;
        mkdir($newdir, 0777, true);
        $dir = $newdir."/";
        $file = $_FILES["file_name"];
        $file_name = uploadFile($dir,$file,"file_".$file_id);
        $insert = $DATABASE->QueryInsert('tb_item',[
            'item_id' => $item_id,
            'item_title' => $_POST["item_title"],
            'community_id' => $_POST["community_id"],
            'collection_id' => $_POST["collection_id"],
            'item_alternative' => $_POST["item_alternative"],
            'item_issued_day' => $_POST["item_issued_day"],
            'item_issued_month' => $_POST["item_issued_month"],
            'item_issued_year' => $_POST["item_issued_year"],
            'item_description' => $_POST["item_description"],
            'item_abstract' => $_POST["item_abstract"],
            'item_sponsorship' => $_POST["item_sponsorship"],
            'item_citation' => $_POST["item_citation"],
            'item_uri' => $_POST["item_uri"],
            'item_publisher' => $_POST["item_publisher"],
            'type_id' => $_POST["type_id"]
        ]);
        if ($insert) {
            if ($file_name != "") {
                $DATABASE->QueryInsert('tb_file',[
                    'file_id' => $file_id,
                    'file_name' => $file_name,
                    'file_type' => 'file',
                    'file_description' => 'ไฟล์เนื้อหา',
                    'item_id' => $item_id
                ]);
            }
            $writer_prefixs = $_POST['writer_prefix'];
            $writer_fnames = $_POST['writer_fname'];
            $writer_lnames = $_POST['writer_lname'];
            $writer_mains = $_POST['writer_main'];
            foreach ($writer_fnames as $index => $writer_fname) {
                $writer_id = $DATABASE->QueryMaxId("tb_writer","writer_id",'WRT',11);
                if (!isset($writer_prefixs[$index], $writer_lnames[$index])) {
                    continue;
                }
                $writer_main = isset($writer_mains[$index]) ? $writer_mains[$index] : 2;
                $DATABASE->QueryInsert('tb_writer',[
                    'writer_id' => $writer_id,
                    'writer_prefix' => $writer_prefixs[$index],
                    'writer_fname' => $writer_fname,
                    'writer_lname' => $writer_lnames[$index],
                    'item_id' => $item_id,
                    'writer_main' => $writer_main

                ]);
            }
            $subject_names = $_POST['subject_name'];
            foreach ($subject_names as $i => $subject_name) {
                $subject_id = $DATABASE->QueryMaxId("tb_subject","subject_id");
                $DATABASE->QueryInsert('tb_subject',[
                    'subject_id' => $subject_id,
                    'subject_name' => $subject_name,
                    'item_id' => $item_id

                ]);
            }
            echo json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"เพิ่มชุมชนเรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            echo json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถเพิ่มชุมชนได้",
                "icon"=>"error"
            ]);
        }
    }
