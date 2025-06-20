<?php
session_start();
include 'config.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "PHPMailer/src/Exception.php";
include "PHPMailer/src/PHPMailer.php";
include "PHPMailer/src/SMTP.php";

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['uid']; // Ensure user_id is an integer

// Function to determine progress percentage
function getProgress($status) {
    switch ($status) {
        case 'Pending': return 25;
        case 'Accepted': return 50;
        case 'Completed': return 100;
        case 'Rejected': return 0;
        case 'Cancelled': return 0;
        default: return 0;
    }
}

// Delete cancelled requests older than 5 hours
$sql_delete_old = "DELETE FROM requests WHERE status = 'Cancelled' AND date < NOW() - INTERVAL 5 HOUR";
$con->query($sql_delete_old);

// Fetch user's SENT requests
$sql_sent = "SELECT requests.id, requests.uid AS agent_id, requests.pid, property.title, requests.status, requests.date,
             sender.uname AS sender_name, agent.uname AS agent_name
             FROM requests 
             JOIN property ON requests.pid = property.pid
             JOIN user AS sender ON requests.id = sender.uid
             JOIN user AS agent ON requests.uid = agent.uid
             WHERE requests.id = ? 
             ORDER BY requests.date DESC";
$stmt_sent = $con->prepare($sql_sent);
if (!$stmt_sent) die("SQL Prepare Error (Sent): " . $con->error);
$stmt_sent->bind_param("i", $user_id);
if (!$stmt_sent->execute()) die("Execution Error (Sent): " . $stmt_sent->error);
$result_sent = $stmt_sent->get_result();
if (!$result_sent) die("Fetching Error (Sent): " . $stmt_sent->error);

// Fetch RECEIVED requests
$sql_received = "SELECT requests.id AS requester_id, requests.uid AS agent_id, requests.pid, property.title, requests.status, requests.date,
                 requester.uname AS requester_name, agent.uname AS agent_name
                 FROM requests 
                 JOIN property ON requests.pid = property.pid
                 JOIN user AS requester ON requests.id = requester.uid
                 JOIN user AS agent ON requests.uid = agent.uid
                 WHERE property.uid = ? 
                 ORDER BY requests.date DESC";
$stmt_received = $con->prepare($sql_received);
if (!$stmt_received) die("SQL Prepare Error (Received): " . $con->error);
$stmt_received->bind_param("i", $user_id);
if (!$stmt_received->execute()) die("Execution Error (Received): " . $stmt_received->error);
$result_received = $stmt_received->get_result();
if (!$result_received) die("Fetching Error (Received): " . $stmt_received->error);

// Cancel SENT request (DELETE)
if (isset($_POST['cancel_user_id']) && isset($_POST['cancel_pid'])) {
    $request_user_id = (int)$_POST['cancel_user_id'];
    $pid = (int)$_POST['cancel_pid'];
    
    $sql_cancel = "DELETE FROM requests WHERE id = ? AND pid = ? AND status = 'Pending'";
    $stmt_cancel = $con->prepare($sql_cancel);
    if (!$stmt_cancel) die("SQL Error: " . $con->error);
    $stmt_cancel->bind_param("ii", $request_user_id, $pid);
    
    if ($stmt_cancel->execute()) {
        if ($stmt_cancel->affected_rows > 0) {
            $stmt_sent->execute();
            $result_sent = $stmt_sent->get_result();
            echo "<script>alert('Request cancelled and deleted successfully.');</script>";
        } else {
            echo "<script>alert('No rows deleted: Check if User ID=$request_user_id, PID=$pid exists and is Pending.');</script>";
        }
    } else {
        echo "<script>alert('Failed to cancel request: " . addslashes($stmt_cancel->error) . "');</script>";
    }
    $stmt_cancel->close();
}

