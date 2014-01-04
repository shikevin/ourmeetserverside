<?php

$response=array();

if (isset($_POST['pid'])&&isset($_POST['la']) && isset ($_POST['lo'])) {
	$pid = $_POST['pid'];
	$la= $_POST['la'];
	$lo=$_POST['lo'];

	require_once __DIR__ . '/db_connect.php';
	$db = new DB_CONNECT();
	$result=mysql_query("INSERT INTO users (pid, la, lo) VALUES ('$pid', '$la', $'lo') ON DUPLICATE KEY UPDATE la = VALUES ('$la'), lo = VALUES('$lo');

	if ($result) {
		$response['success'] = 1;
		$response['message'] = "Location successfully updated";
		echo json_encode($response);
	} else {
		$response['success'] = 0;
		$response['message'] = "Oops! An error occured.";
		echo json_encode($response);
	}
} else {
	$response['success']=0;
	$response['message'] = "Required field(s) is missing";
	echo json_encode($response);
}
?>
