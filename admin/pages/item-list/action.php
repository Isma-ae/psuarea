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
    $limit = 6;
    $condition = "";
    $page = isset($_POST["page"]) && $_POST["page"] > 1 ? (int)$_POST["page"] : 1;
    $start = ($page - 1) * $limit;
    if (isset($_POST["query"])) {
        $query = "SELECT 
                i.item_id, 
                i.item_title, 
                i.item_issued,
                GROUP_CONCAT(DISTINCT CONCAT(w.writer_prefix, ' ', w.writer_fname, ' ', w.writer_lname) 
                    ORDER BY w.writer_fname SEPARATOR ', ') AS writer_names
            FROM tb_item AS i
            LEFT JOIN tb_writer AS w ON i.item_id = w.item_id
        ";
        $params = [];
        $types = "";
        $search_query = "";
        if (!empty($_POST["query"])) {
            $condition = trim(htmlspecialchars($_POST["query"], ENT_QUOTES, 'UTF-8'));
            $condition = str_replace(" ", "%", $condition);
            $search_query = " WHERE i.item_title LIKE ? 
                            OR EXISTS (
                                SELECT 1 FROM tb_writer w 
                                WHERE w.item_id = i.item_id 
                                AND (w.writer_fname LIKE ? OR w.writer_lname LIKE ?)
                            )";
            $params[] = "%$condition%";
            $params[] = "%$condition%";
            $params[] = "%$condition%";
            $types .= "sss";
        }
        $stmt = $DATABASE->Prepare("SELECT COUNT(DISTINCT i.item_id) FROM tb_item AS i " . $search_query);
        if ($stmt) {
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $stmt->bind_result($total_data);
            $stmt->fetch();
            $stmt->close();
        } else {
            die(json_encode(['error' => 'SQL Error: ' . $DATABASE->error]));
        }
        $query .= $search_query . " 
            GROUP BY i.item_id
            ORDER BY i.item_id DESC 
            LIMIT ?, ?
        ";
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
                    'item_id' => $row["item_id"],
                    'item_title' => str_ireplace($replace_array_1, $replace_array_2, $row["item_title"]),
                    'writer_name' => str_ireplace($replace_array_1, $replace_array_2, $row["writer_names"]),
                    'item_issued' => str_ireplace($replace_array_1, $replace_array_2, $row["item_issued"])
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