// Accept RECEIVED request and send email with PHPMailer
if (isset($_POST['accept_pid']) && isset($_POST['accept_requester_id'])) {
    $pid = (int)$_POST['accept_pid'];
    $requester_id = (int)$_POST['accept_requester_id'];
    
    $sql_accept = "UPDATE requests SET status = 'Accepted' 
                   WHERE pid = ? AND id = ? AND status = 'Pending'";
    $stmt_accept = $con->prepare($sql_accept);
    if (!$stmt_accept) die("SQL Error: " . $con->error);
    $stmt_accept->bind_param("ii", $pid, $requester_id);
    
    if ($stmt_accept->execute() && $stmt_accept->affected_rows > 0) {
        $stmt_received->execute();
        $result_received = $stmt_received->get_result();

        // Fetch email addresses and property title
        $sql_requester = "SELECT uemail FROM user WHERE uid = ?";
        $stmt_requester = $con->prepare($sql_requester);
        $stmt_requester->bind_param("i", $requester_id);
        $stmt_requester->execute();
        $requester_email = $stmt_requester->get_result()->fetch_assoc()['uemail'] ?? '';
        $stmt_requester->close();

        $sql_owner = "SELECT uemail FROM user WHERE uid = ?";
        $stmt_owner = $con->prepare($sql_owner);
        $stmt_owner->bind_param("i", $user_id);
        $stmt_owner->execute();
        $owner_email = $stmt_owner->get_result()->fetch_assoc()['uemail'] ?? '';
        $stmt_owner->close();

        $sql_property = "SELECT title FROM property WHERE pid = ?";
        $stmt_property = $con->prepare($sql_property);
        $stmt_property->bind_param("i", $pid);
        $stmt_property->execute();
        $property_title = $stmt_property->get_result()->fetch_assoc()['title'] ?? '';
        $stmt_property->close();

        // Send email using PHPMailer
        if ($requester_email && $owner_email && $property_title) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'getintouchvastuhomes@gmail.com';
                $mail->Password = 'iewl tfab gsqt sqpo'; // Use App Password for Gmail
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('getintouchvastuhomes@gmail.com', 'VastuHome');
                $mail->addAddress($requester_email);
                $mail->addCC($owner_email);

                $mail->isHTML(true);
                $mail->Subject = "Your Property Request Accepted - VastuHome";
                $mail->Body = "Dear User,<br><br>Your request to buy the property '<strong>" . htmlspecialchars($property_title) . "</strong>' has been accepted by the owner.<br><br>Regards,<br>VastuHome Team";
                $mail->AltBody = "Dear User,\n\nYour request to buy the property '" . htmlspecialchars($property_title) . "' has been accepted by the owner.\n\nRegards,\nVastuHome Team";

                $mail->send();
                echo "<script>alert('Request accepted successfully. Email sent to requester and owner.');</script>";
            } catch (Exception $e) {
                echo "<script>alert('Request accepted successfully, but email sending failed: " . addslashes($mail->ErrorInfo) . "');</script>";
            }
        } else {
            echo "<script>alert('Request accepted, but email details could not be fetched.');</script>";
        }
    } else {
        echo "<script>alert('No rows updated: Check if PID=$pid, Requester ID=$requester_id exists and is Pending.');</script>";
    }
    $stmt_accept->close();
}

