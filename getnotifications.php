<?php
require_once 'variables.php';
$response = array();
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $success = true;
    $link = mysqli_connect($db_server, $db_user, $db_password, $db);
    if (mysqli_connect_errno()) {
        $response['success'] = false;
        $response['message'] = $DB_ERROR;
        echo json_encode($response);
        mysqli_close($link);
        exit();
    }
    $response['requests'] = array();
    if ($result = mysqli_query($link, "SELECT name1, updated FROM requests WHERE BINARY name2='$name'")) {
        while ($row = mysqli_fetch_row($result)) {
            $request = array();
            $request['name'] = $row[0];
            $request['time'] = strtotime($row[1]);
            array_push($response['requests'], $request);
        }
        mysqli_free_result($result);
    } else {
        $success = false;
    }
    $response['sessions'] = array();
    if ($result = mysqli_query($link, "SELECT name1, updated FROM sessions WHERE BINARY name2='$name'")) {
        while ($row = mysqli_fetch_row($result)) {
            $session = array();
            $session['name'] = $row[0];
            $session['time'] = strtotime($row[1]);
            array_push($response['sessions'], $session);
        }
        mysqli_free_result($result);
    } else {
        $success = false;
    }
    if ($success) {
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