<?php
require_once 'variables.php';
$response = array();
if (isset($_POST['name1']) && isset($_POST['name2']) && isset($_POST['user']) && isset($_POST['lat']) && isset($_POST['long'])) {
    $name1 = $_POST['name1'];
    $name2 = $_POST['name2'];
    $user = (int) $_POST['user'];
    $lat = $_POST['lat'];
    $long = $_POST['long'];
    $link = mysqli_connect($db_server, $db_user, $db_password, $db);
    if (mysqli_connect_errno()) {
        $response['success'] = false;
        $response['message'] = $DB_ERROR;
        echo json_encode($response);
        mysqli_close($link);
        exit();
    }
    if ($user == 1 && mysqli_query($link, "UPDATE users SET lat='$lat', lon='$long' WHERE BINARY name='$name1'")) {
        mysqli_query($link, "UPDATE sessions SET connected1='1' WHERE BINARY name1='$name1' AND BINARY name2='$name2'");
    } else if ($user == 2 && mysqli_query($link, "UPDATE users SET lat='$lat', lon='$long' WHERE BINARY name='$name2'")) {
        mysqli_query($link, "UPDATE sessions SET connected2='1' WHERE BINARY name1='$name1' AND BINARY name2='$name2'");
    }
    $success = true;
    $response['users'] = array();
    $query = "SELECT lat, lon FROM users WHERE BINARY name='$name1';";
    $query .= "SELECT lat, lon FROM users WHERE BINARY name='$name2'";
    if (mysqli_multi_query($link, $query)) {
        do {
            if ($result = mysqli_store_result($link)) {
                $user = array();
                while ($row = mysqli_fetch_row($result)) {
                    $user['lat'] = $row[0];
                    $user['long'] = $row[1];
                }
                array_push($response['users'], $user);
                mysqli_free_result($result);
            } else {
                $success = false;
            }
        } while (mysqli_next_result($link));
    } else {
        $success = false;
    }
    if ($success && $result = mysqli_query($link, "SELECT connected1, connected2 FROM sessions WHERE BINARY name1='$name1' AND BINARY name2='$name2'")) {
        while ($row = mysqli_fetch_row($result)) {
            $response['connected1'] = (bool) $row[0];
            $response['connected2'] = (bool) $row[1];
        }
        mysqli_free_result($result);
    } else {
        $success = false;
    }
    if ($success) {
        $response['success'] = true;
        $response['message'] = $CONNECTED;
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