// Complete RECEIVED request and send email with PHPMailer
if (isset($_POST['complete_pid']) && isset($_POST['complete_requester_id'])) {
    $pid = (int)$_POST['complete_pid'];
    $requester_id = (int)$_POST['complete_requester_id'];
    
    $sql_complete = "UPDATE requests SET status = 'Completed' 
                     WHERE pid = ? AND id = ? AND status = 'Accepted'";
    $stmt_complete = $con->prepare($sql_complete);
    if (!$stmt_complete) die("SQL Error: " . $con->error);
    $stmt_complete->bind_param("ii", $pid, $requester_id);
    
    if ($stmt_complete->execute() && $stmt_complete->affected_rows > 0) {
        $stmt_received->execute();
        $result_received = $stmt_received->get_result();

        // Fetch email addresses and property title
        $sql_requester = "SELECT uemail FROM user WHERE uid = ?";
        $stmt_requester = $con->prepare($sql_requester);
        $stmt_requester->bind_param("i", $requester_id);
        $stmt_requester->execute();
        $requester_email = $stmt_requester->get_result()->fetch_assoc()['uemail'] ?? '';
        $stmt_requester->close();

        $sql_owner = "SELECT uemail FROM user WHERE uid = ?";
        $stmt_owner = $con->prepare($sql_owner);
        $stmt_owner->bind_param("i", $user_id);
        $stmt_owner->execute();
        $owner_email = $stmt_owner->get_result()->fetch_assoc()['uemail'] ?? '';
        $stmt_owner->close();

        $sql_property = "SELECT title FROM property WHERE pid = ?";
        $stmt_property = $con->prepare($sql_property);
        $stmt_property->bind_param("i", $pid);
        $stmt_property->execute();
        $property_title = $stmt_property->get_result()->fetch_assoc()['title'] ?? '';
        $stmt_property->close();

        // Send email using PHPMailer
        if ($requester_email && $owner_email && $property_title) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'getintouchvastuhomes@gmail.com';
                $mail->Password = 'iewl tfab gsqt sqpo'; // Use App Password for Gmail
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('getintouchvastuhomes@gmail.com', 'VastuHome');
                $mail->addAddress($requester_email);
                $mail->addCC($owner_email);

                $mail->isHTML(true);
                $mail->Subject = "Property Request Completed - VastuHome";
                $mail->Body = "Dear User,<br><br>Your request for the property '<strong>" . htmlspecialchars($property_title) . "</strong>' has been completed successfully.<br><br>Thank you for using VastuHome!<br><br>Regards,<br>VastuHome Team";
                $mail->AltBody = "Dear User,\n\nYour request to buy the property '" . htmlspecialchars($property_title) . "' has been completed successfully.\n\nThank you for using VastuHome!\n\nRegards,\nVastuHome Team";

                $mail->send();
                echo "<script>alert('Request completed successfully. Email sent to requester and owner.');</script>";
            } catch (Exception $e) {
                echo "<script>alert('Request completed successfully, but email sending failed: " . addslashes($mail->ErrorInfo) . "');</script>";
            }
        } else {
            echo "<script>alert('Request completed, but email details could not be fetched.');</script>";
        }
    } else {
        echo "<script>alert('No rows updated: Check if PID=$pid, Requester ID=$requester_id exists and is Accepted.');</script>";
    }
    $stmt_complete->close();
}

