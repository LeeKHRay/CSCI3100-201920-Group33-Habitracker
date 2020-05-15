<?php
    $title = "Remove Goal";
    require 'admin_header.php';
    
    if(!isset($_SESSION['admin_username'])){
        "<div class='mx-2'><p>You don't have permission to access this page! Please log in as administator.</p><div>";
        exit();
    }
    
    require 'db_key.php';
    $conn = connect_db();

    $sql = "select goal_id, username, goal_name, goal_description, goal_subtask from goals";
    $result = $conn->query($sql);
?>

<div><h1 align=center>Goal List</h1></div>

<?php
    // list all goals in a table
    if($result->num_rows > 0) {
        $table = 
            '<table class="table table-bordered table-hover table-sm text-nowrap">
                <thead class="thead-light text-center">
                    <tr>
                        <th class="sticky-top">Goal ID</th>
                        <th class="sticky-top">Goal Owner</th>
                        <th class="sticky-top">Goal Name</th>
                        <th class="sticky-top">Description</th>
                        <th class="sticky-top">Subtask</th>
                        <th class="sticky-top">Delete Goal</th>
                    </tr>
                </thead>
            <tbody>';
        
        while($goal = $result->fetch_assoc()) { 
            $table .= '<tr class="table-success text-center">';
            $table .= '<td class="align-middle">'.$goal['goal_id'].'</td>';
            $table .= '<td class="align-middle">'.$goal['username'].'</td>';
            $table .= '<td class="align-middle">'.$goal['goal_name'].'</td>';
            $table .= '<td class="text-left align-middle text-wrap">'.$goal['goal_description'].'</td>';
            $table .= '<td class="text-left align-middle text-wrap">'.$goal['goal_subtask'].'</td>';
            $table .= '<td class="align-middle"><button class="deleteGoal btn btn-link" data-id="'.$goal['goal_id'].'" data-username="'.$goal['username'].'">Delete</button></td>';
            $table .= '</tr>';
        }
        $table .= '</tbody></table>';
        echo $table;
    }
    else
        echo "No goals";
?>

<?php
    require "footer.php";
?>
