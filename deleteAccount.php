<?php
// Start the session
session_start();

// Include the database connection file
require_once 'config.php'; // Update this to your actual database connection file name

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('You must be logged in to delete your account.'); window.location.href = 'login.php';</script>";
    exit;
}

// Retrieve the user's ID from the session
$uid = $_SESSION['uid'];

try {
    // Begin transaction
    $con->begin_transaction();

    // Prepare the SQL statement to delete user's properties
    $stmt = $con->prepare("DELETE FROM property WHERE uid = ?");
    $stmt->bind_param("i", $uid);

    // Execute the query to delete properties
    if (!$stmt->execute()) {
        throw new Exception("Error deleting properties.");
    }

    // Close the statement
    $stmt->close();

    // Prepare the SQL statement to delete the account
    $stmt = $con->prepare("DELETE FROM user WHERE uid = ?");
    $stmt->bind_param("i", $uid);

    // Execute the query to delete the account
    if ($stmt->execute()) {
        // Commit the transaction
        $con->commit();

        // Destroy the session
        session_destroy();

        // Redirect to a confirmation or homepage
        echo "<script>alert('Your account and properties have been successfully deleted.'); window.location.href = 'index.php';</script>";
    } else {
        throw new Exception("Error deleting account.");
    }

    // Close the statement
    $stmt->close();
} catch (Exception $e) {
    // Rollback the transaction
    $con->rollback();

    // Log error and notify user
    error_log($e->getMessage());
    echo "<script>alert('An unexpected error occurred. Please try again later.'); window.location.href = 'profile.php';</script>";
}

// Close the database connection
$con->close();
?>
