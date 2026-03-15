<?php
include_once 'dbConnection.php';
session_start();
$email=$_SESSION['email'];
//delete feedback
if(isset($_SESSION['key'])){
if(@$_GET['fdid'] && $_SESSION['key']=='sunny7785068889') {
$id = mysqli_real_escape_string($con, $_GET['fdid']);
$result = mysqli_query($con,"DELETE FROM feedback WHERE id='$id' ") or die('Error');
header("location:dash.php?q=3");
}
}

//delete user
if(isset($_SESSION['key'])){
if(@$_GET['demail'] && $_SESSION['key']=='sunny7785068889') {
$demail = mysqli_real_escape_string($con, $_GET['demail']);
$r1 = mysqli_query($con,"DELETE FROM rank WHERE email='$demail' ") or die('Error');
$r2 = mysqli_query($con,"DELETE FROM history WHERE email='$demail' ") or die('Error');
$result = mysqli_query($con,"DELETE FROM user WHERE email='$demail' ") or die('Error');
header("location:dash.php?q=1");
}
}
//remove quiz
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'rmquiz' && $_SESSION['key']=='sunny7785068889') {
$eid = mysqli_real_escape_string($con, $_GET['eid']);
$result = mysqli_query($con,"SELECT * FROM questions WHERE eid='$eid' ") or die('Error');
while($row = mysqli_fetch_array($result)) {
	$qid = $row['qid'];
$r1 = mysqli_query($con,"DELETE FROM options WHERE qid='$qid'") or die('Error');
$r2 = mysqli_query($con,"DELETE FROM answer WHERE qid='$qid' ") or die('Error');
}
$r3 = mysqli_query($con,"DELETE FROM questions WHERE eid='$eid' ") or die('Error');
$r4 = mysqli_query($con,"DELETE FROM quiz WHERE eid='$eid' ") or die('Error');
$r4 = mysqli_query($con,"DELETE FROM history WHERE eid='$eid' ") or die('Error');

header("location:dash.php?q=5");
}
}

//add quiz
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'addquiz' && $_SESSION['key']=='sunny7785068889') {
$name = $_POST['name'];
$name= ucwords(strtolower($name));
$total = $_POST['total'];
$correct = $_POST['right'];
$wrong = $_POST['wrong'];
$time = $_POST['time'];
$tag = $_POST['tag'];
$desc = $_POST['desc'];
$id=uniqid();
$q3=mysqli_query($con,"INSERT INTO quiz VALUES  ('$id','$name' , '$correct' , '$wrong','$total','$time' ,'$desc','$tag', NOW())");

header("location:dash.php?q=4&step=2&eid=$id&n=$total");
}
}

//edit quiz
if(isset($_SESSION['key'])){
if(@$_GET['q'] == 'editquiz' && $_SESSION['key']=='sunny7785068889') {
$eid = @$_GET['eid'];
$name = $_POST['name'];
$total = $_POST['total'];
$correct = $_POST['right'];
$wrong = $_POST['wrong'];
$time = $_POST['time'];
$tag = $_POST['tag'];
$desc = $_POST['desc'];

$name = mysqli_real_escape_string($con, $name);
$tag = mysqli_real_escape_string($con, $tag);
$desc = mysqli_real_escape_string($con, $desc);
$total = (int)$total;
$correct = (int)$correct;
$wrong = (int)$wrong;
$time = (int)$time;

$q = mysqli_query($con, "UPDATE quiz SET title='$name', total='$total', correct='$correct', wrong='$wrong', time='$time', tag='$tag', `desc`='$desc' WHERE eid='$eid'") or die(mysqli_error($con));

header("location:dash.php?q=0&quiz_edited=true");
}
}

