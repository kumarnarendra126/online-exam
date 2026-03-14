<?php
session_start();
if(isset($_SESSION["email"])){
    session_destroy();
}
include_once 'dbConnection.php';

$email = $_POST['email'];
$password = $_POST['password'];

$email = stripslashes($email);
$email = addslashes($email);
$password = stripslashes($password);
$password = addslashes($password);
$password = md5($password);

$result = mysqli_query($con, "SELECT name FROM user WHERE email = '$email' and password = '$password'") or die('Error');
$count = mysqli_num_rows($result);

if ($count == 1) {
    while ($row = mysqli_fetch_array($result)) {
        $name = $row['name'];
    }
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;

    $redirect_url = 'account.php?q=1';

    echo json_encode(['success' => true, 'redirect' => $redirect_url]);
} else {
    session_destroy();
    echo json_encode(['success' => false, 'message' => 'Wrong Username or Password']);
}
?>