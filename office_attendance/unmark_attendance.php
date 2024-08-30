<?php
session_start(); // Make sure session is started

if (!isset($_SESSION['employee_id'])) {
    echo "Error: No session started";
    exit();
}

include 'db/db_connect.php';

$employee_id = $_SESSION['employee_id'];
$date = $_POST['date'] ?? ''; // Ensure the date is properly retrieved

// Check if date is provided
if (empty($date)) {
    echo "Error: No date provided";
    exit();
}

// Sanitize the input to prevent SQL Injection
$date = $conn->real_escape_string($date);

// Ensure that the date format is correct and consistent
// For example, if check_in_time is a datetime field and you only want to compare the date part
$sql = "DELETE FROM attendance WHERE employee_id = '$employee_id' AND DATE(check_in_time) = '$date'";

if ($conn->query($sql) === TRUE) {
    echo "Attendance unmarked successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

