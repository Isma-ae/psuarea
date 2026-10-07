<?php
	ob_start();
	session_start();

	// 1. ตรวจสอบกรณีขนาดข้อมูลเกินขีดจำกัด post_max_size ของ PHP
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
		ob_end_clean();
		header('Content-Type: application/json; charset=utf-8');
		$max_post = ini_get('post_max_size');
		$max_file = ini_get('upload_max_filesize');
		echo json_encode(array(
            "data" => "n",
            "title" => "ไฟล์มีขนาดใหญ่เกินไป",
            "message" => "ขนาดข้อมูลที่ส่งเกินขีดจำกัดที่เซิร์ฟเวอร์กำหนด (post_max_size: $max_post, upload_max_filesize: $max_file) กรุณาลดขนาดไฟล์เอกสารลงก่อนอัปโหลด หรือปรับค่าใน php.ini บนเซิร์ฟเวอร์",
            "icon" => "error"
        ));
        exit();
	}

	// 2. ตรวจสอบ Session อย่างยืดหยุ่น (รองรับ user_name, user_fname หรือ user_id)
	if (!isset($_SESSION["user_fname"]) && !isset($_SESSION["user_name"]) && !isset($_SESSION["user_id"])) {
		ob_end_clean();
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(array(
            "data" => "n",
            "title" => "ไม่สำเร็จ",
            "message" => "Session หมดอายุ กรุณาเข้าสู่ระบบใหม่อีกครั้ง",
            "icon" => "error",
            "url" => "./login/"
        ));
        exit();
	}

    include("../../../php/functions.php");

    $fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
    $resp = "";

	switch ($fn) {
        case 'load_community'		: $resp = load_community(); 		break;
        case 'load_collection'		: $resp = load_collection(); 		break;
        case 'add_item'		        : $resp = add_item(); 	            break;
		default: 
            $resp = json_encode(array(
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "ไม่พบคำสั่งที่ต้องการประมวลผล (Invalid Action: " . htmlspecialchars($fn, ENT_QUOTES, 'UTF-8') . ")",
                "icon" => "error"
            ));
            break;
	}

    ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo $resp;
    exit();

    function load_community()  {
        global $DATABASE;
        $sql = "SELECT * FROM tb_community";
        $response = array();
        $response['data'] = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function load_collection()  {
        global $DATABASE;
        $sql = "SELECT * FROM tb_collection";
        $response = array();
        $response['data'] = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function add_item() {
        global $DATABASE;

        $community_id = isset($_POST["community_id"]) ? $_POST["community_id"] : '0';
        if ($community_id == '0' || empty($community_id)) {
            return json_encode(array(
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "กรุณาเลือกชุมชน",
                "icon" => "error"
            ));
        }

        $collection_id = isset($_POST["collection_id"]) ? $_POST["collection_id"] : '0';
        if ($collection_id == '0' || empty($collection_id)) {
            return json_encode(array(
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "กรุณาเลือกคอลเลกชัน",
                "icon" => "error"
            ));
        }

        $item_title = isset($_POST["item_title"]) ? trim($_POST["item_title"]) : "";
        if (empty($item_title)) {
            return json_encode(array(
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "กรุณากรอกชื่อเรื่องผลงาน",
                "icon" => "error"
            ));
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
                        $error_msg = "ขนาดไฟล์เกินขีดจำกัดที่เซิร์ฟเวอร์กำหนด (upload_max_filesize: " . ini_get('upload_max_filesize') . ", post_max_size: " . ini_get('post_max_size') . ") กรุณาลดขนาดไฟล์ก่อนอัปโหลด";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $error_msg = "การอัปโหลดไฟล์ไม่สมบูรณ์ เนื่องจากการเชื่อมต่อขาดหาย กรุณาลองใหม่อีกครั้ง";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $error_msg = "ไม่พบโฟลเดอร์ชั่วคราวสำหรับพักไฟล์บนเซิร์ฟเวอร์ (upload_tmp_dir)";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $error_msg = "ไม่สามารถบันทึกไฟล์ลงดิสก์ของเซิร์ฟเวอร์ได้ กรุณาตรวจสอบพื้นที่ว่างหรือสิทธิ์การเขียนดิสก์";
                        break;
                }
                return json_encode(array(
                    "data" => "n",
                    "title" => "ไม่สำเร็จ",
                    "message" => $error_msg,
                    "icon" => "error"
                ));
            }
        }

        $item_id = $DATABASE->QueryMaxId("tb_item", "item_id", 'ITM', 11);
        $file_id = $DATABASE->QueryMaxId("tb_file", "file_id");

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

                    return json_encode(array(
                        "data" => "n",
                        "title" => "ไม่สามารถสร้างโฟลเดอร์ได้",
                        "message" => "พาธ: $newdir\nระบบแจ้ง: $sysErr\n(PHP User: $phpUser, สิทธิ์ $filesDir: $permInfo)\n\nกรุณารันคำสั่ง: sudo chown -R $phpUser:$phpUser \"$filesDir\" && sudo chmod -R 777 \"$filesDir\"",
                        "icon" => "error"
                    ));
                }
                @chmod($parentFolder, 0777);
                @chmod($newdir, 0777);
            }

            $dir = $newdir . "/";
            $file = $_FILES["file_name"];
            $file_name = uploadFile($dir, $file, "file_" . $file_id);
            if ($file_name == "") {
                return json_encode(array(
                    "data" => "n",
                    "title" => "ไม่สามารถบันทึกไฟล์ได้",
                    "message" => "ไม่สามารถย้ายไฟล์ไปยัง $dir ได้ กรุณาตรวจสอบสิทธิ์การเขียนโฟลเดอร์",
                    "icon" => "error"
                ));
            }
        }

        $insert = $DATABASE->QueryInsert('tb_item', array(
            'item_id' => $item_id,
            'item_title' => $item_title,
            'community_id' => $community_id,
            'collection_id' => $collection_id,
            'item_alternative' => isset($_POST["item_alternative"]) ? $_POST["item_alternative"] : "",
            'item_issued_month' => isset($_POST["item_issued_month"]) ? $_POST["item_issued_month"] : "",
            'item_issued_year' => isset($_POST["item_issued_year"]) ? $_POST["item_issued_year"] : "",
            'item_abstract' => isset($_POST["item_abstract"]) ? $_POST["item_abstract"] : "",
            'item_sponsorship' => isset($_POST["item_sponsorship"]) ? $_POST["item_sponsorship"] : "",
            'item_citation' => isset($_POST["item_citation"]) ? $_POST["item_citation"] : "",
            'item_uri' => isset($_POST["item_uri"]) ? $_POST["item_uri"] : "",
            'item_publisher' => isset($_POST["item_publisher"]) ? $_POST["item_publisher"] : ""
        ));

        if ($insert) {
            if ($file_name != "") {
                $DATABASE->QueryInsert('tb_file', array(
                    'file_id' => $file_id,
                    'file_name' => $file_name,
                    'file_type' => 'file',
                    'file_description' => 'ไฟล์เนื้อหา',
                    'item_id' => $item_id
                ));
            }

            if (isset($_POST['writer_fname']) && is_array($_POST['writer_fname'])) {
                $writer_fnames = $_POST['writer_fname'];
                $writer_lnames = isset($_POST['writer_lname']) && is_array($_POST['writer_lname']) ? $_POST['writer_lname'] : array();
                $writer_mains = isset($_POST['writer_main']) && is_array($_POST['writer_main']) ? $_POST['writer_main'] : array();
                foreach ($writer_fnames as $index => $writer_fname) {
                    $w_fname = trim($writer_fname);
                    $w_lname = isset($writer_lnames[$index]) ? trim($writer_lnames[$index]) : '';
                    if ($w_fname === '' && $w_lname === '') {
                        continue;
                    }
                    $writer_id = $DATABASE->QueryMaxId("tb_writer", "writer_id", 'WRT', 11);
                    $writer_main = isset($writer_mains[$index]) ? $writer_mains[$index] : 2;
                    $DATABASE->QueryInsert('tb_writer', array(
                        'writer_id' => $writer_id,
                        'writer_fname' => $w_fname,
                        'writer_lname' => $w_lname,
                        'item_id' => $item_id,
                        'writer_main' => $writer_main
                    ));
                }
            }

            if (isset($_POST['subject_name']) && is_array($_POST['subject_name'])) {
                $subject_names = $_POST['subject_name'];
                foreach ($subject_names as $i => $subject_name) {
                    $s_name = trim($subject_name);
                    if ($s_name === '') {
                        continue;
                    }
                    $subject_id = $DATABASE->QueryMaxId("tb_subject", "subject_id");
                    $DATABASE->QueryInsert('tb_subject', array(
                        'subject_id' => $subject_id,
                        'subject_name' => $s_name,
                        'item_id' => $item_id
                    ));
                }
            }

            return json_encode(array(
                "data" => "y",
                "title" => "สำเร็จ",
                "message" => "เพิ่มรายการเรียบร้อย",
                "icon" => "success"
            ));
        } else {
            $db_err = $DATABASE->Error();
            $err_msg = "ไม่สามารถเพิ่มรายการได้";
            if (!empty($db_err)) {
                $err_msg .= " (Database: " . $db_err . ")";
            }
            return json_encode(array(
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => $err_msg,
                "icon" => "error"
            ));
        }
    }
