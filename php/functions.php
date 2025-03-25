<?php
	include('config.php');
	include('class.connect-mysql.php');
	include('ssp.customized.class.php' );
	$DATABASE = new Database($HOST,$USER,$PASS,$DBNAME);
	//autoRemoveFileTemp(getRoot()."fileUpload/activity/temp/", 1800); // 1800=30 min

	function printR($data) {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
	function getRoot() {
		global $PROJECT_ROOT;
		$arr = explode("/",$_SERVER["PHP_SELF"]);
		$p = "";
		for( $i=sizeof($arr)-1 ; $i>0 ; $i-- ) {
			if( $arr[$i]==$PROJECT_ROOT ) break;
			$p .= "../";
		}
		return $p.$PROJECT_ROOT."/";
	}
	function getFileType($fileName) {
		$arr = explode(".", $fileName);
		if( sizeof($arr)==1 ) return "";
		return $arr[ sizeof($arr)-1 ];
	}
	function uploadFile($dir,$file,$rename="") {
		if( !isset($file["name"]) || !isset($file["name"][0]) ) return "";
		if( !isset($file["tmp_name"]) || !isset($file["tmp_name"][0]) ) return "";
		if (is_array($file["name"]) ) {
			$fileName = $file["name"][0];
			$fileTmp = $file["tmp_name"][0];
		} else {
			$fileName = $file["name"];
			$fileTmp = $file["tmp_name"];
		}
		$fileType = getFileType($fileName);
		$fileNameNew = "";
		if( $rename=="" ) $fileNameNew = $fileName;
		else $fileNameNew = $rename.".".$fileType;
		if( move_uploaded_file($fileTmp, $dir.$fileNameNew) ) {
			return $fileNameNew;
		}
		return "";
 	}
 	function deleteFile($dir, $filename) {
 		if ($filename!="" && file_exists($dir.$filename)) {
		    unlink($dir.$filename);
		}
 	}
 	function deleteFolder($folderPath) {
		if (!is_dir($folderPath)) {
			return false; // Not a directory
		}
	
		// Scan all files and subdirectories
		$files = array_diff(scandir($folderPath), array('.', '..'));
	
		// Loop through and delete each file/subdirectory
		foreach ($files as $file) {
			$filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
			
			if (is_dir($filePath)) {
				deleteFolder($filePath); // Recursively delete subdirectories
			} else {
				unlink($filePath); // Delete file
			}
		}
	
		return rmdir($folderPath); // Remove now-empty directory
	}