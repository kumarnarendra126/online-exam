<?php
include_once 'dbConnection.php';
session_start();
$email = $_SESSION['email'] ?? null;

function execute_query($con, $sql, $params = null)
{
    $stmt = $con->prepare($sql);
    if ($stmt === false) {
        error_log("Failed to prepare statement: " . $con->error);
        return false;
    }

    if ($params) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        error_log("Failed to execute statement: " . $stmt->error);
        $stmt->close();
        return false;
    }

    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

//delete feedback
if (isset($_SESSION['key'], $_GET['fdid']) && $_SESSION['key'] == 'sunny7785068889') {
    $id = $_GET['fdid'];
    execute_query($con, "DELETE FROM feedback WHERE id=?", [$id]);
    header("location:dash.php?q=3");
}

//delete user
if (isset($_SESSION['key'], $_GET['demail']) && $_SESSION['key'] == 'sunny7785068889') {
    $demail = $_GET['demail'];
    execute_query($con, "DELETE FROM rank WHERE email=?", [$demail]);
    execute_query($con, "DELETE FROM history WHERE email=?", [$demail]);
    execute_query($con, "DELETE FROM user WHERE email=?", [$demail]);
    header("location:dash.php?q=1");
}

//remove quiz
if (isset($_SESSION['key'], $_GET['q']) && $_GET['q'] == 'rmquiz' && $_SESSION['key'] == 'sunny7785068889') {
    $eid = $_GET['eid'];
    $result = execute_query($con, "SELECT * FROM questions WHERE eid=?", [$eid]);
    while ($row = $result->fetch_assoc()) {
        $qid = $row['qid'];
        execute_query($con, "DELETE FROM options WHERE qid=?", [$qid]);
        execute_query($con, "DELETE FROM answer WHERE qid=?", [$qid]);
    }
    execute_query($con, "DELETE FROM questions WHERE eid=?", [$eid]);
    execute_query($con, "DELETE FROM quiz WHERE eid=?", [$eid]);
    execute_query($con, "DELETE FROM history WHERE eid=?", [$eid]);

    header("location:dash.php?q=5");
}

//add quiz
if (isset($_SESSION['key'], $_GET['q']) && $_GET['q'] == 'addquiz' && $_SESSION['key'] == 'sunny7785068889') {
    $name = ucwords(strtolower($_POST['name']));
    $total = $_POST['total'];
    $correct = $_POST['right'];
    $wrong = $_POST['wrong'];
    $time = $_POST['time'];
    $tag = $_POST['tag'];
    $desc = $_POST['desc'];
    $id = uniqid();
    execute_query($con, "INSERT INTO quiz VALUES (?,?,?,?,?,?,?,?, NOW())", [$id, $name, $correct, $wrong, $total, $time, $desc, $tag]);
    header("location:dash.php?q=4&step=2&eid=$id&n=$total");
}

//edit quiz
if (isset($_SESSION['key'], $_GET['q']) && $_GET['q'] == 'editquiz' && $_SESSION['key'] == 'sunny7785068889') {
    $eid = $_GET['eid'];
    $name = $_POST['name'];
    $total = (int)$_POST['total'];
    $correct = (int)$_POST['right'];
    $wrong = (int)$_POST['wrong'];
    $time = (int)$_POST['time'];
    $tag = $_POST['tag'];
    $desc = $_POST['desc'];

    execute_query($con, "UPDATE quiz SET title=?, total=?, correct=?, wrong=?, time=?, tag=?, `desc`=? WHERE eid=?", [$name, $total, $correct, $wrong, $time, $tag, $desc, $eid]);
    header("location:dash.php?q=0&quiz_edited=true");
}

