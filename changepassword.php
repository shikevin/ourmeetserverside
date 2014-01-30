<?php
require_once 'variables.php';
$response = array();
if (isset($_POST['name']) && isset($_POST['password'])) {
    $name = $_POST['name'];
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
    if (mysqli_query($link, "UPDATE users SET password='$password' WHERE BINARY name='$name'")) {
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