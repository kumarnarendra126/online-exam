<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
 <link rel="stylesheet" href="css/main.css">
 <link  rel="stylesheet" href="css/font.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
 
 <script src="js/jquery.js" type="text/javascript"></script>

  <script src="js/bootstrap.min.js"  type="text/javascript"></script>
 	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

<script>
$(function () {
    $(document).on( 'scroll', function(){
        console.log('scroll top : ' + $(window).scrollTop());
        if($(window).scrollTop()>=$(".logo").height())
        {
             $(".navbar").addClass("navbar-fixed-top");
        }

        if($(window).scrollTop()<$(".logo").height())
        {
             $(".navbar").removeClass("navbar-fixed-top");
        }
    });
});</script>
</head>

<body  style="background:#eee;">
<div class="header">
<div class="row">
<div class="col-lg-6">
<span class="logo">ExamPoint</span></div>
<?php
 include_once 'dbConnection.php';
session_start();
$email=$_SESSION['email'];
  if(!(isset($_SESSION['email']))){
header("location:index.php");

}
else
{
$name = $_SESSION['name'];;

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

</div></div>
<!-- admin start-->

<!--navigation menu-->
<nav class="navbar navbar-default title1">
  <div class="container-fluid" style="padding-left: 0; padding-right: 0;">
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav" style="margin: 0;">
        <li <?php if(@$_GET['q']==0) echo 'class="active"'; ?> style="padding-left: 0;">
          <a href="dash.php?q=0" style="font-weight: bold;">
            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;Home<span class="sr-only">(current)</span>
          </a>
        </li>
        <li <?php if(@$_GET['q']==1) echo 'class="active"'; ?>>
          <a href="dash.php?q=1" style="font-weight: bold;">
            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;User
          </a>
        </li>
        <li <?php if(@$_GET['q']==2) echo 'class="active"'; ?>>
          <a href="dash.php?q=2" style="font-weight: bold;">
            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp;Ranking
          </a>
        </li>
        <li <?php if(@$_GET['q']==3) echo 'class="active"'; ?>>
          <a href="dash.php?q=3" style="font-weight: bold;">
            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>&nbsp;Feedback
          </a>
        </li>
        <li class="dropdown <?php if(@$_GET['q']==4 || @$_GET['q']==5) echo 'active"'; ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="font-weight: bold;">
            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>&nbsp;Quiz<span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="dash.php?q=4" style="font-weight: bold;">Add Quiz</a></li>
            <li><a href="dash.php?q=5" style="font-weight: bold;">Remove Quiz</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<!--navigation menu closed-->




<div class="container"><!--container start-->
<div class="row">
<div class="col-md-12">
<!--home start-->

<?php if(@$_GET['q']==0) {

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
	<td><b><a href="account.php?q=quiz&step=2&eid='.$eid.'&n=1&t='.$total.'" class="pull-right btn btn-success"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Start</b></span></a></b></td></tr>';
}
else
{
echo '<tr style="color:#99cc32"><td>'.$c++.'</td><td>'.$title.'&nbsp;<span title="This quiz is already solve by you" class="glyphicon glyphicon-ok" aria-hidden="true"></span></td><td>'.$total.'</td><td>'.$correct*$total.'</td><td>'.$time.'&nbsp;min</td>
	<td><span class="pull-right" style="color: green; font-weight: bold;">Solved</span></td></tr>';
}
}
$c=0;
echo '</table></div></div>';

}

//ranking start
if(@$_GET['q']== 2) 
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
$name = $gender = $college = '';
$q12=mysqli_query($con,"SELECT * FROM user WHERE email='$e' " )or die('Error231');
while($row=mysqli_fetch_array($q12) )
{
$name=$row['name'];
$gender=$row['gender'];
$college=$row['college'];
}
$c++;
echo '<tr><td style="color:#99cc32"><b>'.$c.'</b></td><td>'.$name.'</td><td>'.$gender.'</td><td>'.$college.'</td><td>'.$s.'</td><td>';
}
echo '</table></div></div>';}

?>