// Reject RECEIVED request and send email with PHPMailer (DELETE)
if (isset($_POST['reject_pid']) && isset($_POST['reject_requester_id']) && isset($_POST['reject_reason'])) {
    $pid = (int)$_POST['reject_pid'];
    $requester_id = (int)$_POST['reject_requester_id'];
    $reason = trim($_POST['reject_reason']);
    
    $sql_reject = "DELETE FROM requests WHERE pid = ? AND id = ? AND status = 'Pending'";
    $stmt_reject = $con->prepare($sql_reject);
    if (!$stmt_reject) die("SQL Error: " . $con->error);
    $stmt_reject->bind_param("ii", $pid, $requester_id);
    
    if ($stmt_reject->execute() && $stmt_reject->affected_rows > 0) {
        $stmt_received->execute();
        $result_received = $stmt_received->get_result();

        // Fetch email addresses and property title
        $sql_requester = "SELECT uemail FROM user WHERE uid = ?";
        $stmt_requester = $con->prepare($sql_requester);
        $stmt_requester->bind_param("i", $requester_id);
        $stmt_requester->execute();
        $requester_email = $stmt_requester->get_result()->fetch_assoc()['uemail'] ?? '';
        $stmt_requester->close();

        $sql_owner = "SELECT uemail FROM user WHERE uid = ?";
        $stmt_owner = $con->prepare($sql_owner);
        $stmt_owner->bind_param("i", $user_id);
        $stmt_owner->execute();
        $owner_email = $stmt_owner->get_result()->fetch_assoc()['uemail'] ?? '';
        $stmt_owner->close();

        $sql_property = "SELECT title FROM property WHERE pid = ?";
        $stmt_property = $con->prepare($sql_property);
        $stmt_property->bind_param("i", $pid);
        $stmt_property->execute();
        $property_title = $stmt_property->get_result()->fetch_assoc()['title'] ?? '';
        $stmt_property->close();

        // Send email using PHPMailer
        if ($requester_email && $owner_email && $property_title) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'getintouchvastuhomes@gmail.com';
                $mail->Password = 'iewl tfab gsqt sqpo'; // Use App Password for Gmail
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('getintouchvastuhomes@gmail.com', 'VastuHome');
                $mail->addAddress($requester_email);
                $mail->addCC($owner_email);

                $mail->isHTML(true);
                $mail->Subject = "Your Property Request Rejected - VastuHome";
                $mail->Body = "Dear User,<br><br>Your request to buy the property '<strong>" . htmlspecialchars($property_title) . "</strong>' has been rejected and removed.<br>Reason: " . htmlspecialchars($reason) . "<br><br>Regards,<br>VastuHome Team";
                $mail->AltBody = "Dear User,\n\nYour request to buy the property '" . htmlspecialchars($property_title) . "' has been rejected and removed.\nReason: " . htmlspecialchars($reason) . "\n\nRegards,\nVastuHome Team";

                $mail->send();
                echo "<script>alert('Request rejected and deleted successfully. Email sent to requester and owner with reason.');</script>";
            } catch (Exception $e) {
                echo "<script>alert('Request rejected and deleted successfully, but email sending failed: " . addslashes($mail->ErrorInfo) . "');</script>";
            }
        } else {
            echo "<script>alert('Request rejected and deleted, but email details could not be fetched.');</script>";
        }
    } else {
        echo "<script>alert('No rows deleted: Check if PID=$pid, Requester ID=$requester_id exists and is Pending.');</script>";
    }
    $stmt_reject->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Request History - VastuHome</title>
    <!-- Bootstrap and Custom CSS -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta Tags -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="images/favicon.ico">

    <!--	Fonts
    ========================================================-->
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&display=swap" rel="stylesheet">
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
    <style>
    /* General Styles */
body {
    font-family: 'Muli', Arial, sans-serif; /* Match login.php font */
    background-color: #f4f4f4;
    margin: 0;
}

/* Table Styles */
.requests-table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(14, 224, 123, 0.1);
}

.requests-table th {
    background-color: #4CAF50; /* Green header from login.php theme */
    color: white;
}

.requests-table td, .requests-table th {
    padding: 12px;
    text-align: center;
}

.requests-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.requests-table tr:hover {
    background-color: #f1f1f1;
}

/* Progress Bar Styles */
.progress-container {
    width: 100%;
    background-color: #ddd;
    border-radius: 10px;
    overflow: hidden;
    margin: 10px 0;
}

.progress-bar {
    height: 25px;
    text-align: center;
    line-height: 25px;
    color: white;
    border-radius: 10px;
    transition: width 0.5s ease-in-out;
}

.progress-bar.pending {
    background-color: orange;
}

.progress-bar.accepted {
    background-color: #007bff; /* Bootstrap primary blue */
}

.progress-bar.completed {
    background-color: #28a745; /* Bootstrap success green */
}

.progress-bar.rejected {
    background-color: #dc3545; /* Bootstrap danger red */
}

.progress-bar.cancelled {
    background-color: #6c757d; /* Bootstrap secondary gray */
}

