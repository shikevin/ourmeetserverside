<?php
require_once 'variables.php';
$response = array();
if (isset($_POST['name1']) && isset($_POST['name2']) && isset($_POST['befriend'])) {
    $name1 = $_POST['name1'];
    $name2 = $_POST['name2'];
    $befriend = (bool) $_POST['befriend'];
    $link = mysqli_connect($db_server, $db_user, $db_password, $db);
    if (mysqli_connect_errno()) {
        $response['success'] = false;
        $response['message'] = $DB_ERROR;
        echo json_encode($response);
        mysqli_close($link);
        exit();
    }
    if ($result = mysqli_query($link, "SELECT * FROM requests WHERE BINARY name1='$name1' AND BINARY name2='$name2'")) {
        if (mysqli_num_rows($result) > 0) {
            mysqli_free_result($result);
            if ($befriend) {
                $success = true;
                $query = "INSERT INTO friends (name1, name2) VALUES ('$name1', '$name2');";
                $query .= "DELETE FROM requests WHERE BINARY name1='$name1' AND BINARY name2='$name2'";
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
                    $response['message'] = $ADDED_FRIEND;
                } else {
                    $response['success'] = false;
                    $response['message'] = $QUERY_ERROR;
                }
            } else if (mysqli_query($link, "DELETE FROM requests WHERE BINARY name1='$name1' AND BINARY name2='$name2'")) {
                $response['success'] = true;
                $response['message'] = $DECLINED_REQUEST;
            } else {
                $response['success'] = false;
                $response['message'] = $QUERY_ERROR;
            }
        } else {
            $response['success'] = false;
            $response['message'] = $REQUEST_EXPIRED;
        }
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