<!--home closed-->
<!--users start-->
<?php if(@$_GET['q']==1) {

// parameters
$limit = 10;
$page  = isset($_GET['p']) ? max(1,(int)$_GET['p']) : 1;
$start = ($page-1) * $limit;
$search = '';
if(isset($_GET['s']) && trim($_GET['s'])!=""){
    $search = mysqli_real_escape_string($con, trim($_GET['s']));
}
// whitelist of sortable columns
$allowedSort = ['name','email','college','mob','gender','created_at','updated_at'];
$sort   = isset($_GET['sort']) && in_array($_GET['sort'],$allowedSort) ? $_GET['sort'] : 'name';
$order  = (isset($_GET['order']) && strtolower($_GET['order'])=='desc') ? 'DESC' : 'ASC';

$where = '';
if($search !== ''){
    $where = " WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR college LIKE '%$search%' OR mob LIKE '%$search%'";
}

// total rows
$countRes = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM user $where") or die('Error');
$rowCnt = mysqli_fetch_assoc($countRes);
$totalRows = $rowCnt['cnt'];
$totalPages = ceil($totalRows / $limit);

// fetch data
$result = mysqli_query($con, "SELECT * FROM user $where ORDER BY $sort $order LIMIT $start,$limit") or die('Error');

// search form
echo '<div class="panel"><form class="form-inline" method="get" action="dash.php">
        <input type="hidden" name="q" value="1" />
        <div class="form-group">
            <input type="text" name="s" class="form-control" placeholder="Search name or email" value="'.htmlspecialchars($search).'" />
        </div>
        <button type="submit" class="btn btn-orange">Search</button>
      </form><br />';

// edit form (modal, always present)
echo <<<EOT
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content themed-modal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Edit User</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post" action="update.php?q=updateuser">
                        <input type="hidden" name="email" id="editEmail" value="" />
                        <div class="form-group">
                            <label class="col-md-3 control-label">Name</label>
                            <div class="col-md-9">
                                <input type="text" name="name" id="editName" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Gender</label>
                            <div class="col-md-9">
                                <select name="gender" id="editGender" class="form-control">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">College</label>
                            <div class="col-md-9">
                                <input type="text" name="college" id="editCollege" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Mobile</label>
                            <div class="col-md-9">
                                <input type="text" name="mob" id="editMob" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-success">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>

<script>
$("#editModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var email = button.data("email");
    var name = button.data("name");
    var gender = button.data("gender");
    var college = button.data("college");
    var mob = button.data("mob");
    $("#editEmail").val(email);
    $("#editName").val(name);
    $("#editGender option[value='" + gender + "']").prop("selected", true);
    $("#editCollege").val(college);
    $("#editMob").val(mob);
});
</script>
EOT;


// table header with sortable links
echo '<div class="table-responsive"><table class="table table-striped title1">
<tr><td><b>S.N.</b></td>
<td><b><a href="?q=1&sort=name&order='.(($sort=='name'&&$order=='ASC')?'desc':'asc').($search?"&s=".urlencode($search):'').'">Name</a></b></td>
<td><b><a href="?q=1&sort=gender&order='.(($sort=='gender'&&$order=='ASC')?'desc':'asc').($search?"&s=".urlencode($search):'').'">Gender</a></b></td>
<td><b><a href="?q=1&sort=college&order='.(($sort=='college'&&$order=='ASC')?'desc':'asc').($search?"&s=".urlencode($search):'').'">College</a></b></td>
<td><b><a href="?q=1&sort=email&order='.(($sort=='email'&&$order=='ASC')?'desc':'asc').($search?"&s=".urlencode($search):'').'">Email</a></b></td>
<td><b><a href="?q=1&sort=mob&order='.(($sort=='mob'&&$order=='ASC')?'desc':'asc').($search?"&s=".urlencode($search):'').'">Mobile</a></b></td>
<td><b><a href="?q=1&sort=created_at&order='.(($sort=='created_at'&&$order=='ASC')?'desc':'asc').($search?"&s=".urlencode($search):'').'">Created</a></b></td>
<td><b><a href="?q=1&sort=updated_at&order='.(($sort=='updated_at'&&$order=='ASC')?'desc':'asc').($search?"&s=".urlencode($search):'').'">Updated</a></b></td>
<td></td></tr>';
$c = $start + 1;
while($row = mysqli_fetch_array($result)) {
    $name = $row['name'];
    $mob = $row['mob'];
    $gender = $row['gender'];
    $email = $row['email'];
    $college = $row['college'];
    $created = $row['created_at'];
    $created = date("d-m-Y H:i", strtotime($created));
    $updated = $row['updated_at'];
    $updated = date("d-m-Y H:i", strtotime($updated));

    echo '<tr><td>'.$c++.'</td><td>'.$name.'</td><td>'.$gender.'</td><td>'.$college.'</td><td>'.$email.'</td><td>'.$mob.'</td><td>'.$created.'</td><td>'.$updated.'</td>
    <td><a title="Edit User" data-toggle="modal" data-target="#editModal" data-email="'.htmlspecialchars($email).'" data-name="'.htmlspecialchars($name).'" data-gender="'.htmlspecialchars($gender).'" data-college="'.htmlspecialchars($college).'" data-mob="'.htmlspecialchars($mob).'"><b><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></b></a>&nbsp;
    <a title="Delete User" href="update.php?demail='.$email.'"><b><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></b></a></td></tr>';
}

