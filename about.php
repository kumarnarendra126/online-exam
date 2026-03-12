<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>About US</title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
 <link rel="stylesheet" href="css/main.css">
 <link  rel="stylesheet" href="css/font.css">
 <script src="js/jquery.js" type="text/javascript"></script>
  <script src="js/bootstrap.min.js"  type="text/javascript"></script>
 	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
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

<div class="bg2">
<div class="row" style="text-align:center; color:oldlace">
  <h2 style="text-align: center;">About Us</h2>

        <!-- Vision & Mission -->
        <div class="section">
            <h2>Vision & Mission</h2>
            <ul class="no-bullets">
                <li><b>Empowering students through interactive learning experiences.</b></li>
                <li><b>Creating a platform that enhances knowledge through quizzes.</b></li>
                <li><b>Ensuring easy access for students and educators.</b></li>
            </ul>
        </div>

        <!-- Quiz Instructions -->
        <div class="section">
            <h2>Quiz Instructions</h2>
            <ul class="no-bullets">
                <li><b>Read all questions carefully before answering.</b></li>
                <li><b>Time management is crucial — pace yourself.</b></li>
                <li><b>Ensure stable internet connectivity during the quiz.</b></li>
                <li><b>Click 'Submit' only after confirming all answers.</b></li>
                <li><b>Check your results instantly once submitted.</b></li>
            </ul>
        </div>

        <!-- Key Features -->
        <div class="section">
            <h2>Key Features</h2>
            <ul class="no-bullets">
                <li><b>User-friendly interface for smooth navigation.</b></li>
                <li><b>Instant result display for better feedback.</b></li>
                <li><b>Detailed performance analysis for improvement insights.</b></li>
                <li><b>Accessible anytime, anywhere for convenience.</b></li>
            </ul>
        </div>

        <!-- Platform Overview -->
        <div class="section">
            <h2>Platform Overview</h2>
            <ul class="no-bullets">
                <li><b>Efficient and user-friendly quiz system.</b></li>
                <li><b>Designed for students to practice and improve skills.</b></li>
                <li><b>Provides a seamless experience for students and educators.</b></li>
                <li><b>Developed under the Information Technology branch at M.D.D.C, Gorakhpur.</b></li>
            </ul>
        </div>

        <!-- Social Media Links -->
        <div class="section">
            <div class="social-links">
                <a href="https://instagram.com"><img src="image/instagram.webp" alt="Instagram" height='20'></a>
                <a href="https://www.facebook.com"><img src="image/facebook.webp" alt="Facebook" height='20' style="margin:20px"></a>
                <a href="https://telegram.org"><img src="image/telegram.webp" alt="Telegram" height='20'></a>
            </div>
        </div>
    </div>
</div>
</div><!--container end-->


<!--Footer start-->
<div class="row footer">
	<div class="col-md-2 box">
		<a href="about" target="_blank" style="font-weight: bold;">About us</a>
	</div>
	<div class="col-md-2 box">
		<a href="contact" target="_blank" style="font-weight: bold;">Contact Us</a>
	</div>
	<div class="col-md-3 box">
		<a href="#" data-toggle="modal" data-target="#login" style="font-weight: bold;">Admin Login</a></div>
	<div class="col-md-3 box">
		<a href="#" data-toggle="modal" data-target="#developers" style="font-weight: bold;">Developers</a>
	</div>
	<div class="col-md-2 box">
		<a href="feedback" target="_blank" style="font-weight: bold;">Feedback</a>
	</div>
</div>

<?php include 'developers_modal.php'; ?>

<?php include 'admin_login_modal.php'; ?>

<!--footer end-->


</body>
</html>
