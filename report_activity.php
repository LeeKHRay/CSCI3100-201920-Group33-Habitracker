<?php
    require 'header.php';
    if( !isset($_SESSION['username'])){
        echo "You are not authorized to view this page. Go back <a href= '/'>home</a>";
        exit();
    }
    else if(!isset($_GET['id'])){
        header("Location: index.php");
        exit();   
    }
    
    require 'db_key.php';
    $conn = connect_db();
    
    function getProperWeekDay($capWeekDay){
        if ($capWeekDay=="MON")return "Monday";
        elseif ($capWeekDay=="TUE")return "Tuesday";
        elseif ($capWeekDay=="WED")return "Wednesday";
        elseif ($capWeekDay=="THU")return "Thursday";
        elseif ($capWeekDay=="FRI")return "Friday";
        elseif ($capWeekDay=="SAT")return "Saturday";
        elseif ($capWeekDay=="SUN")return "Sunday";
    }
    
    $activity = null;
    $sql = "select * from activity_table where activity_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: admin_index.php?error=sqlerror");
        exit();
    }
    else {
        mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
    }
?>
<link rel="stylesheet" href="report_form.css">

<div class="loginbox">
<h1>Report Inappropriate</h1>
    
    <form action="report_backend.php" method="POST">
        <!-- this field is hidden -->
        <div class="form-group">
            <input type="hidden" name="activity_id" value="<?php echo $row['activity_id'] ?>">
            <input type="hidden" name="activity_name" value="<?php echo $row['activity_name'] ?>" readonly>
            <input type="hidden" name="activity_creator" value="<?php echo $row['username'] ?>" readonly>
        </div>
        <div class="form-group">
<p>Activity ID: <?php echo $row['activity_id'];?></p><br>
<p>Name: <?php echo $row['activity_name'];?></p><br>
<?php
    if(!empty($row['activity_one_off_datetime'])){
        $date= $row['activity_one_off_datetime'];
        echo "<p>Date and time: ".$date."</p><br>";
        //echo "<div><label for='date'>Activity Date:</label> <input type='text' id='date' value='.$date.'> </div>";
    }
    if($row['activity_repetition']==1){
        
        echo "<p>The activity will be held once a week on <br>".
        getProperWeekDay($row['activity_recurring_date_0']).
        " at ".$row['activity_recurring_time_0'].". </p>";
    }
    if($row['activity_repetition']==2){
        
        echo "<p>The activity will be held twice a week on ".
        getProperWeekDay($row['activity_recurring_date_0']).
        " at ".$row['activity_recurring_time_0']." and ".
        getProperWeekDay($row['activity_recurring_date_1']).
        " at ".$row['activity_recurring_time_1'].". ".
        
        " </p><br>";
    }
    if($row['activity_repetition']==3){
        
        echo "<p>The activity will be held three times a week on ".
        getProperWeekDay($row['activity_recurring_date_0']).
        " at ".$row['activity_recurring_time_0']." , ".
        getProperWeekDay($row['activity_recurring_date_1']).
        " at ".$row['activity_recurring_time_1']." and ".
        getProperWeekDay($row['activity_recurring_date_2']).
        " at ".$row['activity_recurring_time_2'].". ".
        " </p><br>";
    }
    ?>
<p>Location: <?php echo $row['activity_location'];?></p><br>
<p>Remark on the date and time: <?php echo ($row['activity_time_remark']==''? "-" : $row['activity_time_remark']); ?></p><br>
<p>General remark: <?php echo ($row['activity_remark']==''? "-" : $row['activity_remark']); ?></p><br>
<p>Created by: <?php echo $row['username'];?></p><br>
            <label for="reason">Reason:</label>
            <textarea class="form-control" name="reason" required></textarea>
        </div>

        <br><button type="submit" class="btn btn-success" name="report_activity">Submit</button>
    </form>
</div>

<?php
    require "footer.php";
?>
