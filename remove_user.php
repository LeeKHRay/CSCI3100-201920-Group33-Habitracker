<?php
    $title = "Remove User";
    require 'admin_header.php';
    
    if(!isset($_SESSION['admin_username'])){
        "<div class='mx-2'><p>You don't have permission to access this page! Please log in as administator.</p><div>";
        exit();
    }
    
    require 'db_key.php';
    $conn = connect_db();

    $sql = "select user_id, username, first_name, last_name, email, max(last_activity) from login natural join login_details group by user_id";
    $result = $conn->query($sql);
?>

<div><h1 align=center>User List</h1></div>

<?php
    // list all user in a table
    if($result->num_rows > 0) {
        $table = 
            '<table class="table table-bordered table-hover table-sm text-nowrap">
                <thead class="thead-light text-center">
                    <tr>
                        <th class="sticky-top">User ID</th>
                        <th class="sticky-top">Username</th>
                        <th class="sticky-top">First Name</th>
                        <th class="sticky-top">Last Name</th>
                        <th class="sticky-top">Email</th>
                        <th class="sticky-top">Last Activty</th>
                        <th class="sticky-top">Delete User</th>
                    </tr>
                </thead>
            <tbody>';
        
        while($user = $result->fetch_assoc()) { 
            $table .= '<tr class="table-success text-center">';
            $table .= '<td class="align-middle">'.$user['user_id'].'</td>';
            $table .= '<td class="align-middle">'.$user['username'].'</td>';
            $table .= '<td class="align-middle">'.$user['first_name'].'</td>';
            $table .= '<td class="align-middle">'.$user['last_name'].'</td>';
            $table .= '<td class="align-middle">'.$user['email'].'</td>';
            $table .= '<td class="align-middle">'.$user['max(last_activity)'].'</td>';
            $table .= '<td class="align-middle"><button class="deleteUser btn btn-link" data-id="'.$user['user_id'].'" data-username="'.$user['username'].'">Delete</button></td>';
            $table .= '</tr>';
        }
        $table .= '</tbody></table>';
        echo $table;
    }
    else
        echo "No users";
?>

<?php
    require "footer.php";
?>