//edit questions
if(isset($_SESSION['key'])){
    if(@$_GET['q'] == 'editqns' && $_SESSION['key']=='sunny7785068889') {
        $eid = @$_GET['eid'];
        $num_questions = $_POST['num_questions'];

        for ($i = 1; $i <= $num_questions; $i++) {
            $qid = $_POST['qid' . $i];
            $qns = $_POST['qns' . $i];

            $qns = mysqli_real_escape_string($con, $qns);
            mysqli_query($con, "UPDATE questions SET qns='$qns' WHERE qid='$qid'") or die(mysqli_error($con));

            for ($j = 1; $j <= 4; $j++) {
                $option = $_POST['option' . $i . '_' . $j];
                $optionid = $_POST['optionid' . $i . '_' . $j];
                $option = mysqli_real_escape_string($con, $option);
                mysqli_query($con, "UPDATE options SET `option`='$option' WHERE optionid='$optionid'") or die(mysqli_error($con));
            }

            $ansid = $_POST['ans' . $i];
            mysqli_query($con, "UPDATE answer SET ansid='$ansid' WHERE qid='$qid'") or die(mysqli_error($con));
        }

        header("location:dash.php?q=0&questions_edited=true");
    }
}

//add question
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'addqns' && $_SESSION['key']=='sunny7785068889') {
$n=@$_GET['n'];
$eid=@$_GET['eid'];
$ch=@$_GET['ch'];

for($i=1;$i<=$n;$i++)
 {
 $qid=uniqid();
 $qns=$_POST['qns'.$i];
$q3=mysqli_query($con,"INSERT INTO questions VALUES  ('$eid','$qid','$qns' , '$ch' , '$i')");
  $oaid=uniqid();
  $obid=uniqid();
$ocid=uniqid();
$odid=uniqid();
$a=$_POST[$i.'1'];
$b=$_POST[$i.'2'];
$c=$_POST[$i.'3'];
$d=$_POST[$i.'4'];
$qa=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$a','$oaid')") or die('Error61');
$qb=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$b','$obid')") or die('Error62');
$qc=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$c','$ocid')") or die('Error63');
$qd=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$d','$odid')") or die('Error64');
$e=$_POST['ans'.$i];
switch($e)
{
case 'a':
$ansid=$oaid;
break;
case 'b':
$ansid=$obid;
break;
case 'c':
$ansid=$ocid;
break;
case 'd':
$ansid=$odid;
break;
default:
$ansid=$oaid;
}


$qans=mysqli_query($con,"INSERT INTO answer VALUES  ('$qid','$ansid')");

 }
header("location:dash.php?q=0");
}
}

