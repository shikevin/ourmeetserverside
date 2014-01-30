<?php
require_once 'variables.php';
$response = array();
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $link = mysqli_connect($db_server, $db_user, $db_password, $db);
    if (mysqli_connect_errno()) {
        $response['success'] = false;
        $response['message'] = $DB_ERROR;
        echo json_encode($response);
        mysqli_close($link);
        exit();
    }
    if ($result = mysqli_query($link, "SELECT name FROM users WHERE email='$email'")) {
        $username = "";
        $newpassword = "";
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i < 8; $i++) {
            $newpassword .= $characters[rand(0, strlen($characters) - 1)];
        }
        if (mysqli_num_rows($result) == 1) {
            while ($row = mysqli_fetch_row($result)) {
                $username = $row[0];
            }
            mysqli_free_result($result);
            $message = "Your username is: " . $username . "\r\nYour password has been reset to: " . $newpassword . "\r\n- The Mapp Team";
            $message = wordwrap($message, 70, "\r\n");
            $headers = "From: The Mapp Team <recovery@mappfind.me>";
            $salt = "pepper";
            $hashedpassword = hash('SHA512', $newpassword . $salt);
            if (mysqli_query($link, "UPDATE users SET password='$hashedpassword' WHERE BINARY name='$username'") && mail($email, "Recovery", $message, $headers)) {
                $response['success'] = true;
                $response['message'] = $SUCCESS;
            } else {
                $response['success'] = false;
                $response['message'] = $RESET_FAIL;
            }
        } else {
            $response['success'] = false;
            $response['message'] = $NO_EMAIL_EXISTS;
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