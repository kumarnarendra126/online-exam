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

<!--home start-->
<?php if(@$_GET['q']==1) {

$result = mysqli_query($con,"SELECT * FROM quiz ORDER BY date DESC") or die('Error');
echo  '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
<tr><td><b>S.N.</b></td><td><b>Topic</b></td><td><b>Total question</b></td><td><b>Marks</b></td><td><b>Time limit</b></td><td></td></tr>';
$c=1;
while($row = mysqli_fetch_array($result)) {
	$title = $row['title'];
	$total = $row['total'];
	$correct = $row['correct'];
    $time = $row['time'];
	$eid = $row['eid'];
$q12=mysqli_query($con,"SELECT score FROM history WHERE eid='$eid' AND email='$email'" )or die('Error98');
$rowcount=mysqli_num_rows($q12);	
if($rowcount == 0){
	echo '<tr><td>'.$c++.'</td><td>'.$title.'</td><td>'.$total.'</td><td>'.$correct*$total.'</td><td>'.$time.'&nbsp;min</td>
	<td><b><a href="account.php?q=quiz&step=2&eid='.$eid.'&n=1&t='.$total.'" class="pull-right btn sub1" style="margin:0px;background:#99cc32"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Start</b></span></a></b></td></tr>';
}
else
{
echo '<tr style="color:#99cc32"><td>'.$c++.'</td><td>'.$title.'&nbsp;<span title="This quiz is already solve by you" class="glyphicon glyphicon-ok" aria-hidden="true"></span></td><td>'.$total.'</td><td>'.$correct*$total.'</td><td>'.$time.'&nbsp;min</td>
	<td><b><a href="summary.php?eid='.$eid.'" class="pull-right btn sub1" style="margin:0px;background:#99cc32"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;<span class="title1"><b>View</b></span></a></b></td></tr>';
}
}
$c=0;
echo '</table></div></div>';

}?>
<!--<span id="countdown" class="timer"></span>
<script>
var seconds = 40;
    function secondPassed() {
    var minutes = Math.round((seconds - 30)/60);
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = "0" + remainingSeconds; 
    }
    document.getElementById('countdown').innerHTML = minutes + ":" +    remainingSeconds;
    if (seconds == 0) {
        clearInterval(countdownTimer);
        document.getElementById('countdown').innerHTML = "Buzz Buzz";
    } else {    
        seconds--;
    }
    }
var countdownTimer = setInterval('secondPassed()', 1000);
</script>-->

<!--home closed-->

