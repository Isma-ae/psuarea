<?php
	session_start();
	include("../../php/functions.php");
	$fn = isset( $_POST["fn"] ) ? $_POST["fn"] : "";
	switch ($fn) {
        case 'login'		: login(); 		break;
        case 'logout'		: logout(); 	break;
		default: break;
	}
    function login()
    {
		global $DATABASE; 
        $username = $_POST["user_name"];
        $password = $_POST["user_password"];
		$sql = "SELECT * FROM tb_user WHERE user_name = '".$username."'";
		$obj = $DATABASE->QueryObj($sql);
		if (sizeof($obj) == 1) {
			$auth = PSUPassport($username, $password, 4000);
			if ($auth != null) {
				$_SESSION["user_id"] = $obj[0]["user_id"];
				$_SESSION["user_name"] = $auth["username"];
				$_SESSION["user_fname"] = $auth["name"];
				$_SESSION["user_lname"] = $auth["sname"];
				echo json_encode([
					"title"=>"t",
					"message"=>"เข้าสู่ระบบสำเร็จ",
					"icon"=>"success"
				]);
			} else {
				echo json_encode([
					"title"=>"ไม่สำเร็จ",
					"message"=>"บัญชีผู้ใช้หรือรหัสผ่านไม่ถูกต้อง",
					"icon"=>"error"
				]);
			}
		} else {
			echo json_encode([
				"title"=>"ไม่สำเร็จ",
				"message"=>"คุณยังไม่ได้รับสิทธ์ในการใช้งานระบบ",
				"icon"=>"error"
			]);
		}
		
    }

    function logout()
	{
		session_destroy();
		echo "true";
	}



	function PSUPassport($username, $password, $timeout=5000) {
		try {
		 	include_once("../../php/class.soap-client-timeout.php");
		 	$client = new SoapClientTimeout("https://passport.psu.ac.th/authentication/authentication.asmx?wsdl", array(
		  		"timeout"=>$timeout
		 	));
		 	$resp = $client->Authenticate(array(
		  		'username'=> $username,
		  		'password'=> $password
		 	));
		 	if($resp->AuthenticateResult) {
		  		$resp = $client->GetUserDetails(array(
		   			'username'=> $username,
		   			'password'=> $password
		  		));
				$data = array();
				$arr = explode("@", $resp->GetUserDetailsResult->string[0]);
				$data["username"] = $arr[0];
				$data["name"] = $resp->GetUserDetailsResult->string[1];
				$data["sname"] = $resp->GetUserDetailsResult->string[2];
				$data["id"] = $resp->GetUserDetailsResult->string[3];
				switch($resp->GetUserDetailsResult->string[4]) {
					case "M" : case "1" : $data["sex"] = "ชาย"; break;
					case "F" : case "2" : $data["sex"] = "หญิง"; break;
					default : $data["sex"] = $resp->GetUserDetailsResult->string[4];
				}
				$data["gender"] = $resp->GetUserDetailsResult->string[4];
		  		$data["card_id"] = $resp->GetUserDetailsResult->string[5];
				$data["fac_id"] = $resp->GetUserDetailsResult->string[7];
		  		$data["department"] = $resp->GetUserDetailsResult->string[8];
		  		$data["campus"] = $resp->GetUserDetailsResult->string[10];
		  		$data["prefix"] = $resp->GetUserDetailsResult->string[12];
		  		$data["email"] = $resp->GetUserDetailsResult->string[13];
		  		$arr = explode(",", $resp->GetUserDetailsResult->string[14]);
		  		$arr = explode("=", $arr[4]);
		  		$data["type"] = $arr[1];        // Students/Staffs/Alumni
		  		$data["campus_id"] = $resp->GetUserDetailsResult->string[9]; // C02
		  		$data["old"] = $resp->GetUserDetailsResult->string;
		  		return $data;
			} else {
		  		return null;
			}
		} catch(Exception $e) {
			return null;
		}
	}
    
?>