//edit questions
if (isset($_SESSION['key'], $_GET['q']) && $_GET['q'] == 'editqns' && $_SESSION['key'] == 'sunny7785068889') {
    $num_questions = $_POST['num_questions'];

    for ($i = 1; $i <= $num_questions; $i++) {
        $qid = $_POST['qid' . $i];
        $qns = $_POST['qns' . $i];

        execute_query($con, "UPDATE questions SET qns=? WHERE qid=?", [$qns, $qid]);

        for ($j = 1; $j <= 4; $j++) {
            $option = $_POST['option' . $i . '_' . $j];
            $optionid = $_POST['optionid' . $i . '_' . $j];
            execute_query($con, "UPDATE options SET `option`=? WHERE optionid=?", [$option, $optionid]);
        }

        $ansid = $_POST['ans' . $i];
        execute_query($con, "UPDATE answer SET ansid=? WHERE qid=?", [$ansid, $qid]);
    }

    header("location:dash.php?q=0&questions_edited=true");
}


//add question
if (isset($_SESSION['key'], $_GET['q']) && $_GET['q'] == 'addqns' && $_SESSION['key'] == 'sunny7785068889') {
    $n = $_GET['n'];
    $eid = $_GET['eid'];
    $ch = $_GET['ch'];

    for ($i = 1; $i <= $n; $i++) {
        $qid = uniqid();
        $qns = $_POST['qns' . $i];
        execute_query($con, "INSERT INTO questions VALUES (?,?,?,?,?)", [$eid, $qid, $qns, $ch, $i]);
        $oaid = uniqid();
        $obid = uniqid();
        $ocid = uniqid();
        $odid = uniqid();
        $a = $_POST[$i . '1'];
        $b = $_POST[$i . '2'];
        $c = $_POST[$i . '3'];
        $d = $_POST[$i . '4'];
        execute_query($con, "INSERT INTO options VALUES (?,?,?)", [$qid, $a, $oaid]);
        execute_query($con, "INSERT INTO options VALUES (?,?,?)", [$qid, $b, $obid]);
        execute_query($con, "INSERT INTO options VALUES (?,?,?)", [$qid, $c, $ocid]);
        execute_query($con, "INSERT INTO options VALUES (?,?,?)", [$qid, $d, $odid]);
        $e = $_POST['ans' . $i];
        switch ($e) {
            case 'a':
                $ansid = $oaid;
                break;
            case 'b':
                $ansid = $obid;
                break;
            case 'c':
                $ansid = $ocid;
                break;
            case 'd':
                $ansid = $odid;
                break;
            default:
                $ansid = $oaid;
        }


        execute_query($con, "INSERT INTO answer VALUES (?,?)", [$qid, $ansid]);

    }
    header("location:dash.php?q=0");
}

