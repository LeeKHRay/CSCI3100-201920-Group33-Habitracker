<?php
    if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
        parse_str(file_get_contents("php://input"), $vars);
                
        require 'db_key.php';
        $conn = connect_db();
        
        // find user's email address
        $sql = "select email from login where user_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: admin_index.php?error=sqlerror");
            exit();
        }
        else {
            mysqli_stmt_bind_param($stmt, "i", $vars['user_id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $account = mysqli_fetch_assoc($result);
            
            // send email notification
            $message = '<p>Dear '.$vars['username'].',</p>';
            $message .= '<p>We deleted your account because you keep creating goals/activities that contain inappropriate information.</p>';
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
            $mail->Subject = '[Habitracker] Your account has been deleted';
            $mail->Body = $message;
            $mail->AddAddress($account['email']);

            // delete user
            $sql = "delete from login where user_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: admin_index.php?error=sqlerror");
                exit();
            }
            else {
                mysqli_stmt_bind_param($stmt, "i", $vars['user_id']);
                mysqli_stmt_execute($stmt);

                $sql = "delete from login_details where user_id = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: admin_index.php?error=sqlerror");
                    exit();
                }
                else {
                    mysqli_stmt_bind_param($stmt, "i", $vars['user_id']);
                    mysqli_stmt_execute($stmt);
                }
            }

            $mail->Send();
            exit();
        }
    }
    else{
        header("Location: ../admin_index.php");
        exit();
    }
?>
