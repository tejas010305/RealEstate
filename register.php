<?php 
include("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";


$error="";
$msg="";
if(isset($_REQUEST['reg']))
{
	$name=$_REQUEST['name'];
	$email=$_REQUEST['email'];
	$phone=$_REQUEST['phone'];
	$pass=$_REQUEST['pass'];
	$utype=$_REQUEST['utype'];
	
	$uimage=$_FILES['uimage']['name'];
	$temp_name1 = $_FILES['uimage']['tmp_name'];
	
	
	$query = "SELECT * FROM user where uemail='$email'";
	$res=mysqli_query($con, $query);
	$num=mysqli_num_rows($res);
	
	if($num == 1)
	{
		$error = "<p class='alert alert-warning'>Email Id already Exist</p> ";
	}
	else
	{
		
		if(!empty($name) && !empty($email) && !empty($phone) && !empty($pass) && !empty($uimage))
		{
			
			$sql="INSERT INTO user (uname,uemail,uphone,upass,utype,uimage) VALUES ('$name','$email','$phone','$pass','$utype','$uimage')";
			$result=mysqli_query($con, $sql);
			move_uploaded_file($temp_name1,"admin/user/$uimage");
			if($result) {
                // Send email to the user using PHPMailer
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'smtp.gmail.com';                        // Set the SMTP server to send through
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'getintouchvastuhomes@gmail.com';
                    $mail->Password = 'iewl tfab gsqt sqpo';            // SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
                    $mail->Port = 587;                                    // TCP port to connect to

                    //Recipients
                    $mail->setFrom('your-email@gmail.com', 'Vastu Homes');
                    $mail->addAddress($email, $name);                     // Add a recipient

                    // Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Welcome to Vastu Homes!';
                    $mail->Body    = "
                    <html>
                    <head>
                    <title>Welcome to Vastu Homes</title>
                    </head>
                    <body>
                    <p>Hi $name,</p>
                    <p>Welcome to Vastu Homes! We're excited to have you as a part of our community.</p>
                    <p>Best regards,<br>Vastu Homes Team</p>
                    </body>
                    </html>
                    ";

                    $mail->send();
                    $msg = "<p class='alert alert-success'>Register Successfully. A welcome email has been sent!</p>";
                } catch (Exception $e) {
                    $error = "<p class='alert alert-warning'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
                }
            } else {
                $error = "<p class='alert alert-warning'>Register Not Successful</p>";
            }
		}else{
			$error = "<p class='alert alert-warning'>Please Fill all the fields</p>";
		}
	}
	
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Meta Tags -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="shortcut icon" href="images/favicon.ico">

<!--	Fonts
	========================================================-->
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

<!--	Css Link
	========================================================-->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/layerslider.css">
<link rel="stylesheet" type="text/css" href="css/color.css">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/login.css">

<!--	Title
	=========================================================-->
<title>Vastu Homes</title>
</head>
<body>

<!--	Page Loader
=============================================================
<div class="page-loader position-fixed z-index-9999 w-100 bg-white vh-100">
	<div class="d-flex justify-content-center y-middle position-relative">
	  <div class="spinner-border" role="status">
		<span class="sr-only">Loading...</span>
	  </div>
	</div>
</div>
--> 


<div id="page-wrapper">
    <div class="row"> 
        <!--	Header start  -->
		<?php include("include/header.php");?>
        <!--	Header end  -->
        
        <!--	Banner   --->
        <!-- <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Register</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-left float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Register</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div> -->
         <!--	Banner   --->
		 
		 
		 
		 <div class="page-wrappers login-body full-row bg-gray" style="position: relative; background: url('images/Register background.jpg') no-repeat center center/cover; height: 100vh;">
    <div class="login-wrapper">
        <div class="container">
            <div class="loginbox" style="background: transparent; font-family: Verdana, Geneva, Tahoma, sans-serif ">
                <div class="login-right" style="background: rgba(0, 0, 0, 0.6); padding: 20px; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); ">
                    <div class="login-right-wrap" style="color: white; ">
                        <h1>Register</h1>
                        <p class="account-subtitle" style="color: white;" >Access to our dashboard</p>
                        <?php echo $error; ?><?php echo $msg; ?>
                        <!-- Form -->
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Your Name*">
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Your Email*">
                            </div>
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control" placeholder="Your Phone*" maxlength="10">
                            </div>
                            <div class="form-group">
                                <input type="password" name="pass" class="form-control" placeholder="Your Password*">
                            </div>

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
                            <div class="form-check-inline disabled">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="utype" value="builder">Builder
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-form-label"><b>User Image</b></label>
                                <input class="form-control" name="uimage" type="file">
                            </div>
                            
                            <button class="btn btn-success" name="reg" value="Register" type="submit">Register</button>
                            
                        </form>
                        
                        <div class="login-or">
                            <span class="or-line"></span>
                            <span class="span-or" style="color: black;">or</span>
                        </div>
                        
                        <div class="social-login">
                            <span style="color: White">Register with</span>
                            <a href="https://r.search.yahoo.com/_ylt=AwrKGGwCoXNnrQIAaZi7HAx.;_ylu=Y29sbwNzZzMEcG9zAzEEdnRpZAMEc2VjA3Ny/RV=2/RE=1736840706/RO=10/RU=https%3a%2f%2fwww.facebook.com%2flogin.php%2f%3flang%3den-US/RK=2/RS=roY_eGFzGOU2QoG7rgvSVzIMgYE-" class="facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://r.search.yahoo.com/_ylt=AwrKHAU4oXNn2AIA_1W7HAx.;_ylu=Y29sbwNzZzMEcG9zAzEEdnRpZAMEc2VjA3Ny/RV=2/RE=1736840761/RO=10/RU=https%3a%2f%2faccounts.google.com%2flogin%3fhl%3den/RK=2/RS=Xs4uE7OhHkY9orUCzfv2ekLxZ0c-" class="google"><i class="fab fa-google"></i></a>
                            <a href="https://r.search.yahoo.com/_ylt=Awr1WT1yoXNnEAIAh.a7HAx.;_ylu=Y29sbwNzZzMEcG9zAzEEdnRpZAMEc2VjA3Ny/RV=2/RE=1736840818/RO=10/RU=https%3a%2f%2ftwitter.com%2flogin/RK=2/RS=3acqwitke2OIx_wJDKxPWZSP54Q-" class="facebook"><i class="fab fa-twitter"></i></a>
                            <a href="https://r.search.yahoo.com/_ylt=AwrKAXzIoXNn7AEAb227HAx.;_ylu=Y29sbwNzZzMEcG9zAzEEdnRpZAMEc2VjA3Ny/RV=2/RE=1736840905/RO=10/RU=https%3a%2f%2fwww.instagram.com%2faccounts%2flogin%2f/RK=2/RS=bdOzUyru_X0T3TPcGacslj0X2b8-" class="google"><i class="fab fa-instagram"></i></a>
                        </div>
                        <!-- /Social Login -->
                        
                        <div class="text-center dont-have" style="color: white;">Already have an account? <a href="login.php" style="color:  aqua;">Login</a></div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--    login  -->


<!--    Footer   start-->
<?php include("include/footer.php");?>
<!--    Footer   start-->

<!-- Scroll to top --> 
<a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
<!-- End Scroll To top --> 
</div>
</div>
<!-- Wrapper End --> 

<!--	Js Link
============================================================--> 
<script src="js/jquery.min.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/greensock.js"></script> 
<script src="js/layerslider.transitions.js"></script> 
<script src="js/layerslider.kreaturamedia.jquery.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/owl.carousel.min.js"></script> 
<script src="js/tmpl.js"></script> 
<script src="js/jquery.dependClass-0.1.js"></script> 
<script src="js/draggable-0.1.js"></script> 
<script src="js/jquery.slider.js"></script> 
<script src="js/wow.js"></script> 
<script src="js/custom.js"></script>
</body>
</html>