// pagination
if($totalPages > 1){
    echo '<tr><td colspan="8" align="center">';
    for($i=1;$i<=$totalPages;$i++){
        if($i==$page){
            echo ' <b>'.$i.'</b> ';
        } else {
            $qs = '?q=1&p='.$i;
            if($search) $qs .= '&s='.urlencode($search);
            if($sort)   $qs .= '&sort='.urlencode($sort).'&order='.urlencode(strtolower($order));
            echo ' <a href="'.$qs.'">'.$i.'</a> ';
        }
    }
    echo '</td></tr>';
}

$c=0;
echo '</table></div></div>';

}?>
<!--user end-->

<!--feedback start-->
<?php if(@$_GET['q']==3) {
$result = mysqli_query($con,"SELECT * FROM `feedback` ORDER BY `feedback`.`date` DESC") or die('Error');
echo  '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
<tr><td><b>S.N.</b></td><td><b>Subject</b></td><td><b>Email</b></td><td><b>Date</b></td><td><b>Time</b></td><td><b>By</b></td><td></td><td></td></tr>';
$c=1;
while($row = mysqli_fetch_array($result)) {
	$date = $row['date'];
	$date= date("d-m-Y",strtotime($date));
	$time = $row['time'];
	$subject = $row['subject'];
	$name = $row['name'];
	$email = $row['email'];
	$id = $row['id'];
	 echo '<tr><td>'.$c++.'</td>';
	echo '<td><a title="Click to open feedback" href="dash.php?q=3&fid='.$id.'">'.$subject.'</a></td><td>'.$email.'</td><td>'.$date.'</td><td>'.$time.'</td><td>'.$name.'</td>
	<td><a title="Open Feedback" href="dash.php?q=3&fid='.$id.'"><b><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span></b></a></td>';
	echo '<td><a title="Delete Feedback" href="update.php?fdid='.$id.'"><b><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></b></a></td>

	</tr>';
}
echo '</table></div></div>';
}
?>
<!--feedback closed-->

