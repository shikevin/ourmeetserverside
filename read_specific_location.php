<?php

$response = array ();

require_once __DIR__ . '/db_connect.php';
$db = new DB_CONNECT();

if (isset($_POST["pid"])) {
	$pid=$_POST['pid'];
	
	$result = mysql_query("SELECT *FROM users WHERE pid= ".$pid."");

	if(!empty($result)) {
		if(mysql_num_rows($result)>0) {
			$result = mysql_fetch_array($result);

			$location=array();
			$location["pid"] = $result["pid"];
			$location["lo"] = $result["lo"];
			$location["la"] = $result["la"];
			$location["created_at"] = $result["created_at"];
			$location["updated_at"] = $result["updated_at"];

			$location["success"] = 1;
			$location["product"] = array();

			array_push($response["product"], $location);

			echo json_encode($response);

		} 
		else {

			$response["success"] = 0;
			$response["message"] = "No product found";
			echo json_encode($response);
		}
	}
	else {
		$response["success"] = 0;
		$response["message"] = "No product found";
		echo json_encode($response);

} 
}
else {

	$response["success"] = 0;
	$response["message"] = "Required field(s) is missing";

	echo json_encode($response);
}

?>
