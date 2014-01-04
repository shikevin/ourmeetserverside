<?php

$response = array ();

require_once __DIR__ . '/db_connect.php';
$db = new DB_CONNECT();

if (isset($_GET["pid"])) {
	$pid=$_GET['pid'];
	echo $pid;
	$result = mysql_query("SELECT * FROM users WHERE pid= $pid");


	if(!empty($result)) {
		if(mysql_num_rows($result)>0) {
			$result = mysql_fetch_array($result);

			$location=array();
			$location["pid"] = $result["pid"];
			$location["lo"] = $result["lo"];
			$location["la"] = $result["la"];
			$location["created_at"] = $result["created_at"];
			$location["updated_at"] = $result["updated_at"];

			$response["success"] = 1;
			$response["location"] = array();

			array_push($response["location"], $location);

			echo json_encode($response);

		} 
		else {

			$response["success"] = 0;
			$response["message"] = "No product found 2";
			echo json_encode($response);
		}
	}
	else {
		$response["success"] = 0;
		$response["message"] = "No product found 1";
		echo json_encode($response);

} 
}
else {

	$response["success"] = 0;
	$response["message"] = "Required field(s) is missing";

	echo json_encode($response);
}

?>
