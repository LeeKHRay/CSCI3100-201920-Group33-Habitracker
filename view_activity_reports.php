<?php
    $title = "View Activity Report";
    require 'admin_header.php';
    
    if( !isset( $_SESSION['admin_username']) ){
        "<div class='mx-2'><p>You don't have permission to access this page! Please log in as administator.</p><div>";
        exit();
    }
    
    require 'db_key.php';
    $conn = connect_db();

    $sql = "select * from reports where resolved = false and report_type = 'activity' order by report_time";
    $unresolved_result = $conn->query($sql);

    $sql = "select * from reports where resolved = true and report_type = 'activity' order by report_time";
    $resolved_result = $conn->query($sql);
?>

<div><h1 align=center>Activity Report List</h1></div>

<?php
    if($unresolved_result->num_rows + $resolved_result->num_rows > 0) {
        $table = 
            '<table class="table table-bordered table-hover table-sm text-nowrap">
                <thead class="thead-light text-center">
                    <tr>
                        <th class="sticky-top">Report Time</th>
                        <th class="sticky-top">Reporter</th>
                        <th class="sticky-top">Activity Host</th>
                        <th class="sticky-top">Activity Name</th>
                        <th class="sticky-top">Reason</th>
                        <th class="sticky-top">Delete Activity</th>
                        <th class="sticky-top">Dismiss Report</th>
                        <th class="sticky-top">Resolve Report</th>
                    </tr>
                </thead>
            <tbody>';
        if ($unresolved_result->num_rows > 0) { // listing unresolved activity reports
            while($report = $unresolved_result->fetch_assoc()) { 
                $table .= '<tr class="table-success text-center">';
                $table .= '<td class="align-middle">'.$report['report_time'].'</td>';
                $table .= '<td class="align-middle">'.$report['reporter'].'</td>';
                $table .= '<td class="align-middle">'.$report['owner'].'</td>';     
                
                $sql = "select activity_name from activity_table where activity_id = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: ../admin_index.php?error=sqlerror");
                    exit();
                }
                else {
                    mysqli_stmt_bind_param($stmt, "i", $report['activity_id']);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $activity = mysqli_fetch_assoc($result);

                    if ($activity == null){
                        $table .= '<td class="align-middle">'.$report['activity_name'].'</td>';
                    }
                    else {
                        if ($activity['activity_name'] != $report['activity_name']) {
                            // update activity name in report
                            $sql = "update reports set activity_name = ? where activity_id = ?";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)){
                                header("Location: ../admin_index.php?error=sqlerror");
                                exit();
                            }
                            else {
                                mysqli_stmt_bind_param($stmt, "si", $activity['activity_name'], $report['activity_id']);
                                mysqli_stmt_execute($stmt);
                            }
                        }

                        $table .= '<td class="align-middle">'.$activity['activity_name'].'</td>';
                    }
                }
                
                $table .= '<td class="text-left align-middle text-wrap">'.$report['reason'].'</td>';
                
                if ($report['deleted'] == false && $report['dismissed'] == false) {
                    $table .= '<td class="align-middle"><button class="deleteActivity btn btn-link" data-id="'.$report['activity_id'].'" data-username="'.$report['owner'].'">Delete</button></td>';
                    $table .= '<td class="align-middle"><button class="dismiss btn btn-link" data-id="'.$report['report_id'].'">Dismiss</button></td>';
                    $table .= '<td class="align-middle"><button class="btn btn-link disabled">Resolve</button></td>';
                }
                else {
                    if ($report['deleted'] == true) {
                        $table .= '<td class="align-middle"><button class="btn btn-link disabled">Deleted</button></td>';
                        $table .= '<td class="align-middle"><button class="btn btn-link disabled">Dismiss</button></td>';
                    }
                    else {
                        $table .= '<td class="align-middle"><button class="btn btn-link disabled">Delete</button></td>';
                        $table .= '<td class="align-middle"><button class="btn btn-link disabled">Dismissed</button></td>';
                    }
                    $table .= '<td class="align-middle"><button class="resolve btn btn-link" data-id="'.$report['report_id'].'">Resolve</button></td>';
                }
                
                $table .= '</tr>';
            }
        } 
        if ($resolved_result->num_rows > 0) { // listing resolved activity reports
            while($report = $resolved_result->fetch_assoc()) { 
                $table .= '<tr class="table-primary text-center">';
                $table .= '<td class="align-middle">'.$report['report_time'].'</td>';
                $table .= '<td class="align-middle">'.$report['reporter'].'</td>';
                $table .= '<td class="align-middle">'.$report['owner'].'</td>';
                
                $sql = "select activity_name from activity_table where activity_id = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: ../admin_index.php?error=sqlerror");
                    exit();
                }
                else {
                    mysqli_stmt_bind_param($stmt, "i", $report['activity_id']);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $activity = mysqli_fetch_assoc($result);

                    if ($activity == null){
                        $table .= '<td class="align-middle">'.$report['activity_name'].'</td>';
                    }
                    else {
                        if ($activity['activity_name'] != $report['activity_name']) {
                            // update activity name in report
                            $sql = "update reports set activity_name = ? where activity_id = ?";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)){
                                header("Location: ../admin_index.php?error=sqlerror");
                                exit();
                            }
                            else {
                                mysqli_stmt_bind_param($stmt, "si", $activity['activity_name'], $report['activity_id']);
                                mysqli_stmt_execute($stmt);
                            }
                        }

                        $table .= '<td class="align-middle">'.$activity['activity_name'].'</td>';
                    }
                }
                
                $table .= '<td class="text-left align-middle text-wrap">'.$report['reason'].'</td>';
                
                if ($report['deleted'] == true) {
                    $table .= '<td class="align-middle"><button class="btn btn-link align-middle disabled">Deleted</button></td>';
                    $table .= '<td class="align-middle"><button class="btn btn-link align-middle disabled">Dismiss</button></td>';
                }
                else {
                    $table .= '<td class="align-middle"><button class="btn btn-link align-middle disabled">Delete</button></td>';
                    $table .= '<td class="align-middle"><button class="btn btn-link align-middle disabled">Dismissed</button></td>';
                }
                $table .= '<td class="align-middle"><button class="btn btn-link align-middle disabled">Resolved</button></td>';
                $table .= '</tr>';
            }

        }
        $table .= '</tbody></table>';
        echo $table;
    }
    else
        echo "No reports";
?>

<?php
    require "footer.php";
?>
