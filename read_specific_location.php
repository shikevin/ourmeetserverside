<?php

$response = array ();

require_once __DIR__ . '/db_connect.php';
$db = new DB_CONNECT();

print_r($_GET);

if ($_GET['pid']==="") echo "pid is an empty string \n";
if($_GET["pid"] === false) echo "a is false\n";
if($_GET["pid"] === null) echo "a is null\n";
if(isset($_GET["pid"])) echo "a is set\n";
if(!empty($_GET["pid"])) echo "a is not empty";


if (isset($_GET["pid"])) {
	echo "pid is" .htmlspecialchars($_GET['$pid']) . '.';
	$pid=$_GET['pid'];
	
	$result = mysql_query("SELECT *FROM users WHERE pid= '.$pid.'");


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