<!--feedback reading portion start-->
<?php if(@$_GET['fid']) {
echo '<br />';
$id=@$_GET['fid'];
$result = mysqli_query($con,"SELECT * FROM feedback WHERE id='$id' ") or die('Error');
while($row = mysqli_fetch_array($result)) {
	$name = $row['name'];
	$subject = $row['subject'];
	$date = $row['date'];
	$date= date("d-m-Y",strtotime($date));
	$time = $row['time'];
	$feedback = $row['feedback'];
	
echo '<div class="panel"<a title="Back to Archive" href="update.php?q1=2"><b><span class="glyphicon glyphicon-level-up" aria-hidden="true"></span></b></a><h2 style="text-align:center; margin-top:-15px;font-family: "Ubuntu", sans-serif;"><b>'.$subject.'</b></h1>';
 echo '<div class="mCustomScrollbar" data-mcs-theme="dark" style="margin-left:10px;margin-right:10px; max-height:450px; line-height:35px;padding:5px;"><span style="line-height:35px;padding:5px;">-&nbsp;<b>DATE:</b>&nbsp;'.$date.'</span>
<span style="line-height:35px;padding:5px;">&nbsp;<b>Time:</b>&nbsp;'.$time.'</span><span style="line-height:35px;padding:5px;">&nbsp;<b>By:</b>&nbsp;'.$name.'</span><br />'.$feedback.'</div></div>';}
}?>
<!--Feedback reading portion closed-->

<!--add quiz start-->
<?php
if(@$_GET['q']==4 && !(@$_GET['step']) ) {
echo ' 
<div class="row">
<span class="title1" style="margin-left:40%;font-size:30px;"><b>Enter Quiz Details</b></span><br /><br />
 <div class="col-md-3"></div><div class="col-md-6">   <form class="form-horizontal title1" name="form" action="update.php?q=addquiz"  method="POST">
<fieldset>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="name"></label>  
  <div class="col-md-12">
  <input id="name" name="name" placeholder="Enter Quiz title" class="form-control input-md" type="text">
    
  </div>
</div>



<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="total"></label>  
  <div class="col-md-12">
  <input id="total" name="total" placeholder="Enter total number of questions" class="form-control input-md" type="number">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="right"></label>  
  <div class="col-md-12">
  <input id="right" name="right" placeholder="Enter marks on right answer" class="form-control input-md" min="0" type="number">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="wrong"></label>  
  <div class="col-md-12">
  <input id="wrong" name="wrong" placeholder="Enter minus marks on wrong answer" class="form-control input-md" min="0" type="number">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="time"></label>  
  <div class="col-md-12">
  <input id="time" name="time" placeholder="Enter time limit for test in minute" class="form-control input-md" min="1" type="number">
    
  </div>
</div>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="desc"></label>  
  <div class="col-md-12">
  <textarea rows="8" cols="8" name="desc" class="form-control" placeholder="Write description here..."></textarea>  
  </div>
</div>


<div class="form-group">
  <label class="col-md-12 control-label" for=""></label>
  <div class="col-md-12"> 
    <input  type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit" class="btn btn-primary"/>
  </div>
</div>

</fieldset>
</form></div>';



}
?>
<!--add quiz end-->

<!--add quiz step2 start-->
<?php
if(@$_GET['q']==4 && (@$_GET['step'])==2 ) {
echo ' 
<div class="row">
<span class="title1" style="margin-left:40%;font-size:30px;"><b>Enter Question Details</b></span><br /><br />
 <div class="col-md-3"></div><div class="col-md-6"><form class="form-horizontal title1" name="form" action="update.php?q=addqns&n='.@$_GET['n'].'&eid='.@$_GET['eid'].'&ch=4 "  method="POST">
<fieldset>
';
 
 for($i=1;$i<=@$_GET['n'];$i++)
 {
echo '<b>Question number&nbsp;'.$i.'&nbsp;:</><br /><!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="qns'.$i.' "></label>  
  <div class="col-md-12">
  <textarea rows="3" cols="5" name="qns'.$i.'" class="form-control" placeholder="Write question number '.$i.' here..."></textarea>  
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'1"></label>  
  <div class="col-md-12">
  <input id="'.$i.'1" name="'.$i.'1" placeholder="Enter option a" class="form-control input-md" type="text">
    
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'2"></label>  
  <div class="col-md-12">
  <input id="'.$i.'2" name="'.$i.'2" placeholder="Enter option b" class="form-control input-md" type="text">
    
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'3"></label>  
  <div class="col-md-12">
  <input id="'.$i.'3" name="'.$i.'3" placeholder="Enter option c" class="form-control input-md" type="text">
    
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'4"></label>  
  <div class="col-md-12">
  <input id="'.$i.'4" name="'.$i.'4" placeholder="Enter option d" class="form-control input-md" type="text">
    
  </div>
</div>
<br />
<b>Correct answer</b>:<br />
<select id="ans'.$i.'" name="ans'.$i.'" placeholder="Choose correct answer " class="form-control input-md" >
   <option value="a">Select answer for question '.$i.'</option>
  <option value="a">option a</option>
  <option value="b">option b</option>
  <option value="c">option c</option>
  <option value="d">option d</option> </select><br /><br />'; 
 }
    
echo '<div class="form-group">
  <label class="col-md-12 control-label" for=""></label>
  <div class="col-md-12"> 
    <input  type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit" class="btn btn-primary"/>
  </div>
</div>

</fieldset>
</form></div>';



}
?><!--add quiz step 2 end-->

<!--remove quiz-->
<?php if(@$_GET['q']==5) {

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
	echo '<tr><td>'.$c++.'</td><td>'.$title.'</td><td>'.$total.'</td><td>'.$correct*$total.'</td><td>'.$time.'&nbsp;min</td>
	<td><b><a href="update.php?q=rmquiz&eid='.$eid.'" class="pull-right btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Remove</b></span></a></b></td></tr>';
}
$c=0;
echo '</table></div></div>';

}
?>


</div><!--container closed-->
</div></div>
</body>
</html>