<script src="js/timer.js" type="text/javascript"></script>
<!--quiz start-->
<?php
if(@$_GET['q']== 'quiz' && @$_GET['step']== 2) {
    $eid=@$_GET['eid'];
    $sn=@$_GET['n'];
    $total=@$_GET['t'];
    if($sn == 1)
    {
        $q_history=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email'" ) or die(mysqli_error($con));
        if(mysqli_num_rows($q_history) == 0)
        {
            $q=mysqli_query($con,"INSERT INTO history(email, eid, score, level, correct, wrong, date, start_time) VALUES('$email','$eid' ,'0','0','0','0',NOW(), NOW())")or die(mysqli_error($con));
        }
    }
    $q_history=mysqli_query($con,"SELECT UNIX_TIMESTAMP(start_time) as start_timestamp FROM history WHERE eid='$eid' AND email='$email'" ) or die(mysqli_error($con));
    $row_history=mysqli_fetch_array($q_history);
    $start_time_ts = $row_history['start_timestamp'];
    
    $q_quiz=mysqli_query($con,"SELECT * FROM quiz WHERE eid='$eid' " ) or die(mysqli_error($con));
    $row_quiz=mysqli_fetch_array($q_quiz);
    $time = $row_quiz['time'];

    $end_time_ts = $start_time_ts + ($time * 60);
    

    $q_questions=mysqli_query($con,"SELECT * FROM questions WHERE eid='$eid' AND sn='$sn' " ) or die(mysqli_error($con));
    if(mysqli_num_rows($q_questions) == 0)
    {
        echo '<div class="panel" style="margin:5%;"><div class="alert alert-danger" role="alert">
              No questions found for this quiz.
            </div></div>';
    }
    else
    {
        echo '<div class="panel" style="margin:5%;">';
        $progress = ($sn-1)/$total * 100;
        echo '<div class="progress">
                <div class="progress-bar" role="progressbar" style="width: '.$progress.'%;" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100"></div>
              </div>';
        echo '<div class="row">
                <div class="col-md-6">
                    <h3 style="font-weight: bold;">Question &nbsp;'.$sn.'&nbsp;</h3>
                </div>
                <div class="col-md-6">
                    <div id="timer" style="font-size: 20px; font-weight: bold; color: #333; float: right;"></div>
                </div>
              </div>';
        $qid = '';
        while($row_questions=mysqli_fetch_array($q_questions) )
        {
            $qns=$row_questions['qns'];
            $qid=$row_questions['qid'];
            echo '<div class="card">
                    <div class="card-body">
                        <h5 class="card-title">'.$qns.'</h5>';
        }
        if($qid != '')
        {
            $q_options=mysqli_query($con,"SELECT * FROM options WHERE qid='$qid' " ) or die(mysqli_error($con));
            echo '<form action="update.php?q=quiz&step=2&eid='.$eid.'&n='.$sn.'&t='.$total.'&qid='.$qid.'" method="POST"  class="form-horizontal">
                    <ul class="list-group list-group-flush">';

            while($row_options=mysqli_fetch_array($q_options) )
            {
                $option=$row_options['option'];
                $optionid=$row_options['optionid'];
                echo'<li class="list-group-item">
                        <input type="radio" name="ans" value="'.$optionid.'"> '.$option.'
                     </li>';
            }
            echo'</ul>
                <div class="card-body">';
            if($sn < $total)
            {
                echo '<button type="submit" class="btn btn-primary" name="next"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>&nbsp;Next</button>';
            }
            if($sn == $total)
            {
                echo '<button type="submit" class="btn btn-success" name="submit"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>&nbsp;Submit</button>';
            }
            echo '</div>
                </form>
                </div>
                </div>';
        }

        echo '<script>
    // Automatically request full-screen mode
    function openFullscreen() {
      var elem = document.documentElement;
      if (elem.requestFullscreen) {
        elem.requestFullscreen();
      } else if (elem.mozRequestFullScreen) { /* Firefox */
        elem.mozRequestFullScreen();
      } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
        elem.webkitRequestFullscreen();
      } else if (elem.msRequestFullscreen) { /* IE/Edge */
        elem.msRequestFullscreen();
      }
    }
    openFullscreen();

    // Disable the browser\'s back button
    history.pushState(null, null, document.URL);
    window.addEventListener(\'popstate\', function () {
        history.pushState(null, null, document.URL);
    });

    // New client-side timer logic using timestamp
    var endTime = ' . $end_time_ts . ' * 1000; // in milliseconds
    var display = document.querySelector("#timer");

    var timerInterval = setInterval(function () {
        var now = new Date().getTime();
        var remaining = endTime - now;

        if (remaining <= 0) {
            clearInterval(timerInterval);
            var form = document.querySelector("form");
            if(form){
                form.submit();
            }
        } else {
            var minutes = Math.floor(remaining / 60000);
            var seconds = Math.floor((remaining % 60000) / 1000);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;
        }
    }, 1000);
</script>';
        echo '</div>';
    }
//header("location:dash.php?q=4&step=2&eid=$id&n=$total");
}
//result display
if(@$_GET['q']== 'result' && @$_GET['eid']) 
{
$eid=@$_GET['eid'];
$q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' " )or die('Error157');
echo  '<div class="panel">
<center><h1 class="title" style="color:#660033">Result</h1><center><br /><table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';

while($row=mysqli_fetch_array($q) )
{
$s=$row['score'];
$w=$row['wrong'];
$r=$row['correct'];
$qa=$row['level'];
echo '<tr style="color:#66CCFF"><td>Total Questions</td><td>'.$qa.'</td></tr>
      <tr style="color:#99cc32"><td>right Answer&nbsp;<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></td><td>'.$r.'</td></tr> 
	  <tr style="color:red"><td>Wrong Answer&nbsp;<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td><td>'.$w.'</td></tr>
	  <tr style="color:#66CCFF"><td>Score&nbsp;<span class="glyphicon glyphicon-star" aria-hidden="true"></span></td><td>'.$s.'</td></tr>';
}
$q=mysqli_query($con,"SELECT * FROM rank WHERE  email='$email' " )or die('Error157');
while($row=mysqli_fetch_array($q) )
{
$s=$row['score'];
echo '<tr style="color:#990000"><td>Overall Score&nbsp;<span class="glyphicon glyphicon-stats" aria-hidden="true"></span></td><td>'.$s.'</td></tr>';
}
echo '</table></div>';

}
?>
<!--quiz end-->
<?php
//history start
if(@$_GET['q']== 2) 
{
$q=mysqli_query($con,"SELECT * FROM history WHERE email='$email' ORDER BY date DESC " )or die('Error197');
echo  '<div class="panel title">
<table class="table table-striped title1" >
<tr style="color:red"><td><b>S.N.</b></td><td><b>Quiz</b></td><td><b>Question Solved</b></td><td><b>Right</b></td><td><b>Wrong<b></td><td><b>Score</b></td>';
$c=0;
while($row=mysqli_fetch_array($q) )
{
$eid=$row['eid'];
$s=$row['score'];
$w=$row['wrong'];
$r=$row['correct'];
$qa=$row['level'];
$q23=mysqli_query($con,"SELECT title FROM quiz WHERE  eid='$eid' " )or die('Error208');
while($row=mysqli_fetch_array($q23) )
{
$title=$row['title'];
}
$c++;
echo '<tr><td>'.$c.'</td><td>'.$title.'</td><td>'.$qa.'</td><td>'.$r.'</td><td>'.$w.'</td><td>'.$s.'</td></tr>';
}
echo'</table></div>';
}

