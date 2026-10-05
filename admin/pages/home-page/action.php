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
        case 'edit_title'	    : echo edit_title(); 	    break;
		default: break;
	}

    function load_page() {
        global $DATABASE;
		$sql = "SELECT * FROM tb_page";
		$rows = $DATABASE->QueryObj($sql);
        if (empty($rows)) {
            // สร้าง record ตั้งต้นถ้ายังไม่มีข้อมูล
            $DATABASE->QueryInsert('tb_page', [
                'page_id' => 1,
                'page_title' => '',
                'page_banner' => ''
            ]);
            $rows = $DATABASE->QueryObj($sql);
        }
		$return['data'] = $rows;
        return json_encode( $return );
    }

    function add_banner() {
        global $DATABASE;
        $dir = getFilesDir("banner") . "/";
        if (!file_exists($dir)) {
            @mkdir($dir, 0777, true);
            @chmod($dir, 0777);
        }

        $img = isset($_FILES["page_banner"]) ? $_FILES["page_banner"] : null;
        if (!$img || !isset($img['error']) || $img['error'] !== UPLOAD_ERR_OK) {
            $uploadErrorMessages = [
                UPLOAD_ERR_INI_SIZE   => "ขนาดไฟล์เกินค่า upload_max_filesize ใน php.ini",
                UPLOAD_ERR_FORM_SIZE  => "ขนาดไฟล์เกินค่า MAX_FILE_SIZE ในฟอร์ม",
                UPLOAD_ERR_PARTIAL    => "ไฟล์ถูกอัปโหลดเพียงบางส่วน",
                UPLOAD_ERR_NO_FILE    => "กรุณาเลือกไฟล์รูปภาพแบนเนอร์",
                UPLOAD_ERR_NO_TMP_DIR => "ไม่พบโฟลเดอร์ชั่วคราวสำหรับอัปโหลด",
                UPLOAD_ERR_CANT_WRITE => "ไม่สามารถเขียนไฟล์ลงดิสก์ได้ ตรวจสอบสิทธิ์ของเซิร์ฟเวอร์",
                UPLOAD_ERR_EXTENSION  => "การอัปโหลดไฟล์ถูกระงับโดย PHP extension"
            ];
            $errorCode = ($img && isset($img['error'])) ? $img['error'] : UPLOAD_ERR_NO_FILE;
            $errMsg = isset($uploadErrorMessages[$errorCode]) ? $uploadErrorMessages[$errorCode] : "เกิดข้อผิดพลาดในการอัปโหลดไฟล์ (รหัส: $errorCode)";
            return json_encode([
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => $errMsg,
                "icon" => "error"
            ]);
        }

        // ตรวจสอบรูปแบนเนอร์เดิมเพื่อลบไฟล์เก่าทิ้ง ป้องกันไฟล์ตกค้าง
        $old_obj = $DATABASE->QueryObj("SELECT page_banner FROM tb_page WHERE page_id = 1");
        $old_banner = (!empty($old_obj) && !empty($old_obj[0]["page_banner"])) ? $old_obj[0]["page_banner"] : "";

        $page_banner = uploadFile($dir, $img, "banner");
        if ($page_banner != "") {
            // ลบไฟล์เก่าออกถ้าอัปโหลดรูปใหม่สำเร็จ
            if (!empty($old_banner) && $old_banner !== $page_banner) {
                deleteFile($dir, $old_banner);
            }

            $check = $DATABASE->QueryObj("SELECT page_id FROM tb_page WHERE page_id = 1");
            if (!empty($check)) {
                $add_banner = $DATABASE->QueryUpdate('tb_page', ['page_banner' => $page_banner], 'page_id = 1');
            } else {
                $add_banner = $DATABASE->QueryInsert('tb_page', ['page_id' => 1, 'page_banner' => $page_banner, 'page_title' => '']);
            }

            if ($add_banner) {
                return json_encode([
                    "data" => "y",
                    "title" => "สำเร็จ",
                    "message" => "เพิ่มแบนเนอร์เรียบร้อย",
                    "icon" => "success"
                ]);
            } else {
                deleteFile($dir, $page_banner);
                return json_encode([
                    "data" => "n",
                    "title" => "ไม่สำเร็จ",
                    "message" => "ไม่สามารถบันทึกข้อมูลแบนเนอร์ได้",
                    "icon" => "error"
                ]);
            }
        } else {
            return json_encode([
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "ไม่สามารถอัปโหลดไฟล์ได้ กรุณาตรวจสอบสิทธิ์การเขียนโฟลเดอร์ files/banner",
                "icon" => "error"
            ]);
        }
    }

    function delete_banner() {
        global $DATABASE;
        $dir = getFilesDir("banner") . "/";
        $obj = $DATABASE->QueryObj("SELECT * FROM tb_page WHERE page_id = 1");
        $delete = $DATABASE->QueryUpdate('tb_page', ['page_banner' => ''], 'page_id = 1');
        if ($delete) {
            if (!empty($obj) && !empty($obj[0]["page_banner"])) {
                deleteFile($dir, $obj[0]["page_banner"]);
            }
            return json_encode([
                "data" => "y",
                "title" => "สำเร็จ",
                "message" => "ลบแบนเนอร์เรียบร้อย",
                "icon" => "success"
            ]);
        } else {
            return json_encode([
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "ไม่สามารถลบแบนเนอร์ได้",
                "icon" => "error"
            ]);
        }
    }

    function edit_title() {
        global $DATABASE;
        $page_title = isset($_POST["page_title"]) ? $_POST["page_title"] : "";
        $check = $DATABASE->QueryObj("SELECT page_id FROM tb_page WHERE page_id = 1");
        if (!empty($check)) {
            $update = $DATABASE->QueryUpdate('tb_page', ['page_title' => $page_title], 'page_id = 1');
        } else {
            $update = $DATABASE->QueryInsert('tb_page', ['page_id' => 1, 'page_title' => $page_title, 'page_banner' => '']);
        }

        if ($update) {
            return json_encode([
                "data" => "y",
                "title" => "สำเร็จ",
                "message" => "บันทึกข้อความเรียบร้อย",
                "icon" => "success"
            ]);
        } else {
            return json_encode([
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "ไม่สามารถบันทึกข้อความได้",
                "icon" => "error"
            ]);
        }
    }