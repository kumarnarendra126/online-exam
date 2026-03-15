<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
session_start();
include_once 'dbConnection.php';

if (!isset($_SESSION['email'])) {
    header("location:index.php");
    exit();
}

$name = htmlspecialchars($_SESSION['name']);
$email = htmlspecialchars($_SESSION['email']);

$q = $_GET['q'] ?? 1;

function getQuizHistory($con, $email, $eid)
{
    error_log("getQuizHistory called with email: " . $email . " and eid: " . $eid);
    $stmt = $con->prepare("SELECT score FROM history WHERE eid=? AND email=?");
    if ($stmt === false) {
        error_log("Failed to prepare statement: " . $con->error);
        return false;
    }
    $stmt->bind_param("ss", $eid, $email);
    if (!$stmt->execute()) {
        error_log("Failed to execute statement: " . $stmt->error);
        $stmt->close();
        return false;
    }
    $result = $stmt->get_result();
    $row_count = $result->num_rows;
    error_log("getQuizHistory row_count: " . $row_count);
    $stmt->close();
    return $row_count > 0;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Online Examination System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/font.css">
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <?php if (isset($_GET['w'])) {
        echo '<script>alert("' . htmlspecialchars($_GET['w']) . '");</script>';
    } ?>
</head>
<body>
<div class="header">
    <div class="row">
        <div class="col-lg-6">
            <span class="logo">ExamPoint</span></div>
        <div class="col-md-4 col-md-offset-2">
            <span class="pull-right top title1">
                <span class="log1">
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;<strong>Hello,</strong>
                </span>
                <a href="account.php?q=1" class="log log1"><strong><?php echo $name; ?></strong></a>&nbsp;|&nbsp;
                <a href="logout.php?q=account.php" class="log">
                    <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;<strong>Signout</strong>
                </a>
            </span>
        </div>
    </div>
</div>
<div class="bg">

    <!--navigation menu-->
    <nav class="navbar navbar-default title1">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li <?php if ($q == 1) echo 'class="active"'; ?> >
                        <a href="account.php?q=1" style="font-weight: bold;">
                            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;Home<span
                                    class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li <?php if ($q == 2) echo 'class="active"'; ?> >
                        <a href="account.php?q=2" style="font-weight: bold;">
                            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;History
                        </a>
                    </li>
                    <li <?php if ($q == 3) echo 'class="active"'; ?> >
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
                <?php if ($q == 1) {
                    $stmt = $con->prepare("SELECT * FROM quiz ORDER BY date DESC");
                    if ($stmt === false) {
                        error_log("Failed to prepare statement: " . $con->error);
                        echo '<div class="panel"><div class="alert alert-danger" role="alert">Could not load quizzes. Please try again later.</div></div>';
                    } else {
                        $stmt->execute();
                        $result = $stmt->get_result();

                        echo '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
<tr><td><b>S.N.</b></td><td><b>Topic</b></td><td><b>Total question</b></td><td><b>Marks</b></td><td><b>Time limit</b></td><td></td></tr>';
                        $c = 1;
                        while ($row = $result->fetch_assoc()) {
                            $title = htmlspecialchars($row['title']);
                            $total = htmlspecialchars($row['total']);
                            $correct = htmlspecialchars($row['correct']);
                            $time = htmlspecialchars($row['time']);
                            $eid = htmlspecialchars($row['eid']);

                            if (isset($_SESSION['key']) && $_SESSION['key'] == 'sunny7785068889') {
                                echo '<tr><td>' . $c++ . '</td><td>' . $title . '</td><td>' . $total . '</td><td>' . $correct * $total . '</td><td>' . $time . '&nbsp;min</td>
        <td><b><a href="dash.php?q=4&step=2&eid=' . $eid . '&n=' . $total . '" class="pull-right btn sub1" style="margin:0px;background:#99cc32"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;<span class="title1"><b>View Details</b></span></a></b></td></tr>';
                            } else {
                                if (!getQuizHistory($con, $email, $eid)) {
                                    echo '<tr><td>' . $c++ . '</td><td>' . $title . '</td><td>' . $total . '</td><td>' . $correct * $total . '</td><td>' . $time . '&nbsp;min</td>
            <td><b><a href="account.php?q=quiz&step=2&eid=' . $eid . '&n=1&t=' . $total . '" class="pull-right btn sub1" style="margin:0px;background:#99cc32"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Start</b></span></a></b></td></tr>';
                                } else {
                                    echo '<tr style="color:#99cc32"><td>' . $c++ . '</td><td>' . $title . '&nbsp;<span title="This quiz is already solve by you" class="glyphicon glyphicon-ok" aria-hidden="true"></span></td><td>' . $total . '</td><td>' . $correct * $total . '</td><td>' . $time . '&nbsp;min</td>
            <td><b><a href="summary.php?eid=' . $eid . '" class="pull-right btn sub1" style="margin:0px;background:#99cc32"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;<span class="title1"><b>View</b></span></a></b></td></tr>';
                                }
                            }
                        }
                        $c = 0;
                        echo '</table></div></div>';
                        $stmt->close();
                    }
                } ?>
                <!--home end-->

                <!--quiz start-->
                <?php if ($q == 'quiz' && isset($_GET['step']) && $_GET['step'] == 2) {
                    $eid = $_GET['eid'];
                    $sn = $_GET['n'];
                    $total = $_GET['t'];

                    if ($sn == 1) {
                        if (getQuizHistory($con, $email, $eid)) {
                            header("location:summary.php?eid=$eid");
                            exit();
                        } else {
                            $stmt = $con->prepare("INSERT INTO history(email, eid, score, level, correct, wrong, date, start_time) VALUES(?,?, '0','0','0','0',NOW(), NOW())");
                            if ($stmt === false) {
                                error_log("Failed to prepare statement: " . $con->error);
                            } else {
                                $stmt->bind_param("ss", $email, $eid);
                                if (!$stmt->execute()) {
                                    error_log("Failed to execute statement: " . $stmt->error);
                                }
                                $stmt->close();
                            }
                        }
                    }

                    $stmt = $con->prepare("SELECT UNIX_TIMESTAMP(start_time) as start_timestamp FROM history WHERE eid=? AND email=?");
                    if ($stmt === false) {
                        error_log("Failed to prepare statement: " . $con->error);
                    } else {
                        $stmt->bind_param("ss", $eid, $email);
                        if (!$stmt->execute()) {
                            error_log("Failed to execute statement: " . $stmt->error);
                        } else {
                            $result = $stmt->get_result();
                            $row_history = $result->fetch_assoc();
                            $start_time_ts = $row_history['start_timestamp'];

                            $stmt2 = $con->prepare("SELECT * FROM quiz WHERE eid=?");
                            if ($stmt2 === false) {
                                error_log("Failed to prepare statement: " . $con->error);
                            } else {
                                $stmt2->bind_param("s", $eid);
                                if (!$stmt2->execute()) {
                                    error_log("Failed to execute statement: " . $stmt2->error);
                                } else {
                                    $result2 = $stmt2->get_result();
                                    $row_quiz = $result2->fetch_assoc();
                                    $time = $row_quiz['time'];
                                    $end_time_ts = $start_time_ts + ($time * 60);

                                    $stmt3 = $con->prepare("SELECT * FROM questions WHERE eid=? AND sn=?");
                                    if ($stmt3 === false) {
                                        error_log("Failed to prepare statement: " . $con->error);
                                    } else {
                                        $stmt3->bind_param("si", $eid, $sn);
                                        if (!$stmt3->execute()) {
                                            error_log("Failed to execute statement: " . $stmt3->error);
                                        } else {
                                            $result3 = $stmt3->get_result();
                                            if ($result3->num_rows == 0) {
                                                echo '<div class="panel" style="margin:5%;"><div class="alert alert-danger" role="alert">No questions found for this quiz.</div></div>';
                                            } else {
                                                echo '<div class="panel" style="margin:5%;">';
                                                $progress = ($sn - 1) / $total * 100;
                                                echo '<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' . $progress . '%;" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100"></div></div>';
                                                echo '<div class="row"><div class="col-md-6"><h3 style="font-weight: bold;">Question &nbsp;' . $sn . '&nbsp;</h3></div><div class="col-md-6"><div id="timer" style="font-size: 20px; font-weight: bold; color: #333; float: right;"></div></div></div>';
                                                $qid = '';
                                                while ($row_questions = $result3->fetch_assoc()) {
                                                    $qns = htmlspecialchars($row_questions['qns']);
                                                    $qid = htmlspecialchars($row_questions['qid']);
                                                    echo '<div class="card"><div class="card-body"><h5 class="card-title">' . $qns . '</h5>';
                                                }
                                                if ($qid != '') {
                                                    $stmt4 = $con->prepare("SELECT * FROM options WHERE qid=?");
                                                    if ($stmt4 === false) {
                                                        error_log("Failed to prepare statement: " . $con->error);
                                                    } else {
                                                        $stmt4->bind_param("s", $qid);
                                                        if (!$stmt4->execute()) {
                                                            error_log("Failed to execute statement: " . $stmt4->error);
                                                        } else {
                                                            $result4 = $stmt4->get_result();
                                                            echo '<form action="update.php?q=quiz&step=2&eid=' . $eid . '&n=' . $sn . '&t=' . $total . '&qid=' . $qid . '" method="POST"  class="form-horizontal"><ul class="list-group list-group-flush">';
                                                            while ($row_options = $result4->fetch_assoc()) {
                                                                $option = htmlspecialchars($row_options['option']);
                                                                $optionid = htmlspecialchars($row_options['optionid']);
                                                                echo '<li class="list-group-item"><input type="radio" name="ans" value="' . $optionid . '"> ' . $option . '</li>';
                                                            }
                                                            echo '</ul><div class="card-body">';
                                                            if ($sn < $total) {
                                                                echo '<button type="submit" class="btn btn-primary" name="next"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>&nbsp;Next</button>';
                                                            }
                                                            if ($sn == $total) {
                                                                echo '<button type="submit" class="btn btn-success" name="submit"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>&nbsp;Submit</button>';
                                                            }
                                                            echo '</div></form></div></div>';
                                                        }
                                                        $stmt4->close();
                                                    }
                                                }
                                                echo '</div>';
                                                ?>
                                                <script>
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
                                                    window.addEventListener('popstate', function () {
                                                        history.pushState(null, null, document.URL);
                                                    });

                                                    // New client-side timer logic using timestamp
                                                    var endTime = <?php echo $end_time_ts; ?> * 1000; // in milliseconds
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
                                                </script>
                                                <?php
                                            }
                                        }
                                        $stmt3->close();
                                    }
                                }
                                $stmt2->close();
                            }
                        }
                        $stmt->close();
                    }
                } ?>
                <!--quiz end-->

                <!--result display start-->
                <?php if ($q == 'result' && isset($_GET['eid'])) {
                    $eid = $_GET['eid'];
                    $stmt = $con->prepare("SELECT * FROM history WHERE eid=? AND email=?");
                    if ($stmt === false) {
                        error_log("Failed to prepare statement: " . $con->error);
                    } else {
                        $stmt->bind_param("ss", $eid, $email);
                        if (!$stmt->execute()) {
                            error_log("Failed to execute statement: " . $stmt->error);
                        } else {
                            $result = $stmt->get_result();
                            echo '<div class="panel">
<center><h1 class="title" style="color:#660033">Result</h1><center><br /><table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';

                            while ($row = $result->fetch_assoc()) {
                                $s = htmlspecialchars($row['score']);
                                $w = htmlspecialchars($row['wrong']);
                                $r = htmlspecialchars($row['correct']);
                                $qa = htmlspecialchars($row['level']);
                                echo '<tr style="color:#66CCFF"><td>Total Questions</td><td>' . $qa . '</td></tr>
      <tr style="color:#99cc32"><td>right Answer&nbsp;<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></td><td>' . $r . '</td></tr>
	  <tr style="color:red"><td>Wrong Answer&nbsp;<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td><td>' . $w . '</td></tr>
	  <tr style="color:#66CCFF"><td>Score&nbsp;<span class="glyphicon glyphicon-star" aria-hidden="true"></span></td><td>' . $s . '</td></tr>';
                            }
                            $stmt->close();

                            $stmt2 = $con->prepare("SELECT * FROM rank WHERE  email=?");
                            if ($stmt2 === false) {
                                error_log("Failed to prepare statement: " . $con->error);
                            } else {
                                $stmt2->bind_param("s", $email);
                                if (!$stmt2->execute()) {
                                    error_log("Failed to execute statement: " . $stmt2->error);
                                } else {
                                    $result2 = $stmt2->get_result();
                                    while ($row = $result2->fetch_assoc()) {
                                        $s = htmlspecialchars($row['score']);
                                        echo '<tr style="color:#990000"><td>Overall Score&nbsp;<span class="glyphicon glyphicon-stats" aria-hidden="true"></span></td><td>' . $s . '</td></tr>';
                                    }
                                }
                                $stmt2->close();
                            }
                            echo '</table></div>';
                        }
                    }
                } ?>
                <!--result display end-->

                <!--history start-->
                <?php if ($q == 2) {
                    $stmt = $con->prepare("SELECT * FROM history WHERE email=? ORDER BY date DESC");
                    if ($stmt === false) {
                        error_log("Failed to prepare statement: " . $con->error);
                    } else {
                        $stmt->bind_param("s", $email);
                        if (!$stmt->execute()) {
                            error_log("Failed to execute statement: " . $stmt->error);
                        } else {
                            $result = $stmt->get_result();
                            echo '<div class="panel title">
<table class="table table-striped title1" >
<tr style="color:red"><td><b>S.N.</b></td><td><b>Quiz</b></td><td><b>Question Solved</b></td><td><b>Right</b></td><td><b>Wrong<b></td><td><b>Score</b></td></tr>';
                            $c = 0;
                            while ($row = $result->fetch_assoc()) {
                                $eid = htmlspecialchars($row['eid']);
                                $s = htmlspecialchars($row['score']);
                                $w = htmlspecialchars($row['wrong']);
                                $r = htmlspecialchars($row['correct']);
                                $qa = htmlspecialchars($row['level']);

                                $stmt2 = $con->prepare("SELECT title FROM quiz WHERE  eid=?");
                                if ($stmt2 === false) {
                                    error_log("Failed to prepare statement: " . $con->error);
                                } else {
                                    $stmt2->bind_param("s", $eid);
                                    if (!$stmt2->execute()) {
                                        error_log("Failed to execute statement: " . $stmt2->error);
                                    } else {
                                        $result2 = $stmt2->get_result();
                                        while ($row2 = $result2->fetch_assoc()) {
                                            $title = htmlspecialchars($row2['title']);
                                        }
                                        $c++;
                                        echo '<tr><td>' . $c . '</td><td>' . $title . '</td><td>' . $qa . '</td><td>' . $r . '</td><td>' . $w . '</td><td>' . $s . '</td></tr>';
                                    }
                                    $stmt2->close();
                                }
                            }
                            echo '</table></div>';
                        }
                        $stmt->close();
                    }
                } ?>
                <!--history end-->

                <!--ranking start-->
                <?php if ($q == 3) {
                    $stmt = $con->prepare("SELECT * FROM rank  ORDER BY score DESC");
                    if ($stmt === false) {
                        error_log("Failed to prepare statement: " . $con->error);
                    } else {
                        if (!$stmt->execute()) {
                            error_log("Failed to execute statement: " . $stmt->error);
                        } else {
                            $result = $stmt->get_result();
                            echo '<div class="panel title"><div class="table-responsive">
<table class="table table-striped title1" >
<tr style="color:red"><td><b>Rank</b></td><td><b>Name</b></td><td><b>Gender</b></td><td><b>College</b></td><td><b>Score</b></td></tr>';
                            $c = 0;
                            while ($row = $result->fetch_assoc()) {
                                $e = htmlspecialchars($row['email']);
                                $s = htmlspecialchars($row['score']);

                                $stmt2 = $con->prepare("SELECT * FROM user WHERE email=?");
                                if ($stmt2 === false) {
                                    error_log("Failed to prepare statement: " . $con->error);
                                } else {
                                    $stmt2->bind_param("s", $e);
                                    if (!$stmt2->execute()) {
                                        error_log("Failed to execute statement: " . $stmt2->error);
                                    } else {
                                        $result2 = $stmt2->get_result();
                                        while ($row2 = $result2->fetch_assoc()) {
                                            $name = htmlspecialchars($row2['name']);
                                            $gender = htmlspecialchars($row2['gender']);
                                            $college = htmlspecialchars($row2['college']);
                                        }
                                        $c++;
                                        echo '<tr><td style="color:#99cc32"><b>' . $c . '</b></td><td>' . $name . '</td><td>' . $gender . '</td><td>' . $college . '</td><td>' . $s . '</td><td>';
                                    }
                                    $stmt2->close();
                                }
                            }
                            echo '</table></div></div>';
                        }
                        $stmt->close();
                    }
                } ?>
                <!--ranking end-->

            </div>
        </div>
    </div>
</div>
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
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
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
                        <h4 style="color:#202020; font-family:'typo';font-size:16px" class="title1">+91
                            9616625629</h4>
                        <h4 style="font-family:'typo';">abhishek007kum@gmail.com</h4>
                        <h4 style="font-family:'typo';">M.D.D.C Gorakhpur</h4>
                    </div>
                </div>
                <hr> <!-- Divider Line -->

                <div class="row">
                    <div class="col-md-4">
                        <img src="./image/abhishek-pal.jpeg" width="140" height="140" alt=""
                             class="img-rounded">
                    </div>
                    <div class="col-md-5">
                        <h4>Abhishek Pal</h4>
                        <h4 style="color:#202020; font-family:'typo';font-size:16px" class="title1">+91
                            7370004059</h4>
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
                            <h4 style="color:#202020; font-family:'typo';font-size:16px" class="title1">+91
                                9264943633</h4>
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
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title"><span style="color:orange;font-family:'typo' ">LOGIN</span></h4>
            </div>
            <div class="modal-body title1">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <form role="form" method="post" action="admin.php?q=index.php">
                            <div class="form-group">
                                <input type="text" name="uname" maxlength="20" placeholder="Admin user id"
                                       class="form-control"/>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" maxlength="15" placeholder="Password"
                                       class="form-control"/>
                            </div>
                            <div class="form-group" align="center">
                                <input type="submit" name="login" value="Login" class="btn btn-primary"/>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--footer end-->
</body>
</html>
