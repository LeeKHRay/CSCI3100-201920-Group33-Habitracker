<?php
    function deleteGoal($conn, $goal_id) {
        // remove goal
        $sql = "delete from goals where goal_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: admin_index.php?error=sqlerror");
            exit();
        }
        else {
            // update report status
            mysqli_stmt_bind_param($stmt, "i", $goal_id);
            mysqli_stmt_execute($stmt);

            $sql = "update reports set deleted = true where goal_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: admin_index.php?error=sqlerror");
                exit();
            }
            else {
                mysqli_stmt_bind_param($stmt, "i", $goal_id);
                mysqli_stmt_execute($stmt);
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        parse_str(file_get_contents("php://input"), $vars);
                
        require 'db_key.php';
        $conn = connect_db();
        
        if (!empty($vars['goal_id'])) {
            // only delete goal
            $sql1 = "select email from login where username = ?";
            $stmt1 = mysqli_stmt_init($conn);
            $sql2 = "select goal_name from goals where goal_id = ?";
            $stmt2 = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt1, $sql1) || !mysqli_stmt_prepare($stmt2, $sql2)) {
                header("Location: admin_index.php?error=sqlerror");
                exit();
            }
            else {
                mysqli_stmt_bind_param($stmt1, "s", $vars['username']);
                mysqli_stmt_execute($stmt1);
                $result1 = mysqli_stmt_get_result($stmt1);
                $account = mysqli_fetch_assoc($result1);
                mysqli_stmt_bind_param($stmt2, "i", $vars['goal_id']);
                mysqli_stmt_execute($stmt2);
                $result2 = mysqli_stmt_get_result($stmt2);
                $goal = mysqli_fetch_assoc($result2);
            
                // send email notification
                $message = '<p>Dear '.$vars['username'].',</p>';
                $message .= '<p>We deleted your goal "'.$goal['goal_name'].'" because its content contains some inappropriate information.</p>';
                $message .= '<p>Please send an email to noreply-habitracker@gmail.com if you have any queries.</p>';            

                require_once('PHPMailer-5.2-stable/PHPMailerAutoload.php');
                
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->SMTPAuth = true; //tell phpmailer to authenticate with gmail
                $mail->SMTPSecure = 'ssl'; //to use gmail need to connect sll
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = '465';
                $mail->isHTML();
                $mail->Username = 'noreply.habitracker@gmail.com';//your own email address
                $mail->Password = 'csci3100';
                $mail->Subject = '[Habitracker] Your goal has been deleted';
                $mail->Body = $message;
                $mail->AddAddress($account['email']);

                deleteGoal($conn, $vars['goal_id']);

                $mail->Send();
                exit();
            }
        }
        else {
            // delete goal after delete user
            $sql = "select goal_id from goals where username = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: admin_index.php?error=sqlerror");
                exit();
            }
            else {
                mysqli_stmt_bind_param($stmt, "s", $vars['username']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                while($goal = mysqli_fetch_assoc($result)) {
                    deleteGoal($conn, $goal['goal_id']);
                }
                exit();
            }   
        }
    }
    else{
        header("Location: ../admin_index.php");
        exit();
    }
?>
