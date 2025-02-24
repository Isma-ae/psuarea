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
        case 'load_community'		: echo load_community(); 		break;
        case 'add_community'		: echo add_community(); 	    break;
        case 'edit_community'	    : echo edit_community(); 	    break;
        case 'delete_community'	    : echo delete_community(); 	    break;
		default: break;
	}

    function load_community() {
        global $DATABASE;
        $limit = 5;
        $condition = "";
        $page = isset($_POST["page"]) && $_POST["page"] > 1 ? (int)$_POST["page"] : 1;
        $start = ($page - 1) * $limit;
        if (isset($_POST["query"])) {
            $query = "SELECT * FROM tb_community";
            $params = [];
            $types = "";
            $search_query = "";
            if (!empty($_POST["query"])) {
                $condition = trim(preg_replace('/[^A-Za-z0-9\- ]/', '', $_POST["query"]));
                $condition = str_replace(" ", "%", $condition);
                $search_query = " WHERE community_title LIKE ? OR community_description LIKE ?";
                $params[] = "%$condition%";
                $params[] = "%$condition%";
                $types .= "ss";
            }
            $stmt = $DATABASE->Prepare($query . $search_query);
            if ($stmt) {
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $stmt->store_result();
                $total_data = $stmt->num_rows;
                $stmt->close();
            } else {
                die(json_encode(['error' => 'SQL Error: ' . $DATABASE->error]));
            }
            $query .= $search_query . " ORDER BY community_id DESC LIMIT ?, ?";
            $stmt = $DATABASE->Prepare($query);
            if ($stmt) {
                $params[] = $start;
                $params[] = $limit;
                $types .= "ii";
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
        
                $data = [];
                $replace_array_1 = explode('%', $condition);
                $replace_array_2 = array_map(fn($word) => "<span style='background-color:#" . rand(100000, 999999) . "; color:#fff'>$word</span>", $replace_array_1);
        
                while ($row = $result->fetch_assoc()) {
                    $data[] = [
                        'community_id' => $row["community_id"],
                        'community_title' => str_ireplace($replace_array_1, $replace_array_2, $row["community_title"]),
                        'community_description' => str_ireplace($replace_array_1, $replace_array_2, $row["community_description"]),
                        'community_img' => str_ireplace($replace_array_1, $replace_array_2, $row["community_img"])
                    ];
                }
                $stmt->close();
            } else {
                die(json_encode(['error' => 'SQL Error: ' . $DATABASE->error]));
            }
            $DATABASE->Close();
            $total_pages = ceil($total_data / $limit);
            $pagination_html = '<div align="center"><ul class="pagination">';
            if ($page > 1) {
                $pagination_html .= '<li class="page-item"><a class="page-link" href="javascript:load_data(`' . $_POST["query"] . '`, ' . ($page - 1) . ')">Previous</a></li>';
            } else {
                $pagination_html .= '<li class="page-item disabled"><a class="page-link">Previous</a></li>';
            }
            for ($count = 1; $count <= $total_pages; $count++) {
                $active = $count == $page ? ' active' : '';
                $pagination_html .= '<li class="page-item' . $active . '"><a class="page-link" href="javascript:load_data(`' . $_POST["query"] . '`, ' . $count . ')">' . $count . '</a></li>';
            }
            if ($page < $total_pages) {
                $pagination_html .= '<li class="page-item"><a class="page-link" href="javascript:load_data(`' . $_POST["query"] . '`, ' . ($page + 1) . ')">Next</a></li>';
            } else {
                $pagination_html .= '<li class="page-item disabled"><a class="page-link">Next</a></li>';
            }
            $pagination_html .= '</ul></div>';
            echo json_encode([
                'data' => $data,
                'pagination' => $pagination_html,
                'total_data' => $total_data
            ]);
        }
    }

    function add_community() {
        global $DATABASE;
        $dir = "../../../files/community/";
        $community_id = $DATABASE->QueryMaxId("tb_community","community_id",'COM',11);
        $img = $_FILES["community_img"];
        $community_img = uploadFile($dir,$img,"community_".$community_id);
        $insert = $DATABASE->QueryInsert('tb_community',[
            'community_id' => $community_id,
            'community_title' => $_POST["community_title"],
            'community_description' => $_POST["community_description"],
            'community_img' => $community_img
        ]);
        if ($insert) {
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
        
    }

    function edit_community() {
        global $DATABASE;
        $dir = "../../../files/community/";
        $community_id = $_POST["community_id"];
        $img = $_FILES["community_img"];
        $community_img = uploadFile($dir,$img,"community_".$community_id);
        $update_img = ($community_img=="") ? "" : " ,'community_img' => $community_img";
        if ($community_img=="") {
            $update = $DATABASE->QueryUpdate("tb_community",[
                'community_title' => $_POST["community_title"],
                'community_description' => $_POST["community_description"]
            ],"community_id = '".$community_id."'");
        } else {
            $update = $DATABASE->QueryUpdate("tb_community",[
                'community_title' => $_POST["community_title"],
                'community_description' => $_POST["community_description"],
                'community_img' => $community_img
            ],"community_id = '".$community_id."'");
        }
        if ($update) {
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"แก้ไขชุมชนเรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            return json_encode([
                "data"=>"y",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถแก้ไขชุมชนได้",
                "icon"=>"error"
            ]);
        }
    }

    function delete_community() {
        global $DATABASE;
        $dir = "../../../files/community/";
        $obj = $DATABASE->QueryObj("SELECT * FROM tb_community WHERE community_id = '".$_POST["community_id"]."'");
        $delete = $DATABASE->QueryDelete("tb_community","community_id = '".$_POST["community_id"]."'");
        if ($delete) {
            deleteFile($dir,$obj[0]["community_img"]);
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"ลบชุมชนเรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            return json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถลบชุมชนนี้",
                "icon"=>"error"
            ]);
        }
        
    }