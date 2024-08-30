<?php
session_start(); // Make sure session is started

if (!isset($_SESSION['employee_id'])) {
    header('Location: index.html');
    exit();
}

include 'db/db_connect.php';

$employee_id = $_SESSION['employee_id'];
$currentYear = date('Y');
$currentMonth = date('m');

// Fetch employee details
$employeeSql = "SELECT name FROM employees WHERE employee_id = '$employee_id'";
$employeeResult = $conn->query($employeeSql);
$employee = $employeeResult->fetch_assoc();

// Get attendance data for the current month
$startDate = "$currentYear-$currentMonth-01";
$endDate = date('Y-m-t', strtotime($startDate)); // Last day of the month

$sql = "SELECT * FROM attendance WHERE employee_id = '$employee_id' AND check_in_time BETWEEN '$startDate' AND '$endDate'";
$result = $conn->query($sql);

$attendanceData = [];
while ($row = $result->fetch_assoc()) {
    $date = date('Y-m-d', strtotime($row['check_in_time']));
    $attendanceData[$date] = 'present';
}

$daysInMonth = new DateTime($startDate);
$daysInMonth->modify('last day of this month');
$totalDays = $daysInMonth->format('t');

$workingDays = 0;
$presentDays = 0;

for ($day = 1; $day <= $totalDays; $day++) {
    $date = "$currentYear-$currentMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);
    $dayOfWeek = date('N', strtotime($date));

    if ($dayOfWeek < 6) { // Monday to Friday are working days
        $workingDays++;
        if (isset($attendanceData[$date])) {
            $presentDays++;
        }
    }
}

$attendancePercentage = $workingDays ? ($presentDays / $workingDays) * 100 : 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
        }

        h2 {
            text-align: center;
            color: #4e54c8;
            margin-bottom: 20px;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            max-width: 100%;
            margin: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
            padding: 5px;
        }

        .calendar-day {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .calendar-day:hover {
            background-color: #e0e0e0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .calendar-day.today {
            background-color: #cce5ff;
            border: 2px solid #4e54c8;
            font-weight: bold;
        }

        .calendar-day.weekend {
            background-color: #e74c3c; /* Deep red for weekends */
            color: white;
        }

        .calendar-day.holiday {
            background-color: #f8d7da; /* Light red for holidays */
            color: #721c24;
        }

        .status-dot {
            display: block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            position: absolute;
            bottom: 5px;
            right: 5px;
        }

        .status-dot.present {
            background-color: green;
        }

        .status-dot.absent {
            background-color: red;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .popup.active {
            display: block;
        }

        .popup button {
            margin: 5px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4e54c8;
            color: white;
            cursor: pointer;
        }

        .popup button:hover {
            background-color: #8f94fb;
        }

        .logout-button {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #ff4e4e;
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .logout-button:hover {
            background-color: #ff7878;
        }

        .attendance-percentage {
            margin-top: 20px;
            font-size: 18px;
            text-align: center;
            color: #4e54c8;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($employee['name']); ?></h2>
        <div id="calendar" class="calendar"></div>
        <button class="logout-button" onclick="logout()">Logout</button>
        <div class="attendance-percentage">
            Attendance Percentage: <?php echo number_format($attendancePercentage, 2); ?>%
        </div>
    </div>

    <!-- Popup -->
    <div id="popup" class="popup">
        <p>Mark your attendance for <span id="popup-date"></span>?</p>
        <button onclick="markAttendance()">Mark Attendance</button>
        <button onclick="unmarkAttendance()">Unmark Attendance</button>
        <button onclick="closePopup()">Cancel</button>
    </div>

    <script src="js/script.js"></script>
</body>
</html>

