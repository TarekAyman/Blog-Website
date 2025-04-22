<?php
require 'vendor/autoload.php';
include "db.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";


if (isset($_GET['token'])) {
    $token = $_GET['token'];

    
    $sql = "SELECT * FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = trim($_POST["password"]);
            $confirm_password = trim($_POST["confirm_password"]);

            if ($new_password === $confirm_password) {
                
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $hashed_password, $token);
                $stmt->execute();

                $message = "Your password has been successfully reset.";
            } else {
                $message = "Passwords do not match.";
            }
        }
    } else {
        $message = "Invalid or expired token.";
    }
} else {
    
    $message = "No token provided.";
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_GET['token'])) {
    $email = trim($_POST["email"]);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $token = bin2hex(random_bytes(50));

        $sql = "UPDATE users SET reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        
        $reset_link = "http://localhost/Matter_Project/reset_password.php?token=" . $token;

        
            $mail = new PHPMailer(true);
        try {
            
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'tarekseoudii@gmail.com';  
            $mail->Password = 'riqo dstp rlwa vxps';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
            $mail->Port = 587;  

            
            $mail->setFrom('your-email@gmail.com', 'Mailer');  
            $mail->addAddress($email, 'User'); 
            $mail->Subject = 'Password Reset Request'; 
            $mail->Body = "Click the link below to reset your password:\n" . $reset_link;  

            
            if ($mail->send()) {
                $message = "A password reset link has been sent to your email.";
            } else {
                $message = "Message could not be sent.";
            }
        } catch (Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "No account found with that email.";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <?php if (isset($_GET['token'])): ?>
            <h2>Reset Your Password</h2>
            <?php if ($message): ?>
                <p><?= $message ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <label>New Password:</label>
                <input type="password" name="password" required><br>

                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required><br>

                <button type="submit">Reset Password</button>
            </form>
        <?php else: ?>
            <h2>Reset Password</h2>
            <?php if ($message): ?>
                <p><?= $message ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <label>Email:</label>
                <input type="email" name="email" required><br>

                <button type="submit">Send Reset Link</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
