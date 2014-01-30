<?php
require_once 'variables.php';
$response = array();
if (isset($_POST['name']) && isset($_POST['email'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $link = mysqli_connect($db_server, $db_user, $db_password, $db);
    if (mysqli_connect_errno()) {
        $response['success'] = false;
        $response['message'] = $DB_ERROR;
        echo json_encode($response);
        mysqli_close($link);
        exit();
    }
    if ($result = mysqli_query($link, "SELECT * FROM users WHERE email='$email'")) {
        if (mysqli_num_rows($result) == 0) {
            if (mysqli_query($link, "UPDATE users SET email='$email' WHERE BINARY name='$name'")) {
                $response['success'] = true;
                $response['message'] = $SUCCESS;
            } else {
                $response['success'] = false;
                $response['message'] = $QUERY_ERROR;
            }
        } else {
            $response['success'] = false;
            $response['message'] = $EMAIL_TAKEN;
        }
        mysqli_free_result($result);
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