<?php
//session_start(); // Start session (if not already started)

if (!isset($_SESSION)) {
    echo "Session is not started.";
} else {
    echo "Session is active.";
}
?>
