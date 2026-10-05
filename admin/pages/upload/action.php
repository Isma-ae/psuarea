<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

	header('Content-Type: application/json; charset=utf-8');

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
        $item_id = isset($_POST["item_id"]) ? addslashes(trim($_POST["item_id"])) : "";
        $sql = "SELECT * FROM tb_file WHERE item_id = '$item_id' ORDER BY file_id ASC";
        $data = $DATABASE->QueryObj($sql);
        if (!is_array($data)) {
            $data = [];
        }
        $json = json_encode(["data" => $data], JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            array_walk_recursive($data, function(&$item) {
                if (is_string($item)) {
                    $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8');
                }
            });
            $json = json_encode(["data" => $data], JSON_UNESCAPED_UNICODE);
        }
        return $json;
    }

    function add_file() {
        global $DATABASE;
        $item_id = isset($_POST["item_id"]) ? addslashes(trim($_POST["item_id"])) : "";
        if (empty($item_id)) {
            return json_encode([
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "ไม่พบรหัสรายการ (item_id)",
                "icon" => "error"
            ]);
        }

        $file = isset($_FILES["file_name"]) ? $_FILES["file_name"] : null;
        if (!$file || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            $uploadErrorMessages = [
                UPLOAD_ERR_INI_SIZE   => "ขนาดไฟล์เกินค่า upload_max_filesize ใน php.ini",
                UPLOAD_ERR_FORM_SIZE  => "ขนาดไฟล์เกินค่า MAX_FILE_SIZE ในฟอร์ม",
                UPLOAD_ERR_PARTIAL    => "ไฟล์ถูกอัปโหลดเพียงบางส่วน",
                UPLOAD_ERR_NO_FILE    => "กรุณาเลือกไฟล์ที่ต้องการอัปโหลด",
                UPLOAD_ERR_NO_TMP_DIR => "ไม่พบโฟลเดอร์ชั่วคราวสำหรับอัปโหลด",
                UPLOAD_ERR_CANT_WRITE => "ไม่สามารถเขียนไฟล์ลงดิสก์ได้ ตรวจสอบสิทธิ์เซิร์ฟเวอร์",
                UPLOAD_ERR_EXTENSION  => "การอัปโหลดไฟล์ถูกระงับโดย PHP extension"
            ];
            $errorCode = ($file && isset($file['error'])) ? $file['error'] : UPLOAD_ERR_NO_FILE;
            $errMsg = isset($uploadErrorMessages[$errorCode]) ? $uploadErrorMessages[$errorCode] : "เกิดข้อผิดพลาดในการอัปโหลดไฟล์ (รหัส: $errorCode)";
            return json_encode([
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => $errMsg,
                "icon" => "error"
            ]);
        }

        $dir = getFilesDir("item/" . $item_id) . "/";
        if (!file_exists($dir)) {
            if (!@mkdir($dir, 0777, true)) {
                $lastErr = error_get_last();
                $errDetail = (isset($lastErr['message']) && !empty($lastErr['message'])) ? $lastErr['message'] : 'Permission denied';
                return json_encode([
                    "data" => "n",
                    "title" => "ไม่สามารถสร้างโฟลเดอร์ได้",
                    "message" => "ไม่สามารถสร้างโฟลเดอร์ $dir (" . $errDetail . ")",
                    "icon" => "error"
                ]);
            }
            @chmod($dir, 0777);
        }

        $file_id = $DATABASE->QueryMaxId("tb_file", "file_id");
        $uploaded_name = uploadFile($dir, $file, "file_" . $file_id);
        if ($uploaded_name == "") {
            return json_encode([
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "ไม่สามารถอัปโหลดไฟล์ได้ กรุณาตรวจสอบสิทธิ์โฟลเดอร์ $dir",
                "icon" => "error"
            ]);
        }

        $insert = $DATABASE->QueryInsert('tb_file', [
            'file_id' => $file_id,
            'file_name' => $uploaded_name,
            'file_type' => isset($_POST["file_type"]) ? $_POST["file_type"] : "file",
            'file_description' => isset($_POST["file_description"]) ? $_POST["file_description"] : "",
            'item_id' => $item_id
        ]);

        if ($insert) {
            return json_encode([
                "data" => "y",
                "title" => "สำเร็จ",
                "message" => "เพิ่มไฟล์เรียบร้อย",
                "icon" => "success"
            ]);
        } else {
            deleteFile($dir, $uploaded_name);
            return json_encode([
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "ไม่สามารถบันทึกข้อมูลไฟล์ลงฐานข้อมูลได้",
                "icon" => "error"
            ]);
        }
    }

    function edit_file() {
        global $DATABASE;
        $item_id = isset($_POST["item_id"]) ? addslashes(trim($_POST["item_id"])) : "";
        $file_id = isset($_POST["file_id"]) ? addslashes(trim($_POST["file_id"])) : "";
        $file_type = isset($_POST["file_type"]) ? $_POST["file_type"] : "file";
        $file_description = isset($_POST["file_description"]) ? $_POST["file_description"] : "";

        $dir = getFilesDir("item/" . $item_id) . "/";
        if (!file_exists($dir)) {
            @mkdir($dir, 0777, true);
            @chmod($dir, 0777);
        }

        $file = isset($_FILES["file_name"]) ? $_FILES["file_name"] : null;
        $has_new_file = ($file && isset($file["error"]) && $file["error"] === UPLOAD_ERR_OK && !empty($file["name"]));

        // ตรวจสอบชื่อไฟล์เดิม
        $old_file_obj = $DATABASE->QueryObj("SELECT * FROM tb_file WHERE file_id = '$file_id'");
        $old_file_name = (!empty($old_file_obj) && isset($old_file_obj[0]["file_name"])) ? $old_file_obj[0]["file_name"] : "";

        if ($has_new_file) {
            $uploaded_name = uploadFile($dir, $file, "file_" . $file_id);
            if ($uploaded_name == "") {
                return json_encode([
                    "data" => "n",
                    "title" => "ไม่สำเร็จ",
                    "message" => "ไม่สามารถอัปโหลดไฟล์ใหม่ได้ กรุณาตรวจสอบสิทธิ์โฟลเดอร์ $dir",
                    "icon" => "error"
                ]);
            }

            $update = $DATABASE->QueryUpdate("tb_file", [
                'file_type' => $file_type,
                'file_description' => $file_description,
                'file_name' => $uploaded_name
            ], "file_id = '$file_id'");

            if ($update) {
                // ลบไฟล์เก่าออกหากมีไฟล์ใหม่และชื่อไม่ซ้ำกัน
                if (!empty($old_file_name) && $old_file_name !== $uploaded_name) {
                    deleteFile($dir, $old_file_name);
                }
                return json_encode([
                    "data" => "y",
                    "title" => "สำเร็จ",
                    "message" => "แก้ไขไฟล์เรียบร้อย",
                    "icon" => "success"
                ]);
            } else {
                deleteFile($dir, $uploaded_name);
                return json_encode([
                    "data" => "n",
                    "title" => "ไม่สำเร็จ",
                    "message" => "ไม่สามารถแก้ไขข้อมูลไฟล์ได้",
                    "icon" => "error"
                ]);
            }
        } else {
            // หากมีการส่งไฟล์มาแต่ error ไม่ใช่ NO_FILE
            if ($file && isset($file["error"]) && $file["error"] !== UPLOAD_ERR_NO_FILE && !empty($file["name"])) {
                return json_encode([
                    "data" => "n",
                    "title" => "ไม่สำเร็จ",
                    "message" => "เกิดข้อผิดพลาดในการอัปโหลดไฟล์ใหม่ (รหัส: " . $file["error"] . ")",
                    "icon" => "error"
                ]);
            }

            $update = $DATABASE->QueryUpdate("tb_file", [
                'file_type' => $file_type,
                'file_description' => $file_description
            ], "file_id = '$file_id'");

            if ($update) {
                return json_encode([
                    "data" => "y",
                    "title" => "สำเร็จ",
                    "message" => "แก้ไขไฟล์เรียบร้อย",
                    "icon" => "success"
                ]);
            } else {
                return json_encode([
                    "data" => "n",
                    "title" => "ไม่สำเร็จ",
                    "message" => "ไม่สามารถแก้ไขข้อมูลไฟล์ได้",
                    "icon" => "error"
                ]);
            }
        }
    }

    function delete_file() {
        global $DATABASE;
        $item_id = isset($_POST["item_id"]) ? addslashes(trim($_POST["item_id"])) : "";
        $file_id = isset($_POST["file_id"]) ? addslashes(trim($_POST["file_id"])) : "";
        $dir = getFilesDir("item/" . $item_id) . "/";

        $obj = $DATABASE->QueryObj("SELECT * FROM tb_file WHERE file_id = '$file_id'");
        $delete = $DATABASE->QueryDelete("tb_file", "file_id = '$file_id'");
        if ($delete) {
            if (!empty($obj) && isset($obj[0]["file_name"])) {
                deleteFile($dir, $obj[0]["file_name"]);
            }
            return json_encode([
                "data" => "y",
                "title" => "สำเร็จ",
                "message" => "ลบไฟล์เรียบร้อย",
                "icon" => "success"
            ]);
        } else {
            return json_encode([
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "ไม่สามารถลบไฟล์นี้ได้",
                "icon" => "error"
            ]);
        }
    }