//ranking start
if(@$_GET['q']== 3) 
{
$q=mysqli_query($con,"SELECT * FROM rank  ORDER BY score DESC " )or die('Error223');
echo  '<div class="panel title"><div class="table-responsive">
<table class="table table-striped title1" >
<tr style="color:red"><td><b>Rank</b></td><td><b>Name</b></td><td><b>Gender</b></td><td><b>College</b></td><td><b>Score</b></td></tr>';
$c=0;
while($row=mysqli_fetch_array($q) )
{
$e=$row['email'];
$s=$row['score'];
$q12=mysqli_query($con,"SELECT * FROM user WHERE email='$e' " )or die('Error231');
while($row=mysqli_fetch_array($q12) )
{$name=$row['name'];
$gender=$row['gender'];
$college=$row['college'];
}
$c++;
echo '<tr><td style="color:#99cc32"><b>'.$c.'</b></td><td>'.$name.'</td><td>'.$gender.'</td><td>'.$college.'</td><td>'.$s.'</td><td>';
}
echo '</table></div></div>';}
?>



</div></div></div></div>
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

<!-- Modal For Developers-->
<div class="modal fade title1" id="developers">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" style="font-family:'typo' "><span style="color:orange">Developers</span></h4>
      </div>
	  
      <div class="modal-body">
    <p>
        <div class="row">
            <div class="col-md-4">
               <img src="./image/abhishek.jpeg" width="140" height="140" alt="" class="img-rounded">
            </div>
            <div class="col-md-5">
                <h4>Abhishek Kumar</h4>
                <h4 style="color:#202020; font-family:'typo';font-size:16px" class="title1">+91 9616625629</h4>
                <h4 style="font-family:'typo';">abhishek007kum@gmail.com</h4>
                <h4 style="font-family:'typo';">M.D.D.C Gorakhpur</h4>
            </div>
        </div>
        <hr> <!-- Divider Line -->

        <div class="row">
            <div class="col-md-4">
                <img src="./image/abhishek-pal.jpeg" width="140" height="140" alt="" class="img-rounded">
            </div>
            <div class="col-md-5">
                <h4>Abhishek Pal</h4>
                <h4 style="color:#202020; font-family:'typo';font-size:16px" class="title1">+91  7370004059</h4>
                <h4 style="font-family:'typo';">abhishekpal7641@gmail.com</h4>
                <h4 style="font-family:'typo';">M.D.D.C Gorakhpur</h4>
            </div>
        </div>
        <hr> <!-- Divider Line -->

        <div class="row">
            <div class="col-md-4">
               <img src="./image/ansh.jpeg" width="140" height="140" alt="" class="img-rounded">
            </div>
            <div class="col-md-5">
                <h4>Ansh Raj Sharma</a>
                <h4 style="color:#202020; font-family:'typo';font-size:16px" class="title1">+91 9264943633</h4>
                <h4 style="font-family:'typo';">anshrajsharma1234@gmail.com</h4>
                <h4 style="font-family:'typo';">M.D.D.C Gorakhpur</h4>
            </div>
        </div>
    </p>
</div>
    
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--Modal for admin login-->
	 <div class="modal fade" id="login">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><span style="color:orange;font-family:'typo' ">LOGIN</span></h4>
      </div>
      <div class="modal-body title1">
<div class="row">
<div class="col-md-3"></div>
<div class="col-md-6">
<form role="form" method="post" action="admin.php?q=index.php">
<div class="form-group">
<input type="text" name="uname" maxlength="20"  placeholder="Admin user id" class="form-control"/> 
</div>
<div class="form-group">
<input type="password" name="password" maxlength="15" placeholder="Password" class="form-control"/>
</div>
<div class="form-group" align="center">
<input type="submit" name="login" value="Login" class="btn btn-primary" />
</div>
</form>
</div><div class="col-md-3"></div></div>
      </div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--footer end-->


</body>
</html>
