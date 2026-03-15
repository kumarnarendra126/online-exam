<?php
session_start();
if(isset($_SESSION["email"])){
    session_destroy();
}
include_once 'dbConnection.php';

$email = $_POST['email'];
$password = $_POST['password'];

$password = md5($password);

$stmt = $con->prepare("SELECT name FROM user WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

$count = $result->num_rows;

if ($count == 1) {
    while ($row = $result->fetch_assoc()) {
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
$stmt->close();
?>