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
        if ($_POST["community_id"] == '0') {
            return json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"กรุณาเลือกชุมชน",
                "icon"=>"error"
            ]);
            exit();
        }
        if ($_POST["collection_id"] == '0') {
            return json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"กรุณาเลือกคอลเลกชัน",
                "icon"=>"error"
            ]);
            exit();
        }

        // ตรวจสอบข้อผิดพลาดของไฟล์ที่อัปโหลด (ถ้ามีการเลือกไฟล์)
        $has_upload_file = isset($_FILES["file_name"]) && !empty($_FILES["file_name"]["name"]);
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
        }

        $item_id = $DATABASE->QueryMaxId("tb_item","item_id",'ITM',11);
        $file_id = $DATABASE->QueryMaxId("tb_file","file_id");

        $file_name = "";
        if ($has_upload_file && isset($_FILES["file_name"]["tmp_name"]) && !empty($_FILES["file_name"]["tmp_name"])) {
            $filesDir = getFilesDir();
            $parentFolder = getFilesDir("item");
            $newdir = $parentFolder . "/" . $item_id;

            // ตรวจสอบและสร้างโฟลเดอร์สำหรับจัดเก็บไฟล์
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
        }

        $insert = $DATABASE->QueryInsert('tb_item',[
            'item_id' => $item_id,
            'item_title' => $_POST["item_title"],
            'community_id' => $_POST["community_id"],
            'collection_id' => $_POST["collection_id"],
            'item_alternative' => $_POST["item_alternative"],
            'item_issued_month' => $_POST["item_issued_month"],
            'item_issued_year' => $_POST["item_issued_year"],
            'item_abstract' => $_POST["item_abstract"],
            'item_sponsorship' => $_POST["item_sponsorship"],
            'item_citation' => $_POST["item_citation"],
            'item_uri' => $_POST["item_uri"],
            'item_publisher' => $_POST["item_publisher"]
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
            $writer_fnames = $_POST['writer_fname'];
            $writer_lnames = $_POST['writer_lname'];
            $writer_mains = $_POST['writer_main'];
            foreach ($writer_fnames as $index => $writer_fname) {
                $writer_id = $DATABASE->QueryMaxId("tb_writer","writer_id",'WRT',11);
                if (!isset($writer_lnames[$index])) {
                    continue;
                }
                $writer_main = isset($writer_mains[$index]) ? $writer_mains[$index] : 2;
                $DATABASE->QueryInsert('tb_writer',[
                    'writer_id' => $writer_id,
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
                "message"=>"เพิ่มรายการเรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            echo json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถเพิ่มรายการได้",
                "icon"=>"error"
            ]);
        }
    }
