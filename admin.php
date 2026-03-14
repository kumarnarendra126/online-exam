<?php
include_once 'dbConnection.php';
$email = $_POST['uname'];
$password = $_POST['password'];

$email = stripslashes($email);
$email = addslashes($email);
$password = stripslashes($password); 
$password = addslashes($password);
$result = mysqli_query($con,"SELECT email FROM admin WHERE email = '$email' and password = '$password'") or die('Error');
$count=mysqli_num_rows($result);

if($count==1){
    session_start();
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
?>