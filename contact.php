<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ExamPoint - contact.php</title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
 <link rel="stylesheet" href="css/main.css">
 <link  rel="stylesheet" href="css/font.css">
 <script src="js/jquery.js" type="text/javascript"></script>
  <script src="js/bootstrap.min.js"  type="text/javascript"></script>
    <script src="js/login.js" type="text/javascript"></script>
  <script src="js/admin_login.js" type="text/javascript"></script>
 	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
   <style>
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #4a90e2;
            text-align: center;
        }
        .section {
            padding: 15px;
            margin-bottom: 15px;
        }
        .section p {
            margin: 5px 0;
        }
        .contact-info a {
            text-decoration: none;
            color: #4a90e2;
            font-weight: bold;
        }
        .feedback-section {
            text-align: center;
            margin-top: 20px;
        }
        .feedback-textarea {
            width: 40%;
            height: 100px;
            border: 2px solid #4a90e2;
            border-radius: 8px;
            padding: 10px;
            resize: none;
            font-size: 18px;
        }
        .feedback-button {
            background-color: #4a90e2;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 30px;
            font-size: 18px;
        }
    </style>
    <script>
        function sendFeedback() {
            const feedback = document.getElementById("feedback").value;
            if (feedback.trim() === "") {
                alert("Please enter your feedback before sending.");
            } else {
                alert("Feedback sent successfully!");
            }
        }
    </script>
	<!--alert message-->
<!--alert message end-->

</head>

<body>

<!--header start-->
<div class="row header">
<div class="col-lg-6">
<span class="logo">ExamPoint</span></div>
<div class="col-md-2">
</div>
<div class="col-md-4">
<?php
 include_once 'dbConnection.php';
session_start();
  if((!isset($_SESSION['email']))){
echo '<a href="#" class="pull-right sub1 btn title3" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>&nbsp;Signin</a>&nbsp;';}
else
{
echo '<a href="logout?q=feedback" class="pull-right sub1 btn title3"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;Signout</a>&nbsp;';}
?>

<a href="index" class="pull-right btn sub1 title3"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;Home</a>&nbsp;
</div></div>

<?php include 'signin_modal.php'; ?>

<!--header end-->

<div class="bg1">
<div class="row" style="height: 630px;">
    <div class="col-md-3"></div>
    <div class="col-md-6 panel" style="background-image:url(image/bg1.jpg); min-height:430px;">
<div class="row" style="text-align:center;">
  <h2 style="text-align: center; color:#000066; font-family:'typo';">Contact Us</h2>

         <!-- <div class="feedback-section">
            <textarea id="feedback" class="feedback-textarea" placeholder="Enter your feedback here..."></textarea>
            <br/>
            <button class="feedback-button" onclick="sendFeedback()">Send Feedback</button><br><br>
        </div> -->
        <div class="section">
            <p><b>Name:</b> Abhishek Kumar</p>
            <p><b>&#x2709; Email:</b> <a href="mailto:abhishek007kum@gmail.com">abhishek007kum@gmail.com</a></p>
            <p><b>&#x260E; Mobile:</b> <a href="tel:+917875335539">+91 9616625629</a></p>
        </div>
        <div class="section">
            <p><b>Name:</b> Abhishek Pal</p>
            <p><b>&#x2709; Email:</b> <a href="mailto:abhishekpal7641@gmail.com">abhishekpal7641@gmail.com</a></p>
            <p><b>&#x260E; Mobile:</b> <a href="tel:+919699171841">+91 7370004059</a></p>
        </div>
        <div class="section">
            <p><b>Name:</b> Ansh Raj Sharma</p>
            <p><b>&#x2709; Email:</b> <a href="mailto:anshrajsharma1234@gmail.com">anshrajsharma1234@gmail.com</a></p>
            <p><b>&#x260E; Mobile:</b> <a href="tel:+917498604273">+91 9264943633</a></p>
        </div>
    </div>
</div>
</div><!--container end-->


<!--Footer start-->
<div class="row footer">
	<div class="col-md-2 box">
		<a href="about.php" target="_blank" style="font-weight: bold;">About us</a>
	</div>
	<div class="col-md-2 box">
		<a href="contact.php" target="_blank" style="font-weight: bold;">Contact Us</a>
	</div>
	<div class="col-md-3 box">
		<a href="#" data-toggle="modal" data-target="#login" style="font-weight: bold;">Admin Login</a></div>
	<div class="col-md-3 box">
		<a href="#" data-toggle="modal" data-target="#developers" style="font-weight: bold;">Developers</a>
	</div>
	<div class="col-md-2 box">
		<a href="feedback.php" target="_blank" style="font-weight: bold;">Feedback</a>
	</div>
</div>

<?php include 'developers_modal.php'; ?>

<?php include 'admin_login_modal.php'; ?>

<!--footer end-->


</body>
</html>
