<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['pwd'];

    if (empty($username) || empty($password)){ // check if all fields are filled
        echo 'Fill in all fields!';
    }
    else if ($username != "Admin" || $password != "csci3100"){ // check if username and password are correct
        echo 'Wrong username or password!';
    }
    else{
        session_start();
        $_SESSION['admin_username'] = $username;
        echo 'success';
    }
    exit();
}
else {
    header("Location: ../admin_index.php");
    exit();
}

?>
