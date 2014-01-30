<?php
require_once 'variables.php';
$response = array();
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $link = mysqli_connect($db_server, $db_user, $db_password, $db);
    if (mysqli_connect_errno()) {
        $response['success'] = false;
        $response['message'] = $DB_ERROR;
        echo json_encode($response);
        mysqli_close($link);
        exit();
    }
    $response['friends'] = array();
    $query = "SELECT name2 FROM friends WHERE BINARY name1='$name';";
    $query .= "SELECT name1 FROM friends WHERE BINARY name2='$name'";
    if (mysqli_multi_query($link, $query)) {
        do {
            if ($result = mysqli_store_result($link)) {
                while ($row = mysqli_fetch_row($result)) {
                    array_push($response['friends'], $row[0]);
                }
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($link));
        $response['success'] = true;
        $response['message'] = $SUCCESS;
    } else {
        $response['success'] = false;
        $response['message'] = $QUERY_ERROR;
    }
    mysqli_close($link);
} else {
    $response['success'] = false;
    $response['message'] = $HTTP_POST_ERROR;
}
echo json_encode($response);
?>