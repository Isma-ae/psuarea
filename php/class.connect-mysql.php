<?php
	/*
	*  	Author	: Srikee Eadtrong
	*  	Company	: Computer Center PSU Pattani
	*  	Date	: 28/09/2022 22.57
	*	Version : 2.0.4
	*  	
	*/
	class Database {
		var $host = null;
		var $user = null;
		var $pass = null;
		var $dbname = null;
		var $charset = null;
		var $conn = null;
		var $error = "";
		var $sql = "";
		public function __construct($host, $user, $pass, $dbname, $charset="UTF8") {
			$this->host = $host;
			$this->user = $user;
			$this->pass = $pass;
			$this->dbname = $dbname;
			$this->charset = $charset;
		}

		public function Close() {
			if(!$this->conn->connect_error) return $this->conn->close();
			return null;
		}
		private function Connect() {
			$host = $this->host;
			$user = $this->user;
			$pass = $this->pass;
			$dbname = $this->dbname;
			$this->conn = @new mysqli( $host , $user , $pass, $dbname );
			$this->error = "";
			if ($this->conn->connect_error) {
				//die("Connection failed: [".$this->conn->connect_errno."] " . $this->conn->connect_error);
				echo "Connection failed: [".$this->conn->connect_errno."] " . $this->conn->connect_error;
				$this->error = "Connection failed: [".$this->conn->connect_errno."] " . $this->conn->connect_error;
				$this->conn = null;
			} else {
				$this->conn->query("SET NAMES ".$this->charset);
				$this->conn->query("SET sql_mode = 'NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'"); // ยกเลิกการ strict ฟิล์ดข้อมูลว่าง
			}
		}

		public function Prepare($sql) {
			$this->sql = $sql;
			$this->Connect();
			if ($this->conn === null) {
				die("Database connection is null.");
			}
			return $this->conn->prepare($sql);
		}
	
		public function Error() {
			return $this->error;
		}
		public function Escape($data, $type='escape') {
			// type='escape' 	=> sql : insert or edit only
			// type='display'  	=> sql : select
			$data = trim( $data );
			if($type=="escape") {
				$this->Connect();
				$q = mysqli_real_escape_string($this->conn, $data);
				$this->Close();
				return $q;
			}
			if($type=="display") return htmlspecialchars($data);
			return $data;
		}
		public function Query($sql) {
			$this->sql = $sql;
			$this->Connect();
			if( $this->conn!=null ) {
				$q = $this->conn->query($sql);
				if( !$q ) $this->error = $this->conn->error;
				$this->Close();
				return $q;
			}
			return null;
		}
		public function QueryObj($sql, $index="") {
			$obj = array();
			$result = $this->Query($sql);
			if( $result!=null ) {
				while($row = $result->fetch_assoc()) {
					if($index=="") $obj[] = $row;
					else $obj[$row[$index]] = $row;
				}
				return $obj;
			}
			return array();
		}
		public function QueryJson($sql, $index="") {
			$obj = $this->QueryObj($sql, $index="");
			return json_encode($obj);
		}
		public function QueryString($sql) {
			$result = $this->Query($sql);
			if( $result!=null ) {
				if($row = $result->fetch_array()) {
					return $row[0];
				}
			}
			return null;
		}
		public function QueryNumRow($sql) {
			$result = $this->Query($sql);
			if( $result!=null ) {
				return $result->num_rows;
			}
			return 0;
		}
		public function QueryMaxId($table, $feild, $schar="", $digit=0, $replace="0") {
			if($digit==0)
				$sql = "SELECT CONCAT( '".$schar."' , IFNULL(MAX(".$feild."),0) + 1 ) as ID FROM ".$table.";";
			else
				$sql = "
						SELECT 
							CONCAT(
									'".$schar."',
									LPAD(	
										IFNULL( SUBSTR( MAX( ".$feild." ) , CHAR_LENGTH('".$schar."') + 1 ) , 0 ) + 1 , 
										".$digit." , 
										'".$replace."'
									)
							) as ID
						FROM ".$table.";";
			return $this->QueryString($sql);
		}
		public function QueryHaving($table, $feild_name, $feild_name_value, $not_feild_id="", $not_feild_id_value="") {
			$sql = "SELECT * FROM `".$table."` WHERE ".$feild_name."='".$this->Escape($feild_name_value)."'  ";
			if( $not_feild_id!="" ) $sql .= "AND `".$not_feild_id."`!='".$this->Escape($not_feild_id_value)."' ";
			if( $this->QueryNumRow($sql)==0 ) return false;
			return true;
		}
		public function QueryInsert($table, $data) {
			foreach($data as $key=>$val) {
				$data[$key] = $this->Escape($val);
			}
			$sql = "INSERT INTO `".$table."` (`".implode( "`,`", array_keys($data) )."`) VALUES ('".implode( "','", array_values($data) )."')";
			return $this->Query($sql);
		}
		public function QueryUpdate($table, $data, $condition) {
			$setfeild = array();
			foreach($data as $key=>$val) {
                if( strtoupper($val)=="NULL" ) $setfeild[] = "`".$key."`=".strtoupper(trim($val))."";
				else $setfeild[] = "`".$key."`='".$this->Escape($val)."'";
			}
			$sql = "UPDATE `".$table."` SET ".implode(",", $setfeild)."  WHERE ".$condition;
			return $this->Query($sql);
		}
		public function QueryDelete($table, $condition) {
			$sql = "DELETE FROM `".$table."`  WHERE ".$condition;
			return $this->Query($sql);
		}
	}