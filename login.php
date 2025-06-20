<?php
session_start();
include("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";

$error = "";
$msg = "";

// Registration Logic
if (isset($_POST['reg'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $pass = $_POST['pass']; // Store password as plain text
    $utype = $_POST['utype'];

    // Handle file upload
    $uimage = '';
    $temp_name1 = '';
    if (isset($_FILES['uimage']) && !empty($_FILES['uimage']['name'])) {
        $uimage = $_FILES['uimage']['name'];
        $temp_name1 = $_FILES['uimage']['tmp_name'];
    }

    // Check if email already exists using prepared statement
    $query = "SELECT * FROM user WHERE uemail = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $num = mysqli_num_rows($res);

    if ($num == 1) {
        $error = "<p class='alert alert-warning'>Email Id already exists</p>";
    } else {
        if (!empty($name) && !empty($email) && !empty($phone) && !empty($pass) && !empty($uimage)) {
            // Insert user data using prepared statement
            $sql = "INSERT INTO user (uname, uemail, uphone, upass, utype, uimage) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $phone, $pass, $utype, $uimage);
            $result = mysqli_stmt_execute($stmt);

            if ($result && move_uploaded_file($temp_name1, "admin/user/$uimage")) {
                // Send email using PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'getintouchvastuhomes@gmail.com';
                    $mail->Password = 'iewl tfab gsqt sqpo'; // Use an App Password if 2FA is enabled
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('getintouchvastuhomes@gmail.com', 'Vastu Homes');
                    $mail->addAddress($email, $name);

                    $mail->isHTML(true);
                    $mail->Subject = 'Welcome to Vastu Homes!';
                    $mail->Body = "
                    <html>
                    <head><title>Welcome to Vastu Homes</title></head>
                    <body>
                    <p>Hi $name,</p>
                    <p>Welcome to Vastu Homes! We're excited to have you as a part of our community.</p>
                    <p>Best regards,<br>Vastu Homes Team</p>
                    </body>
                    </html>";

                    $mail->send();
                    $msg = "<p class='alert alert-success'>Registration successful! A welcome email has been sent.</p>";
                } catch (Exception $e) {
                    $error = "<p class='alert alert-warning'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
                }
            } else {
                $error = "<p class='alert alert-warning'>Registration failed. Please try again.</p>";
            }
        } else {
            $error = "<p class='alert alert-warning'>Please fill all the fields</p>";
        }
    }
    mysqli_stmt_close($stmt);
}

// Login Logic
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    if (!empty($email) && !empty($pass)) {
        // Check credentials using prepared statement
        $sql = "SELECT * FROM user WHERE uemail = ? AND upass = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $pass);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($result);

        if ($row) {
            $_SESSION['uid'] = $row['uid'];
            $_SESSION['uname'] = $row['uname'];
            $_SESSION['uemail'] = $row['uemail'];
            header("Location: index.php");
            exit();
        } else {
            $error = "<p class='alert alert-warning'>Email or Password does not match!</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "<p class='alert alert-warning'>Please fill all the fields</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Poppins", sans-serif;
            background-image: url('images/Login.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
        input { font-family: "Poppins", sans-serif; }
        .container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
        }
        .forms-container {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
        .signin-signup {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            left: 75%;
            width: 50%;
            transition: 1s 0.7s ease-in-out;
            display: grid;
            grid-template-columns: 1fr;
            z-index: 5;
        }
        form {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            width: 60%;
            margin-left: 10%;
            transition: all 0.2s 0.7s;
            overflow: hidden;
            grid-column: 1 / 2;
            grid-row: 1 / 2;
            background: rgba(0, 0, 0, 0.4);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            border: 2px solid #fff;
            padding: 20px;
        }
        form.sign-up-form { opacity: 0; z-index: 1; }
        form.sign-in-form { z-index: 2; }
        .title { font-size: 2.2rem; color: #444; margin-bottom: 10px; }
        .input-field {
            max-width: 380px;
            width: 100%;
            background-color: #f2f2f2;
            margin: 10px 0;
            height: 55px;
            border-radius: 55px;
            display: grid;
            grid-template-columns: 15% 85%;
            padding: 0 0.4rem;
            position: relative;
        }
        .input-field i {
            text-align: center;
            line-height: 55px;
            color: #b0b0b0;
            transition: 0.5s;
            font-size: 1.1rem;
        }
        .input-field input {
            background: none;
            outline: none;
            border: none;
            line-height: 1;
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
        }
        .input-field input::placeholder { color: #b0b0b0; font-weight: 500; }
        .btn {
            width: 150px;
            background-color: green;
            border: none;
            outline: none;
            height: 49px;
            border-radius: 49px;
            color: #fff;
            text-transform: uppercase;
            font-weight: 600;
            margin: 10px 0;
            cursor: pointer;
            transition: 0.5s;
        }
        .btn:hover { background-color: purple; }
        .panels-container {
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }
        .container:before {
            content: "";
            position: absolute;
            height: 2000px;
            width: 2000px;
            top: -10%;
            right: 48%;
            transform: translateY(-50%);
            background-image: linear-gradient(180deg, blue, #800080);
            transition: 1.8s ease-in-out;
            border-radius: 50%;
            z-index: 6;
        }
        .image {
            width: 100%;
            transition: transform 1.1s ease-in-out;
            transition-delay: 0.4s;
        }
        .panel {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-around;
            text-align: center;
            z-index: 6;
        }
        .left-panel { pointer-events: all; padding: 3rem 17% 2rem 12%; }
        .right-panel { pointer-events: none; padding: 3rem 12% 2rem 17%; }
        .panel .content {
            color: #fff;
            transition: transform 0.9s ease-in-out;
            transition-delay: 0.6s;
        }
        .panel h3 { font-weight: 600; line-height: 1; font-size: 1.5rem; }
        .panel p { font-size: 0.95rem; padding: 0.7rem 0; }
        .btn.transparent {
            margin: 0;
            background: none;
            border: 2px solid #fff;
            width: 130px;
            height: 41px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .right-panel .image, .right-panel .content { transform: translateX(800px); }
        .container.sign-up-mode:before { transform: translate(100%, -50%); right: 52%; }
        .container.sign-up-mode .left-panel .image, .container.sign-up-mode .left-panel .content { transform: translateX(-800px); }
        .container.sign-up-mode .signin-signup { left: 25%; }
        .container.sign-up-mode form.sign-up-form { opacity: 1; z-index: 2; }
        .container.sign-up-mode form.sign-in-form { opacity: 0; z-index: 1; }
        .container.sign-up-mode .right-panel .image, .container.sign-up-mode .right-panel .content { transform: translateX(0%); }
        .container.sign-up-mode .left-panel { pointer-events: none; }
        .container.sign-up-mode .right-panel { pointer-events: all; }
        .account-subtitle { color: #b0b0b0; margin-bottom: 15px; }
        .login-or {
            position: relative;
            text-align: center;
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        .or-line { height: 1px; background-color: #e5e5e5; flex: 1; margin: 0 10px; }
        .span-or {
            background-color: rgba(0, 0, 0, 0.6);
            display: inline-block;
            padding: 5px 15px;
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }
        .dont-have { margin-top: 15px; }
        .dont-have a { color: #ff6f61; text-decoration: none; transition: 0.3s; }
        .dont-have a:hover { text-decoration: underline; }
        .alert.alert-warning { padding: 10px; background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; border-radius: 4px; margin: 10px 0; }
        .alert.alert-success { padding: 10px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 4px; margin: 10px 0; }
        .Manual { color: rgb(255, 255, 255); list-style: none; display: flex; gap: 20px; }
        .form-group { color: rgb(255, 255, 255); margin-left: 20%; }
    </style>
    <title>Login Form</title>
</head>
<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form method="post" class="sign-in-form">
                    <h2 class="title" style="color: white;">Login</h2>
                    <p class="account-subtitle" style="color: white;">Access to our dashboard</p>
                    <?php echo $error; ?>
                    <?php echo $msg; ?>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Your Email*" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="pass" placeholder="Your Password" required />
                    </div>
                    <button class="btn" name="login" value="Login" type="submit">Login</button>
                    <div class="login-or">
                        <span class="or-line"></span>
                        <span class="span-or">or</span>
                        <span class="or-line"></span>
                    </div>
                    <div class="dont-have" style="color: white;">Don't have an account? <a href="#" style="color: aqua;">Register</a></div>
                    <div class="dont-have" style="color: white;">Forgot Password? <a href="forget.php" style="color: aqua;">Forgot Password</a></div>
                </form>

                <form method="post" enctype="multipart/form-data" class="sign-up-form">
                    <h2 class="title" style="color: white;">Sign Up</h2>
                    <?php echo $error; ?>
                    <?php echo $msg; ?>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" placeholder="Username" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-phone"></i>
                        <input type="tel" name="phone" placeholder="Phone" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="pass" placeholder="Password" required />
                    </div>
                    <div class="Manual">
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="utype" value="user" checked>User
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="utype" value="agent">Agent
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="utype" value="builder">Builder
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="col-form-label"><b>User Image</b></label>
                        <input class="form-control" name="uimage" type="file" required />
                    </div>
                    <br>
                    <input type="submit" class="btn" name="reg" value="Sign Up" />
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Welcome To VastuHomes!</h3>
                    <p>Login to access your account and manage your dashboard efficiently.</p>
                    <button class="btn transparent" id="sign-up-btn">Sign Up</button>
                </div>
                <img src="images/login_png1.png" alt="placeholder" class="image" />
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>One of us?</h3>
                    <p>Already have an account? Sign in and continue your journey with us.</p>
                    <button class="btn transparent" id="sign-in-btn">Sign In</button>
                </div>
                <img src="images/login png 2.png" alt="placeholder" class="image" />
            </div>
        </div>
    </div>

    <script>
        const sign_in_btn = document.querySelector("#sign-in-btn");
        const sign_up_btn = document.querySelector("#sign-up-btn");
        const container = document.querySelector(".container");

        sign_up_btn.addEventListener("click", () => {
            container.classList.add("sign-up-mode");
        });

        sign_in_btn.addEventListener("click", () => {
            container.classList.remove("sign-up-mode");
        });
    </script>
</body>
</html>