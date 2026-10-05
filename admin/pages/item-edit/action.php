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
        $item_id = $DATABASE->Escape($_POST["item_id"]);
        $file_id = $DATABASE->Escape($_POST["file_id"]);
        $dir = getFilesDir("item/" . $item_id) . "/";
        $obj = $DATABASE->QueryObj("SELECT * FROM tb_file WHERE file_id = '$file_id'");
        $delete = $DATABASE->QueryDelete("tb_file","file_id = '$file_id'");
        if ($delete) {
            if (!empty($obj) && isset($obj[0]["file_name"])) {
                deleteFile($dir, $obj[0]["file_name"]);
            }
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
                "message"=>"ไม่สามารถลบไฟล์นี้ได้",
                "icon"=>"error"
            ]);
        }
    }

    function edit_item() {
        global $DATABASE;
        $item_id = $DATABASE->Escape($_POST["item_id"]);
        $has_upload_file = isset($_FILES["file_name"]) && !empty($_FILES["file_name"]["name"]);
        $file_name = "";

        if ($has_upload_file) {
            $upload_error = $_FILES["file_name"]["error"];
            if ($upload_error !== UPLOAD_ERR_OK && $upload_error !== UPLOAD_ERR_NO_FILE) {
                $error_msg = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์ (รหัส: $upload_error)";
                switch ($upload_error) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $error_msg = "ขนาดไฟล์เกินขีดจำกัดที่เซิร์ฟเวอร์กำหนด (กรุณาตรวจสอบ upload_max_filesize และ post_max_size ใน php.ini)";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $error_msg = "การอัปโหลดไฟล์ไม่สมบูรณ์ กรุณาลองใหม่อีกครั้ง";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $error_msg = "ไม่พบโฟลเดอร์ชั่วคราวสำหรับพักไฟล์บนเซิร์ฟเวอร์ (upload_tmp_dir)";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $error_msg = "ไม่สามารถบันทึกไฟล์ลงดิสก์ของเซิร์ฟเวอร์ได้ กรุณาตรวจสอบพื้นที่หรือสิทธิ์การเขียนดิสก์";
                        break;
                }
                return json_encode([
                    "data" => "n",
                    "title" => "ไม่สำเร็จ",
                    "message" => $error_msg,
                    "icon" => "error"
                ]);
            }

            $filesDir = getFilesDir();
            $parentFolder = getFilesDir("item");
            $newdir = $parentFolder . "/" . $item_id;

            if (!is_dir($newdir)) {
                if (!@mkdir($newdir, 0777, true) && !is_dir($newdir)) {
                    $err = error_get_last();
                    $sysErr = isset($err['message']) ? $err['message'] : 'Permission denied';
                    $phpUser = function_exists('posix_getpwuid') ? @posix_getpwuid(posix_geteuid())['name'] : get_current_user();
                    $permInfo = is_dir($filesDir) ? substr(sprintf('%o', fileperms($filesDir)), -4) : 'ไม่พบโฟลเดอร์';

                    return json_encode([
                        "data" => "n",
                        "title" => "ไม่สามารถสร้างโฟลเดอร์ได้",
                        "message" => "พาธ: $newdir\nระบบแจ้ง: $sysErr\n(PHP User: $phpUser, สิทธิ์ $filesDir: $permInfo)\n\nกรุณารันคำสั่ง: sudo chown -R $phpUser:$phpUser \"$filesDir\" && sudo chmod -R 777 \"$filesDir\"",
                        "icon" => "error"
                    ]);
                }
                @chmod($parentFolder, 0777);
                @chmod($newdir, 0777);
            }

            $dir = $newdir . "/";
            $file_id = $DATABASE->QueryMaxId("tb_file", "file_id");
            $file = $_FILES["file_name"];
            $file_name = uploadFile($dir, $file, "file_" . $file_id);

            if ($file_name == "") {
                return json_encode([
                    "data" => "n",
                    "title" => "ไม่สามารถบันทึกไฟล์ได้",
                    "message" => "ไม่สามารถย้ายไฟล์ไปยัง $dir ได้ กรุณาตรวจสอบสิทธิ์การเขียนโฟลเดอร์",
                    "icon" => "error"
                ]);
            }

            $DATABASE->QueryInsert('tb_file',[
                'file_id' => $file_id,
                'file_name' => $file_name,
                'file_type' => 'file',
                'file_description' => 'ไฟล์เนื้อหา',
                'item_id' => $item_id
            ]);
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