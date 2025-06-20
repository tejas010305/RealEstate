<?php
include "config.php"; // Include database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";

$email_err = $login_err1 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["emailId"]) || empty(trim($_POST["emailId"]))) {
        $email_err = "Please enter your email.";
    } else {
        $emailId = mysqli_real_escape_string($con, trim($_POST["emailId"]));
        $mobile = mysqli_real_escape_string($con, trim($_POST["number"]));

        $sql = "SELECT * FROM user WHERE uemail='$emailId'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $valid_emailId = $row['uemail'];
            $name = $row['uname'];

            $otp = rand(100000, 999999);
            $otp_expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

            $update_sql = "UPDATE user SET otp='$otp', otp_expiry='$otp_expiry' WHERE uemail='$emailId'";
            mysqli_query($con, $update_sql);

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'getintouchvastuhomes@gmail.com';
                $mail->Password = 'iewl tfab gsqt sqpo';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('your-email@gmail.com', 'Vastu Homes');
                $mail->addAddress($valid_emailId, $name);
                $mail->addReplyTo('your-email@gmail.com', 'Vastu Homes');

                $mail->isHTML(true);
                $mail->Subject = 'OTP for Password Reset';
                $mail->Body = "Your OTP for password reset is: <b>$otp</b>. This OTP is valid for 10 minutes.";

                $mail->send();
                echo 'OTP has been sent to your email address.';
                header("location: reset.php");
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
            }
        } else {
            $login_err1 = "Invalid Email ID.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password - Vastu Homes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/Forget Background.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 15px;
            color: white;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .form-container h3 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            color: #fff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-group input[type="submit"] {
            background-color: #218838;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #1c7430;
        }

        .error {
            color: #ff4444;
            font-size: 14px;
            display: block;
            margin-top: 5px;
            text-align: center;
        }

        @media (max-width: 480px) {
            .form-container {
                margin: 20px;
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h3>Forget Password</h3>
            
            <div class="form-group">
                <label for="emailId">Email Address *</label>
                <input type="email" name="emailId" id="emailId" placeholder="Enter your email" required>
                <span class="error"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group">
                <label for="number">Mobile Number *</label>
                <input type="tel" name="number" id="number" placeholder="Enter your mobile number" required>
            </div>

            <div class="form-group">
                <input type="submit" name="submit" value="Send OTP">
                <span class="error"><?php echo $login_err1; ?></span>
            </div>
        </form>
    </div>
</body>
</html>