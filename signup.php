<?php
require_once 'variables.php';
$response = array();
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $salt = "pepper";
    $password = hash('SHA512', $_POST['password'] . $salt);
    $link = mysqli_connect($db_server, $db_user, $db_password, $db);
    if (mysqli_connect_errno()) {
        $response['success'] = false;
        $response['message'] = $DB_ERROR;
        echo json_encode($response);
        mysqli_close($link);
        exit();
    }
    $success = true;
    if ($result = mysqli_query($link, "SELECT * FROM users WHERE BINARY name='$name'")) {
        if (mysqli_num_rows($result) != 0) {
            $success = false;
            $response['message'] = $USERNAME_TAKEN;
        }
        mysqli_free_result($result);
    } else {
        $success = false;
        $response['message'] = $QUERY_ERROR;
    }
    if ($success && $result = mysqli_query($link, "SELECT * FROM users WHERE email='$email'")) {
        if (mysqli_num_rows($result) != 0) {
            $success = false;
            $response['message'] = $EMAIL_TAKEN;
        }
        mysqli_free_result($result);
    } else {
        $success = false;
        $response['message'] = $QUERY_ERROR;
    }
    if ($success) {
        if (mysqli_query($link, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')")) {
            $response['success'] = true;
            $response['message'] = $SUCCESS;
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