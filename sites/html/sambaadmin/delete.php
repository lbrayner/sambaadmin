<?php
session_start();
include_once("tools/util.php");
if (!check_login()) {
	echo "unauthorized";
	die();
}
include_once ('tools/smbpasswd.php');
$ini = read_config();
$metadata_path = $ini ['metadata_path'];
$use_metadata = !is_null_or_empty_string($metadata_path);

$smbpasswd = new smbpasswd ($metadata_path);

if (isset ( $_POST['user'] )) {
	$user = $_POST['user'];
    $error_msg = "";
	if ($smbpasswd->user_delete($user,$error_msg)) {
		if ($use_metadata) {
			$smbpasswd->meta_delete($user);
		}
		echo "success";
	} else {
        echo $error_msg;
	}
	
} else {
	echo "error";
}
?>