/* Button Styles */
.cancel-btn {
    background-color: #ff4444; /* Red */
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.accept-btn {
    background-color: #4CAF50; /* Green from login.php */
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.reject-btn {
    background-color: #ff9800; /* Orange */
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.complete-btn {
    background-color: #2196F3; /* Blue */
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.cancel-btn:disabled, .accept-btn:disabled, .reject-btn:disabled, .complete-btn:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}

/* Reason Input */
.reason-input {
    margin: 5px;
    padding: 5px;
    width: 150px;
    display: inline-block;
    vertical-align: middle;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .requests-table th, .requests-table td {
        padding: 8px;
    }

    .reason-input {
        width: 100px;
    }

    .btn {
        padding: 4px 8px;
        font-size: 0.9rem;
    }
}
    </style>
</head>
<body>
    <div id="page-wrapper">
        <div class="row">
            <!-- Include Header -->
            <?php include("include/header.php"); ?>

            <div class="container mt-4">
                <!-- Sent Requests Section -->
                <h2 class="text-center mb-4">Your Sent Property Requests</h2>
                <?php if ($result_sent->num_rows > 0) { ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover requests-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Sender Name</th>
                                    <th>Agent Name</th>
                                    <th>Property Name</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result_sent->fetch_assoc()) { 
                                    $progress = getProgress($row['status']);
                                    $statusClass = strtolower($row['status']);
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['sender_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['agent_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <td>
                                            <div class="progress-container">
                                                <div class="progress-bar <?php echo $statusClass; ?>" style="width: <?php echo $progress; ?>%;">
                                                    <?php echo $progress; ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                                        <td>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="cancel_user_id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="cancel_pid" value="<?php echo $row['pid']; ?>">
                                                <button type="submit" class="btn cancel-btn" <?php echo ($row['status'] !== 'Pending') ? 'disabled' : ''; ?>>Cancel</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <p class="text-center text-danger font-weight-bold">No sent property requests found.</p>
                <?php } ?>

                <!-- Received Requests Section -->
                <h2 class="text-center mb-4 mt-5">Requests for Your Properties</h2>
                <?php if ($result_received->num_rows > 0) { ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover requests-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Requester Name</th>
                                    <th>Agent Name</th>
                                    <th>Property Name</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result_received->fetch_assoc()) { 
                                    $progress = getProgress($row['status']);
                                    $statusClass = strtolower($row['status']);
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['requester_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['agent_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <td>
                                            <div class="progress-container">
                                                <div class="progress-bar <?php echo $statusClass; ?>" style="width: <?php echo $progress; ?>%;">
                                                    <?php echo $progress; ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                                        <td>
                                            <?php if ($row['status'] === 'Pending') { ?>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="accept_pid" value="<?php echo $row['pid']; ?>">
                                                    <input type="hidden" name="accept_requester_id" value="<?php echo $row['requester_id']; ?>">
                                                    <button type="submit" class="btn accept-btn">Accept</button>
                                                </form>
                                                <form method="POST" class="d-inline" onsubmit="return validateReason(this);">
                                                    <input type="hidden" name="reject_pid" value="<?php echo $row['pid']; ?>">
                                                    <input type="hidden" name="reject_requester_id" value="<?php echo $row['requester_id']; ?>">
                                                    <input type="text" name="reject_reason" class="reason-input form-control d-inline-block" placeholder="Reason" required>
                                                    <button type="submit" class="btn reject-btn">Reject</button>
                                                </form>
                                            <?php } elseif ($row['status'] === 'Accepted') { ?>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="complete_pid" value="<?php echo $row['pid']; ?>">
                                                    <input type="hidden" name="complete_requester_id" value="<?php echo $row['requester_id']; ?>">
                                                    <button type="submit" class="btn complete-btn">Complete</button>
                                                </form>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <p class="text-center text-danger font-weight-bold">No requests received for your properties.</p>
                <?php } ?>
            </div><!-- Close .container -->

            <!-- Include Footer -->
            <?php include("include/footer.php"); ?>

            <!-- Scroll to top -->
            <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a>
        </div><!-- Close .row -->
    </div><!-- Close #page-wrapper -->

    <!-- JavaScript Includes -->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        function validateReason(form) {
            var reason = form.reject_reason.value.trim();
            if (reason === "") {
                alert("Please provide a reason for rejection.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>

<?php
$stmt_sent->close();
$stmt_received->close();
$con->close();
?>