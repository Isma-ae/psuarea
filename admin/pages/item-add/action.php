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
        'item_alternative' => $_POST["item_alternative"],
        'item_issued' => $_POST["item_issued"],
        'item_description' => $_POST["item_description"],
        'item_abstract' => $_POST["item_abstract"],
        'item_sponsorship' => $_POST["item_sponsorship"],
        'item_citation' => $_POST["item_citation"],
        'item_publisher' => $_POST["item_publisher"],
        'type_id' => $_POST["type_id"]
    ]);
    if ($insert) {
        $DATABASE->QueryInsert('tb_file',[
            'file_id' => $file_id,
            'file_name' => $file_name
        ]);
        $writer_prefixs = $_POST['writer_prefix'];
        $writer_fnames = $_POST['writer_fname'];
        $writer_lnames = $_POST['writer_lname'];
        $writer_mains = $_POST['writer_main'];
        foreach ($writer_fnames as $index => $writer_fname) {
            $writer_id = $DATABASE->QueryMaxId("tb_writer","writer_id",'WRT',11);
            $writer_prefix = $writer_prefixs[$index];
            $writer_lname = $writer_lnames[$index];
            $writer_main = $writer_mains[$index];
            $DATABASE->QueryInsert('tb_writer',[
                'writer_id' => $writer_id,
                'writer_prefix' => $writer_prefix,
                'writer_fname' => $writer_fname,
                'writer_lname' => $writer_lname,
                'writer_main' => $writer_main

            ]);
        }
        $subject_names = $_POST['subject_name'];
        foreach ($subject_names as $i => $subject_name) {
            $subject_id = $DATABASE->QueryMaxId("tb_subject","subject_id");
            $DATABASE->QueryInsert('tb_subject',[
                'subject_id' => $subject_id,
                'subject_name' => $subject_name

            ]);
        }
        return json_encode([
            "data"=>"y",
            "title"=>"สำเร็จ",
            "message"=>"เพิ่มชุมชนเรียบร้อย",
            "icon"=>"success"
        ]);
    } else {
        return json_encode([
            "data"=>"y",
            "title"=>"ไม่สำเร็จ",
            "message"=>"ไม่สามารถเพิ่มชุมชนได้",
            "icon"=>"error"
        ]);
    }