//quiz start
if(@$_GET['q']== 'quiz' && @$_GET['step']== 2) {
    $eid = mysqli_real_escape_string($con, $_GET['eid']);
    $sn = mysqli_real_escape_string($con, $_GET['n']);
    $total = mysqli_real_escape_string($con, $_GET['t']);
    $ans = $_POST['ans'];
    $qid = mysqli_real_escape_string($con, $_GET['qid']);

    $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email'" ) or die(mysqli_error($con));
    $row=mysqli_fetch_array($q);
    $start_time = strtotime($row['start_time']);
    $q=mysqli_query($con,"SELECT * FROM quiz WHERE eid='$eid' " ) or die(mysqli_error($con));
    $row=mysqli_fetch_array($q);
    $time = $row['time'];
    $elapsed_time = time() - $start_time;

    if($elapsed_time >= ($time * 60)) {
        header("location:summary.php?eid=$eid");
    }

    $q=mysqli_query($con,"SELECT * FROM answer WHERE qid='$qid' " ) or die(mysqli_error($con));
    while($row=mysqli_fetch_array($q) )
    {
        $ansid=$row['ansid'];
    }
    $score = 0;
    if($ans == $ansid)
    {
        $q=mysqli_query($con,"SELECT * FROM quiz WHERE eid='$eid' " ) or die(mysqli_error($con));
        while($row=mysqli_fetch_array($q) )
        {
            $sahi=$row['correct'];
        }
        
        $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' ")or die(mysqli_error($con));

        while($row=mysqli_fetch_array($q) )
        {
            $s=$row['score'];
            $r=$row['correct'];
        }
        $r++;
        $s=$s+$sahi;
        $q=mysqli_query($con,"UPDATE `history` SET `score`=$s,`level`=$sn,`correct`=$r, date= NOW()  WHERE  email = '$email' AND eid = '$eid'")or die(mysqli_error($con));
        $score = $sahi;
    } 
    else
    {
        $q=mysqli_query($con,"SELECT * FROM quiz WHERE eid='$eid' " ) or die(mysqli_error($con));

        while($row=mysqli_fetch_array($q) )
        {
            $wrong=$row['wrong'];
        }
        if($sn == 1)
        {
            $q=mysqli_query($con,"INSERT INTO history(email, eid, score, level, correct, wrong, date, start_time) VALUES('$email','$eid' ,'0','0','0','0',NOW(), NOW() )")or die(mysqli_error($con));
        }
        $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' " )or die(mysqli_error($con));
        while($row=mysqli_fetch_array($q) )
        {
            $s=$row['score'];
            $w=$row['wrong'];
        }
        $w++;
        $s=$s-$wrong;
        $q=mysqli_query($con,"UPDATE `history` SET `score`=$s,`level`=$sn,`wrong`=$w, date=NOW() WHERE  email = '$email' AND eid = '$eid'")or die(mysqli_error($con));
        $score = -$wrong;
    }
    $q=mysqli_query($con,"INSERT INTO user_answer VALUES(NULL, '$email', '$eid', '$qid', '$ans', '$score')")or die(mysqli_error($con));

    if(isset($_POST['next']))
    {
        if($sn != $total)
        {
            $sn++;
            header("location:account.php?q=quiz&step=2&eid=$eid&n=$sn&t=$total")or die('Error152');
        }
    }
    else if(isset($_POST['submit']))
    {
        $q=mysqli_query($con,"SELECT score FROM history WHERE eid='$eid' AND email='$email'" )or die(mysqli_error($con));
        while($row=mysqli_fetch_array($q) )
        {
            $s=$row['score'];
        }
        $q=mysqli_query($con,"SELECT * FROM rank WHERE email='$email'" )or die(mysqli_error($con));
        $rowcount=mysqli_num_rows($q);
        if($rowcount == 0)
        {
            $q2=mysqli_query($con,"INSERT INTO rank VALUES('$email','$s',NOW())")or die(mysqli_error($con));
        }
        else
        {
            while($row=mysqli_fetch_array($q) )
            {
                $sun=$row['score'];
            }
            $sun=$s+$sun;
            $q=mysqli_query($con,"UPDATE `rank` SET `score`=$sun ,time=NOW() WHERE email= '$email'")or die(mysqli_error($con));
        }
        header("location:summary.php?eid=$eid");
    }
}

//restart quiz
if(@$_GET['q']== 'quizre' && @$_GET['step']== 25 ) {
$eid=@$_GET['eid'];
$n=@$_GET['n'];
$t=@$_GET['t'];
$q=mysqli_query($con,"SELECT score FROM history WHERE eid='$eid' AND email='$email'" )or die('Error156');
while($row=mysqli_fetch_array($q) )
{
$s=$row['score'];
}
$q=mysqli_query($con,"DELETE FROM `history` WHERE eid='$eid' AND email='$email' " )or die('Error184');
$q=mysqli_query($con,"SELECT * FROM rank WHERE email='$email'" )or die('Error161');
while($row=mysqli_fetch_array($q) )
{
$sun=$row['score'];
}
$sun=$sun-$s;
$q=mysqli_query($con,"UPDATE `rank` SET `score`=$sun ,time=NOW() WHERE email= '$email'")or die('Error174');
header("location:account.php?q=quiz&step=2&eid=$eid&n=1&t=$t");
}

?>



