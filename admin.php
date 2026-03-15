<?php
include_once 'dbConnection.php';
session_start();
$email = $_POST['uname'];
$password = $_POST['password'];

$stmt = $con->prepare("SELECT email FROM admin WHERE email = ? and password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

$count = $result->num_rows;

if($count==1){
    if(isset($_SESSION['email'])){
        session_unset();
    }
    $_SESSION["name"] = 'Admin';
    $_SESSION["key"] ='sunny7785068889';
    $_SESSION["email"] = $email;
    echo json_encode(['success' => true, 'redirect' => 'dash.php?q=0']);
}
else {
    echo json_encode(['success' => false, 'message' => 'Wrong Username or Password']);
}
$stmt->close();
?>