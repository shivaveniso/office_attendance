<?php
session_start();
include 'db/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    echo "Not authenticated";
    exit();
}

$employee_id = $_SESSION['employee_id'];
$date = $_POST['date'];

// Sanitize input
$date = $conn->real_escape_string($date);

// Check if attendance already exists for today
$sql = "SELECT * FROM attendance WHERE employee_id = '$employee_id' AND DATE(check_in_time) = '$date'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Mark attendance
    $sql = "INSERT INTO attendance (employee_id, check_in_time, status) VALUES ('$employee_id', NOW(), 'present')";
    if ($conn->query($sql) === TRUE) {
        echo "Attendance marked successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Attendance already marked for this date";
}

$conn->close();
?>

