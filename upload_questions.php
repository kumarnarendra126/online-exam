<?php
session_start();
include_once 'dbConnection.php';

// Security check: ensure user is admin
if(!isset($_SESSION['key']) || $_SESSION['key'] != 'sunny7785068889') {
    header("location:dash.php");
    die();
}

if (isset($_POST['submit'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        
        $eid = $_POST['eid'];
        $total_questions_in_quiz = (int)$_POST['n'];

        // File check
        $file_tmp_path = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $file_ext_arr = explode('.', $file_name);
        $file_extension = strtolower(end($file_ext_arr));

        $allowed_extensions = ['csv'];
        if (!in_array($file_extension, $allowed_extensions)) {
            header("location:dash.php?q=4&step=2&eid=" . $eid . "&n=" . $total_questions_in_quiz . "&error=Invalid file type. Please upload a CSV file.");
            die();
        }

        // Get current number of questions in the quiz to set sn correctly
        $q = mysqli_query($con, "SELECT COUNT(*) as count FROM questions WHERE eid='$eid'") or die('Error querying question count');
        $row = mysqli_fetch_array($q);
        $question_num_counter = (int)$row['count'] + 1;

        if (($handle = fopen($file_tmp_path, "r")) !== FALSE) {
            // Skip header row if it exists
            // fgetcsv($handle); 

            $questions_added = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) == 6) {
                    // Stop if we have added enough questions to meet the quiz total
                    if ($question_num_counter > $total_questions_in_quiz) {
                        break;
                    }

                    $qns = mysqli_real_escape_string($con, $data[0]);
                    $opt1 = mysqli_real_escape_string($con, $data[1]);
                    $opt2 = mysqli_real_escape_string($con, $data[2]);
                    $opt3 = mysqli_real_escape_string($con, $data[3]);
                    $opt4 = mysqli_real_escape_string($con, $data[4]);
                    $correct_letter = strtolower(trim($data[5]));

                    // Generate unique IDs
                    $qid = uniqid();
                    $oaid1 = uniqid();
                    $oaid2 = uniqid();
                    $oaid3 = uniqid();
                    $oaid4 = uniqid();

                    // Insert question
                    $q3 = mysqli_query($con, "INSERT INTO questions VALUES ('$eid', '$qid', '$qns', '4', '$question_num_counter')");
                    
                    // Insert options
                    $qa = mysqli_query($con, "INSERT INTO options VALUES ('$qid', '$opt1', '$oaid1')");
                    $qb = mysqli_query($con, "INSERT INTO options VALUES ('$qid', '$opt2', '$oaid2')");
                    $qc = mysqli_query($con, "INSERT INTO options VALUES ('$qid', '$opt3', '$oaid3')");
                    $qd = mysqli_query($con, "INSERT INTO options VALUES ('$qid', '$opt4', '$oaid4')");

                    // Determine correct answer ID
                    $ansid = '';
                    switch ($correct_letter) {
                        case 'a': $ansid = $oaid1; break;
                        case 'b': $ansid = $oaid2; break;
                        case 'c': $ansid = $oaid3; break;
                        case 'd': $ansid = $oaid4; break;
                    }

                    if ($ansid != '') {
                        $qans = mysqli_query($con, "INSERT INTO answer VALUES ('$qid', '$ansid')");
                    }
                    
                    $question_num_counter++;
                    $questions_added++;
                }
            }
            fclose($handle);
            header("location:dash.php?q=4&step=2&eid=" . $eid . "&n=" . $total_questions_in_quiz . "&success=" . $questions_added . " questions added.");
            die();
        } else {
            header("location:dash.php?q=4&step=2&eid=" . $eid . "&n=" . $total_questions_in_quiz . "&error=Could not open the file.");
            die();
        }
    } else {
        header("location:dash.php?q=4&step=2&eid=" . $_POST['eid'] . "&n=" . $_POST['n'] . "&error=File upload failed.");
        die();
    }
} else {
    header("location:dash.php");
    die();
}
?>
