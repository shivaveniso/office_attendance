<?php
session_start(); // Start a session to manage user state


include 'db/db_connect.php'; // Include database connection

// Handling AJAX request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'login') {
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $password = $conn->real_escape_string($_POST['password']);

    // Check if employee exists and password is correct
    $sql = "SELECT * FROM employees WHERE employee_id = '$employee_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Password verification (use password hashing in production)
        if ($row['password'] === $password) {
            // Set session variable for logged-in user
            $_SESSION['employee_id'] = $employee_id;
            echo json_encode(['status' => 'success', 'redirect' => 'dashboard.php']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid Password!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Employee ID!']);
    }
    $conn->close();
    exit();
}
?>
