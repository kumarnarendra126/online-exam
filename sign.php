<?php
session_start();
include_once 'dbConnection.php';
ob_start();
$name = $_POST['name'];
$name= ucwords(strtolower($name));
$gender = $_POST['gender'];
$email = $_POST['email'];
$college = $_POST['college'];
$mob = $_POST['mob'];
$password = $_POST['password'];
$password = md5($password);

$stmt = $con->prepare("INSERT INTO user(name, gender, college, email, mob, password) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $gender, $college, $email, $mob, $password);

if($stmt->execute())
{
    $_SESSION["email"] = $email;
    $_SESSION["name"] = $name;
    header("location:account.php?q=1");
}
else
{
    header("location:index.php?q7=Email Already Registered!!!");
}
$stmt->close();
ob_end_flush();
?>