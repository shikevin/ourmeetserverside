<?php
require_once 'variables.php';
$response = array();
if (isset($_POST['name1']) && isset($_POST['name2'])) {
    $name1 = $_POST['name1'];
    $name2 = $_POST['name2'];
    $link = mysqli_connect($db_server, $db_user, $db_password, $db);
    if (mysqli_connect_errno()) {
        $response['success'] = false;
        $response['message'] = $DB_ERROR;
        echo json_encode($response);
        mysqli_close($link);
        exit();
    }
    $success = true;
    $query = "DELETE FROM sessions WHERE BINARY name1='$name1' AND BINARY name2='$name2';";
    $query .= "DELETE FROM sessions WHERE BINARY name1='$name2' AND BINARY name2='$name1'";
    if (mysqli_multi_query($link, $query)) {
        while ($success && mysqli_more_results($link)) {
            if (!mysqli_next_result($link)) {
                $success = false;
            }
        }
    } else {
        $success = false;
    }
    if ($success) {
        $response['success'] = true;
        $response['message'] = $DELETED_SESSION;
    } else {
        $response['success'] = true;
        $response['message'] = $QUERY_ERROR;
    }
    mysqli_close($link);
} else {
    $response['success'] = false;
    $response['message'] = $HTTP_POST_ERROR;
}
echo json_encode($response);
?>