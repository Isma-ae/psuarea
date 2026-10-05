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
        case 'load_type'		: echo load_type(); 		break;
        case 'add_type'		: echo add_type(); 	    break;
        case 'edit_type'	    : echo edit_type(); 	    break;
        case 'delete_type'	    : echo delete_type(); 	    break;
		default: break;
	}

    function load_type() {
        global $DATABASE;
        $limit = 6;
        $condition = "";
        $page = isset($_POST["page"]) && $_POST["page"] > 1 ? (int)$_POST["page"] : 1;
        $start = ($page - 1) * $limit;
        if (isset($_POST["query"])) {
            $query = "SELECT * FROM tb_type";
            $params = [];
            $types = "";
            $search_query = "";
            if (!empty($_POST["query"])) {
                $condition = trim(htmlspecialchars($_POST["query"], ENT_QUOTES, 'UTF-8'));
                $condition = str_replace(" ", "%", $condition);
                $search_query = " WHERE type_name LIKE ?";
                $params[] = "%$condition%";
                $types .= "s";
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
            $query .= $search_query . " ORDER BY type_id DESC LIMIT ?, ?";
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
                $replace_array_2 = array_map(function($word) {
                    return "<span style='background-color:#" . rand(100000, 999999) . "; color:#fff'>$word</span>";
                }, $replace_array_1);
        
                while ($row = $result->fetch_assoc()) {
                    $data[] = [
                        'type_id' => $row["type_id"],
                        'type_name' => str_ireplace($replace_array_1, $replace_array_2, $row["type_name"]),
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

    function add_type() {
        global $DATABASE;
        $type_id = $DATABASE->QueryMaxId("tb_type","type_id");
        $insert = $DATABASE->QueryInsert('tb_type',['type_id' => $type_id,'type_name' => $_POST["type_name"]]);
        if ($insert) {
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"เพิ่มหมวดหมู่เรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            return json_encode([
                "data"=>"y",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถเพิ่มหมวดหมู่ได้",
                "icon"=>"error"
            ]);
        }
        
    }

    function edit_type() {
        global $DATABASE;
        $update = $DATABASE->QueryUpdate("tb_type",['type_name' => $_POST["type_name"]],"type_id = '".$_POST["type_id"]."'");
        if ($update) {
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"แก้ไขหมวดหมู่เรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            return json_encode([
                "data"=>"y",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถแก้ไขหมวดหมู่ได้",
                "icon"=>"error"
            ]);
        }
    }

    function delete_type() {
        global $DATABASE;
        $delete = $DATABASE->QueryDelete("tb_type","type_id = '".$_POST["type_id"]."'");
        if ($delete) {
            return json_encode([
                "data"=>"y",
                "title"=>"สำเร็จ",
                "message"=>"ลบหมวดหมู่เรียบร้อย",
                "icon"=>"success"
            ]);
        } else {
            return json_encode([
                "data"=>"n",
                "title"=>"ไม่สำเร็จ",
                "message"=>"ไม่สามารถลบหมวดหมู่นี้",
                "icon"=>"error"
            ]);
        }
        
    }