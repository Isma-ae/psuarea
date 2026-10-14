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

    include_once(dirname(__FILE__) . "/../../../php/functions.php");

    // ตรวจสอบและอัปเดตโครงสร้างฐานข้อมูลอัตโนมัติหากยังไม่มีฟิลด์/ตารางใหม่
    ensure_schema();

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

    function ensure_schema() {
        global $DATABASE;
        // 1. เพิ่มฟิลด์ใหม่ใน tb_item โดยไม่กระทบฟิลด์เดิม
        $cols_item = array_column($DATABASE->QueryObj('DESCRIBE tb_item'), 'Field');
        if (!in_array('item_journal', $cols_item)) {
            $DATABASE->Query("ALTER TABLE `tb_item` ADD `item_journal` VARCHAR(255) NULL DEFAULT NULL AFTER `item_publisher`");
        }
        if (!in_array('item_volume', $cols_item)) {
            $DATABASE->Query("ALTER TABLE `tb_item` ADD `item_volume` VARCHAR(255) NULL DEFAULT NULL AFTER `item_journal`");
        }
        if (!in_array('item_isbn', $cols_item)) {
            $DATABASE->Query("ALTER TABLE `tb_item` ADD `item_isbn` VARCHAR(100) NULL DEFAULT NULL AFTER `item_volume`");
        }

        // 2. เพิ่มฟิลด์ writer_organization ใน tb_writer โดยไม่กระทบฟิลด์เดิม
        $cols_writer = array_column($DATABASE->QueryObj('DESCRIBE tb_writer'), 'Field');
        if (!in_array('writer_organization', $cols_writer)) {
            $DATABASE->Query("ALTER TABLE `tb_writer` ADD `writer_organization` VARCHAR(255) NULL DEFAULT NULL AFTER `writer_lname`");
        }

        // 3. สร้างตาราง tb_contributor สำหรับเก็บข้อมูล contributor
        $DATABASE->Query("CREATE TABLE IF NOT EXISTS `tb_contributor` (
          `contributor_id` int(11) NOT NULL AUTO_INCREMENT,
          `contributor_name` varchar(255) DEFAULT NULL,
          `contributor_role` varchar(255) DEFAULT NULL,
          `item_id` varchar(14) DEFAULT NULL,
          PRIMARY KEY (`contributor_id`),
          KEY `item_id` (`item_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;");

        // 4. สร้างตาราง tb_item_community สำหรับรองรับการเลือกหลายชุมชนต่อ 1 รายการ
        $DATABASE->Query("CREATE TABLE IF NOT EXISTS `tb_item_community` (
          `item_community_id` int(11) NOT NULL AUTO_INCREMENT,
          `item_id` varchar(14) DEFAULT NULL,
          `community_id` varchar(14) DEFAULT NULL,
          PRIMARY KEY (`item_community_id`),
          KEY `item_id` (`item_id`),
          KEY `community_id` (`community_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;");

        // 5. ตรวจสอบและเพิ่ม 5 คอลเล็กชันหลักหากยังไม่มี
        $collections = array(
            array('COM00000000001', 'วิจัย - Research'),
            array('COM00000000002', 'วิทยานิพนธ์ - Thesis'),
            array('COM00000000004', 'บทความทางวิชาการ - Academic Article'),
            array('COM00000000005', 'หนังสือ - Book'),
            array('COM00000000006', 'รายงาน - Report')
        );
        foreach ($collections as $col) {
            $DATABASE->Query("INSERT INTO `tb_collection` (`collection_id`, `collection_name`) 
                              VALUES ('{$col[0]}', '{$col[1]}') 
                              ON DUPLICATE KEY UPDATE `collection_name` = '{$col[1]}'");
        }
    }

    function load_community()  {
        global $DATABASE;
        $sql = "SELECT * FROM tb_community ORDER BY community_id ASC";
        $response = array();
        $response['data'] = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function load_collection()  {
        global $DATABASE;
        // ดึงเฉพาะ 5 คอลเล็กชันตามที่ระบุในข้อกำหนด
        $target_ids = "'COM00000000001','COM00000000002','COM00000000004','COM00000000005','COM00000000006'";
        $sql = "SELECT * FROM tb_collection WHERE collection_id IN ($target_ids) ORDER BY FIELD(collection_id, 'COM00000000001','COM00000000002','COM00000000004','COM00000000005','COM00000000006')";
        $response = array();
        $response['data'] = $DATABASE->QueryObj($sql);
        return json_encode($response);
    }

    function add_item() {
        global $DATABASE;

        // 1. ตรวจสอบการเลือกชุมชน (รองรับทั้งแบบ array หลายชุมชนและแบบเดี่ยว)
        $community_ids = isset($_POST["community_id"]) ? $_POST["community_id"] : array();
        if (!is_array($community_ids)) {
            $community_ids = (!empty($community_ids) && $community_ids !== '0') ? array($community_ids) : array();
        }
        $community_ids = array_values(array_filter($community_ids, function($v) {
            return !empty($v) && $v !== '0';
        }));

        if (empty($community_ids)) {
            return json_encode(array(
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "กรุณาเลือกชุมชนอย่างน้อย 1 ชุมชน",
                "icon" => "error"
            ));
        }
        // ชุมชนหลักสำหรับฟิลด์เดิม tb_item.community_id เพื่อคงความเข้ากันได้กับส่วนเดิมทั้งหมด
        $primary_community_id = $community_ids[0];

        // 2. ตรวจสอบคอลเลกชัน
        $collection_id = isset($_POST["collection_id"]) ? trim($_POST["collection_id"]) : '0';
        if ($collection_id == '0' || empty($collection_id)) {
            return json_encode(array(
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "กรุณาเลือกคอลเลกชัน",
                "icon" => "error"
            ));
        }

        // 3. ตรวจสอบ Title (ชื่อเรื่อง)
        $item_title = isset($_POST["item_title"]) ? trim($_POST["item_title"]) : "";
        if (empty($item_title)) {
            return json_encode(array(
                "data" => "n",
                "title" => "ไม่สำเร็จ",
                "message" => "กรุณากรอก Title",
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
                        "message" => "พาธ: $newdir\nระบบแจ้ง: $sysErr\n(PHP User: $phpUser, สิทธิ์ $filesDir: $permInfo)",
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

        // บันทึกลง tb_item รวมฟิลด์ใหม่ (item_journal, item_volume, item_isbn)
        $item_data = array(
            'item_id' => $item_id,
            'item_title' => $item_title,
            'community_id' => $primary_community_id,
            'collection_id' => $collection_id,
            'item_alternative' => isset($_POST["item_alternative"]) ? trim($_POST["item_alternative"]) : "",
            'item_issued_month' => isset($_POST["item_issued_month"]) ? trim($_POST["item_issued_month"]) : "",
            'item_issued_year' => isset($_POST["item_issued_year"]) ? trim($_POST["item_issued_year"]) : "",
            'item_abstract' => isset($_POST["item_abstract"]) ? trim($_POST["item_abstract"]) : "",
            'item_sponsorship' => isset($_POST["item_sponsorship"]) ? trim($_POST["item_sponsorship"]) : "",
            'item_citation' => isset($_POST["item_citation"]) ? trim($_POST["item_citation"]) : "",
            'item_uri' => isset($_POST["item_uri"]) ? trim($_POST["item_uri"]) : "",
            'item_publisher' => isset($_POST["item_publisher"]) ? trim($_POST["item_publisher"]) : "",
            'item_journal' => isset($_POST["item_journal"]) ? trim($_POST["item_journal"]) : "",
            'item_volume' => isset($_POST["item_volume"]) ? trim($_POST["item_volume"]) : "",
            'item_isbn' => isset($_POST["item_isbn"]) ? trim($_POST["item_isbn"]) : ""
        );

        $insert = $DATABASE->QueryInsert('tb_item', $item_data);

        if ($insert) {
            // 1. บันทึกความสัมพันธ์หลายชุมชนใน tb_item_community
            foreach ($community_ids as $com_id) {
                $DATABASE->QueryInsert('tb_item_community', array(
                    'item_id' => $item_id,
                    'community_id' => $com_id
                ));
            }

            // 2. บันทึกไฟล์ใน tb_file
            if ($file_name != "") {
                $DATABASE->QueryInsert('tb_file', array(
                    'file_id' => $file_id,
                    'file_name' => $file_name,
                    'file_type' => 'file',
                    'file_description' => 'ไฟล์เนื้อหา',
                    'item_id' => $item_id
                ));
            }

            // 3. บันทึก Creater (tb_writer)
            if (isset($_POST['creator_name']) && is_array($_POST['creator_name'])) {
                $creator_names = $_POST['creator_name'];
                $creator_orgs = isset($_POST['creator_org']) && is_array($_POST['creator_org']) ? $_POST['creator_org'] : array();

                foreach ($creator_names as $idx => $raw_name) {
                    $raw_name = trim($raw_name);
                    $org = isset($creator_orgs[$idx]) ? trim($creator_orgs[$idx]) : '';
                    if ($raw_name === '' && $org === '') {
                        continue;
                    }
                    $parts = preg_split('/\s+/', $raw_name, 2);
                    $fname = isset($parts[0]) ? $parts[0] : '';
                    $lname = isset($parts[1]) ? $parts[1] : '';

                    $writer_id = $DATABASE->QueryMaxId("tb_writer", "writer_id", 'WRT', 11);
                    $writer_main = ($idx === 0) ? 1 : 2;

                    $DATABASE->QueryInsert('tb_writer', array(
                        'writer_id' => $writer_id,
                        'writer_fname' => $fname,
                        'writer_lname' => $lname,
                        'writer_organization' => $org,
                        'item_id' => $item_id,
                        'writer_main' => $writer_main
                    ));
                }
            } elseif (isset($_POST['writer_fname']) && is_array($_POST['writer_fname'])) {
                // รองรับ writer_fname เดิม
                $writer_fnames = $_POST['writer_fname'];
                $writer_lnames = isset($_POST['writer_lname']) && is_array($_POST['writer_lname']) ? $_POST['writer_lname'] : array();
                $writer_orgs = isset($_POST['writer_organization']) && is_array($_POST['writer_organization']) ? $_POST['writer_organization'] : array();
                foreach ($writer_fnames as $index => $writer_fname) {
                    $w_fname = trim($writer_fname);
                    $w_lname = isset($writer_lnames[$index]) ? trim($writer_lnames[$index]) : '';
                    $w_org = isset($writer_orgs[$index]) ? trim($writer_orgs[$index]) : '';
                    if ($w_fname === '' && $w_lname === '' && $w_org === '') {
                        continue;
                    }
                    $writer_id = $DATABASE->QueryMaxId("tb_writer", "writer_id", 'WRT', 11);
                    $writer_main = ($index === 0) ? 1 : 2;
                    $DATABASE->QueryInsert('tb_writer', array(
                        'writer_id' => $writer_id,
                        'writer_fname' => $w_fname,
                        'writer_lname' => $w_lname,
                        'writer_organization' => $w_org,
                        'item_id' => $item_id,
                        'writer_main' => $writer_main
                    ));
                }
            }

            // 4. บันทึก contributor (tb_contributor)
            if (isset($_POST['contributor_name']) && is_array($_POST['contributor_name'])) {
                $contributor_names = $_POST['contributor_name'];
                $contributor_roles = isset($_POST['contributor_role']) && is_array($_POST['contributor_role']) ? $_POST['contributor_role'] : array();

                foreach ($contributor_names as $idx => $cnt_name) {
                    $cnt_name = trim($cnt_name);
                    $cnt_role = isset($contributor_roles[$idx]) ? trim($contributor_roles[$idx]) : '';
                    if ($cnt_name === '' && $cnt_role === '') {
                        continue;
                    }
                    $DATABASE->QueryInsert('tb_contributor', array(
                        'contributor_name' => $cnt_name,
                        'contributor_role' => $cnt_role,
                        'item_id' => $item_id
                    ));
                }
            }

            // 5. บันทึก Subject (tb_subject)
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
