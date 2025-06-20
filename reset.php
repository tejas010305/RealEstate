<?php
// Include database configuration
include("config.php");

// Initialize variables
$emailId = $otp = $new_password = '';
$otp_err = $password_err = $reset_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form fields are set before accessing them
    $emailId = isset($_POST['emailId']) ? mysqli_real_escape_string($con, trim($_POST['emailId'])) : '';
    $otp = isset($_POST['otp']) ? mysqli_real_escape_string($con, trim($_POST['otp'])) : '';
    $new_password = isset($_POST['new_password']) ? mysqli_real_escape_string($con, trim($_POST['new_password'])) : '';

    // Proceed only if all fields are provided
    if (!empty($emailId) && !empty($otp) && !empty($new_password)) {
        $currentDate = date("Y-m-d H:i:s");

        // Verify OTP and expiry
        $sql = "SELECT * FROM user WHERE uemail='$emailId' AND otp='$otp' AND otp_expiry > '$currentDate'";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            // Hash the new password for security
            $hashed_password = $new_password;

            // Update password in the database
            $update_sql = "UPDATE user SET upass='$hashed_password', otp=NULL, otp_expiry=NULL WHERE uemail='$emailId'";
            if (mysqli_query($con, $update_sql)) {
                $reset_success = "Password has been reset successfully!";
                header("Location: login.php"); // Redirect to login page
                exit;
            } else {
                $password_err = "Failed to reset password. Please try again later.";
            }
        } else {
            $otp_err = "Invalid OTP or OTP has expired.";
        }
    } else {
        $otp_err = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    form {
        background-color: rgba(0, 0, 0, 0.7); /* Black transparent background */
        padding: 25px 35px;
        border: 1px solid rgba(255, 255, 255, 0.3); /* Slightly visible border */
        border-radius: 10px;
        max-width: 400px;
        width: 100%;
        text-align: left;
        color: white; /* Default text color for form */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Optional: adds depth */
    }

    form label {
        font-weight: bold;
        color: white;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }

    form input[type="text"],
    form input[type="password"],
    form input[type="number"] {
        width: 100%;
        padding: 10px 15px;
        margin-bottom: 15px;
        border: 1px solid rgba(255, 255, 255, 0.5); /* White-ish border */
        border-radius: 5px;
        font-size: 14px;
        background-color: rgba(255, 255, 255, 0.2); /* Slightly visible input background */
        color: white; /* Input text color */
    }

    form input[type="submit"] {
        background-color: #28a745;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
        transition: background-color 0.3s;
    }

    form input[type="submit"]:hover {
        background-color: #218838;
    }

    form span {
        color: white;
        font-size: 14px;
        display: block;
    }

    .success {
        color: white;
    }

    h3 {
        text-align: center;
        color: white;
    }

    .reset-title {
        text-align: center;
        color: white;
    }

    /* Optional: Add placeholder color for better visibility */
    form input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Optional: Add a backdrop filter for a modern look */
    form {
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }
</style>
</head>
<body>

  

    <div style="background-image: url('images/Reset background new.jpg'); 
                height:100vh; 
                width:100%; 
                background-size: cover; 
                display: flex; 
                justify-content: center; 
                align-items: center; 
                padding: 20px;">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <h3 class="reset-title">Reset Password</h3>
            <p class="account-subtitle">Enter OTP and New Password</p>

            <input type="text" name="emailId" placeholder="Email Id" required >
            <input type="text" name="otp" placeholder="OTP" required>
            <div id="otp-timer" style="color:white;">OTP expires in: <span id="time" style="color:red;">02:00</span></div>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="submit" name="submit" value="Reset Password">
            
            <span><?php echo $otp_err; ?></span>
            <span><?php echo $password_err; ?></span>
            <span class="success"><?php echo $reset_success; ?></span>
        </form>
    </div>

     <!-- <?php include("include/footer.php"); ?>   -->

    <script>
        // OTP Timer Script
        let timerDuration = 120; // 2 minutes
        const timerElement = document.getElementById('time');
        const formElement = document.querySelector('form');

        const countdown = setInterval(() => {
            const minutes = Math.floor(timerDuration / 60);
            const seconds = timerDuration % 60;
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            if (timerDuration <= 0) {
                clearInterval(countdown);
                timerElement.textContent = 'Expired';
                formElement.querySelector('input[name="otp"]').disabled = true;
                alert('Your OTP has expired. Please request a new one.');
            }
            timerDuration--;
        }, 1000);
    </script>
</body>
</html>