//quiz start
if (isset($_GET['q'], $_GET['step']) && $_GET['q'] == 'quiz' && $_GET['step'] == 2) {
    $eid = $_GET['eid'];
    $sn = $_GET['n'];
    $total = $_GET['t'];
    $ans = $_POST['ans'];
    $qid = $_GET['qid'];

    $result = execute_query($con, "SELECT * FROM history WHERE eid=? AND email=?", [$eid, $email]);
    $row = $result->fetch_assoc();
    $start_time = strtotime($row['start_time']);

    $result = execute_query($con, "SELECT * FROM quiz WHERE eid=? ", [$eid]);
    $row = $result->fetch_assoc();
    $time = $row['time'];
    $elapsed_time = time() - $start_time;

    if ($elapsed_time >= ($time * 60)) {
        header("location:summary.php?eid=$eid");
    }

    $result = execute_query($con, "SELECT * FROM answer WHERE qid=? ", [$qid]);
    $ansid = null;
    if($result) {
        while ($row = $result->fetch_assoc()) {
            $ansid = $row['ansid'];
        }
    }

    $score = 0;
    if ($ans == $ansid) {
        $result = execute_query($con, "SELECT * FROM quiz WHERE eid=? ", [$eid]);
        $sahi = 0;
        if($result) {
            while ($row = $result->fetch_assoc()) {
                $sahi = $row['correct'];
            }
        }

        $result = execute_query($con, "SELECT * FROM history WHERE eid=? AND email=? ", [$eid, $email]);
        $s = 0;
        $r = 0;
        if($result) {
            while ($row = $result->fetch_assoc()) {
                $s = $row['score'];
                $r = $row['correct'];
            }
        }
        $r++;
        $s = $s + $sahi;
        execute_query($con, "UPDATE `history` SET `score`=?,`level`=?,`correct`=?, date= NOW()  WHERE  email = ? AND eid = ?", [$s, $sn, $r, $email, $eid]);
        $score = $sahi;
    } else {
        $result = execute_query($con, "SELECT * FROM quiz WHERE eid=? ", [$eid]);
        $wrong = 0;
        if($result) {
            while ($row = $result->fetch_assoc()) {
                $wrong = $row['wrong'];
            }
        }
        $result = execute_query($con, "SELECT * FROM history WHERE eid=? AND email=? ", [$eid, $email]);
        $s = 0;
        $w = 0;
        if($result) {
            while ($row = $result->fetch_assoc()) {
                $s = $row['score'];
                $w = $row['wrong'];
            }
        }
        $w++;
        $s = $s - $wrong;
        execute_query($con, "UPDATE `history` SET `score`=?,`level`=?,`wrong`=?, date=NOW() WHERE  email = ? AND eid = ?", [$s, $sn, $w, $email, $eid]);
        $score = -$wrong;
    }
    execute_query($con, "INSERT INTO user_answer VALUES(NULL, ?, ?, ?, ?, ?)", [$email, $eid, $qid, $ans, $score]);

    if (isset($_POST['next'])) {
        if ($sn != $total) {
            $sn++;
            header("location:account.php?q=quiz&step=2&eid=$eid&n=$sn&t=$total");
        }
    } else if (isset($_POST['submit'])) {
        $result = execute_query($con, "SELECT score FROM history WHERE eid=? AND email=?", [$eid, $email]);
        $s = 0;
        if($result) {
            while ($row = $result->fetch_assoc()) {
                $s = $row['score'];
            }
        }
        $result = execute_query($con, "SELECT * FROM rank WHERE email=?", [$email]);
        $rowcount = 0;
        if($result) {
            $rowcount = $result->num_rows;
        }
        if ($rowcount == 0) {
            execute_query($con, "INSERT INTO rank VALUES(?,?,NOW())", [$email, $s]);
        } else {
            $sun = 0;
            if($result) {
                while ($row = $result->fetch_assoc()) {
                    $sun = $row['score'];
                }
            }
            $sun = $s + $sun;
            execute_query($con, "UPDATE `rank` SET `score`=? ,time=NOW() WHERE email= ?", [$sun, $email]);
        }
        header("location:summary.php?eid=$eid");
    }
}

//restart quiz
if (isset($_GET['q'], $_GET['step']) && $_GET['q'] == 'quizre' && $_GET['step'] == 25) {
    $eid = $_GET['eid'];
    $n = $_GET['n'];
    $t = $_GET['t'];
    $result = execute_query($con, "SELECT score FROM history WHERE eid=? AND email=?", [$eid, $email]);
    $s = 0;
    if($result) {
        while ($row = $result->fetch_assoc()) {
            $s = $row['score'];
        }
    }
    execute_query($con, "DELETE FROM `history` WHERE eid=? AND email=? ", [$eid, $email]);
    $result = execute_query($con, "SELECT * FROM rank WHERE email=?", [$email]);
    $sun = 0;
    if($result) {
        while ($row = $result->fetch_assoc()) {
            $sun = $row['score'];
        }
    }
    $sun = $sun - $s;
    execute_query($con, "UPDATE `rank` SET `score`=? ,time=NOW() WHERE email= ?", [$sun, $email]);
    header("location:account.php?q=quiz&step=2&eid=$eid&n=1&t=$t");
}

?>