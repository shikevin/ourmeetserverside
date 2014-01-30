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
    $query = "SELECT * FROM friends WHERE BINARY name1='$name1' AND BINARY name2='$name2';";
    $query .= "SELECT * FROM friends WHERE BINARY name1='$name2' AND BINARY name2='$name1';";
    $query .= "SELECT * FROM requests WHERE BINARY name1='$name1' AND BINARY name2='$name2';";
    $query .= "SELECT * FROM requests WHERE BINARY name1='$name2' AND BINARY name2='$name1'";
    if (mysqli_multi_query($link, $query)) {
        do {
            if ($success && $result = mysqli_store_result($link)) {
                if (mysqli_num_rows($result) > 0) {
                    $success = false;
                    $response['message'] = $INVALID_REQUEST;
                }
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($link));
    }
    if ($success && $result = mysqli_query($link, "SELECT * FROM users WHERE BINARY name='$name2'")) {
        if (mysqli_num_rows($result) == 0) {
            $success = false;
            $response['message'] = $NO_USER_EXISTS;
        }
        mysqli_free_result($result);
    }
    if ($success) {
        if (mysqli_query($link, "INSERT INTO requests (name1, name2) VALUES ('$name1', '$name2')")) {
            $response['success'] = true;
            $response['message'] = $ADDED_REQUEST;
        } else {
            $response['success'] = false;
            $response['message'] = $QUERY_ERROR;
        }
    } else {
        $response['success'] = false;
    }
    mysqli_close($link);
} else {
    $response['success'] = false;
    $response['message'] = $HTTP_POST_ERROR;
}
echo json_encode($response);
?>