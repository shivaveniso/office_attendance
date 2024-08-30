<?php
session_start();
include 'db/db_connect.php';

if (!isset($_SESSION['employee_id'])) {
    echo json_encode([]);
    exit();
}

$employee_id = $_SESSION['employee_id'];
$currentYear = date('Y');
$currentMonth = date('m');

$sql = "SELECT DATE(check_in_time) as date, status FROM attendance WHERE employee_id = '$employee_id' AND DATE(check_in_time) LIKE '$currentYear-$currentMonth%'";
$result = $conn->query($sql);

$attendanceData = [];
while ($row = $result->fetch_assoc()) {
    $attendanceData[$row['date']] = $row['status'];
}

$conn->close();
echo json_encode($attendanceData);
?>

