<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Online Examination System</title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
 <link rel="stylesheet" href="css/main.css">
 <link  rel="stylesheet" href="css/font.css">
 <script src="js/jquery.js" type="text/javascript"></script>

 
  <script src="js/bootstrap.min.js"  type="text/javascript"></script>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
 <!--alert message-->
<?php if(@$_GET['w'])
{echo'<script>alert("'.@$_GET['w'].'");</script>';}
?>
<!--alert message end-->

</head>
<?php
include_once 'dbConnection.php';
?>
<body>
<div class="header">
<div class="row">
<div class="col-lg-6">
<span class="logo">ExamPoint</span></div>
<div class="col-md-4 col-md-offset-2">
 <?php
 include_once 'dbConnection.php';
session_start();
  if(!(isset($_SESSION['email']))){
header("location:index.php");

}
else
{
$name = $_SESSION['name'];
$email=$_SESSION['email'];

include_once 'dbConnection.php';
echo '<span class="pull-right top title1">
        <span class="log1">
          <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;<strong>Hello,</strong>
        </span> 
        <a href="account.php?q=1" class="log log1"><strong>'.$name.'</strong></a>&nbsp;|&nbsp;
        <a href="logout.php?q=account.php" class="log">
          <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;<strong>Signout</strong>
        </a>
      </span>';
}?>
</div>
</div></div>
<div class="bg">

<!--navigation menu-->
<nav class="navbar navbar-default title1">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <?php if(@$_GET['q']==1) echo 'class="active"'; ?> >
          <a href="account.php?q=1" style="font-weight: bold;">
            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;Home<span class="sr-only">(current)</span>
          </a>
        </li>
        <li <?php if(@$_GET['q']==2) echo 'class="active"'; ?> >
          <a href="account.php?q=2" style="font-weight: bold;">
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;History
          </a>
        </li>
        <li <?php if(@$_GET['q']==3) echo 'class="active"'; ?> >
          <a href="account.php?q=3" style="font-weight: bold;">
            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp;Ranking
          </a>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav><!--navigation menu closed-->

<div class="container"><!--container start-->
<div class="row">
<div class="col-md-12">
<?php
if(@$_GET['eid'])
{
    $eid=@$_GET['eid'];
    $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' " )or die('Error157');
    echo  '<div class="panel">
    <center><h1 class="title" style="color:#660033">Test Summary</h1><center><br />';
    echo '<table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';
    echo '<tr style="color:red"><td>Question</td><td>Your Answer</td><td>Correct Answer</td><td>Score</td></tr>';
    $q=mysqli_query($con,"SELECT * FROM questions WHERE eid='$eid'" )or die('Error157');
    $i = 1;
    while($row=mysqli_fetch_array($q) )
    {
                $qns=$row['qns'];
                $qid=$row['qid'];
        
                // Get user's answer
                $q2=mysqli_query($con,"SELECT * FROM user_answer WHERE eid='$eid' AND email='$email' AND qid='$qid'" )or die('Error157');
                $user_ans = "Not Answered";
                $user_ans_id = null;
                if(mysqli_num_rows($q2) > 0) {
                    $row2=mysqli_fetch_array($q2);
                    $user_ans_id = $row2['ans'];
                    $q3=mysqli_query($con,"SELECT * FROM options WHERE optionid='$user_ans_id'" )or die('Error157');
                    if (mysqli_num_rows($q3) > 0) {
                        $row3=mysqli_fetch_array($q3);
                        $user_ans = $row3['option'];
                    }
                }
        
                // Get correct answer
                $q4=mysqli_query($con,"SELECT * FROM answer WHERE qid='$qid'" )or die('Error157');
                $correct_ans = "N/A";
                $correct_ans_id = null;
                if (mysqli_num_rows($q4) > 0) {
                    $row4=mysqli_fetch_array($q4);
                    $correct_ans_id = $row4['ansid'];
                    $q5=mysqli_query($con,"SELECT * FROM options WHERE optionid='$correct_ans_id'" )or die('Error157');
                    if (mysqli_num_rows($q5) > 0) {
                        $row5=mysqli_fetch_array($q5);
                        $correct_ans = $row5['option'];
                    }
                }
        
                // Calculate score for this question
                $score = 0;
                if($user_ans_id !== null && $user_ans_id == $correct_ans_id)
                {
                    $q6=mysqli_query($con,"SELECT `correct` FROM quiz WHERE eid='$eid'" )or die('Error157');
                    if (mysqli_num_rows($q6) > 0) {
                        $row6=mysqli_fetch_array($q6);
                        $score = $row6['correct'];
                    }
                }
        
                echo '<tr><td>'.$i++.'. '.$qns.'</td><td>'.$user_ans.'</td><td>'.$correct_ans.'</td><td>'.$score.'</td></tr>';
    }
    echo '</table>';

    $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' " )or die('Error157');
    $row=mysqli_fetch_array($q);
    $s=$row['score'];
    $w=$row['wrong'];
    $r=$row['correct'];
    $qa=$row['level'];
    echo '<br /><table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';
    echo '<tr style="color:#66CCFF"><td>Total Questions</td><td>'.$qa.'</td></tr>
      <tr style="color:#99cc32"><td>right Answer&nbsp;<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></td><td>'.$r.'</td></tr> 
	  <tr style="color:red"><td>Wrong Answer&nbsp;<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td><td>'.$w.'</td></tr>
	  <tr style="color:#66CCFF"><td>Score&nbsp;<span class="glyphicon glyphicon-star" aria-hidden="true"></span></td><td>'.$s.'</td></tr>';

    $q=mysqli_query($con,"SELECT * FROM rank WHERE  email='$email' " )or die('Error157');
    while($row=mysqli_fetch_array($q) )
    {
        $s=$row['score'];
        echo '<tr style="color:#990000"><td>Overall Score&nbsp;<span class="glyphicon glyphicon-stats" aria-hidden="true"></span></td><td>'.$s.'</td></tr>';
    }
    echo '</table></div>';
}
?>
</div>
</div>
</div>
</div>
</body>
</html>