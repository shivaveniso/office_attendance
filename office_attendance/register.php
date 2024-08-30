<?php
// Include database connection file
include 'db/db_connect.php';

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Insert new employee record
        $sql = "INSERT INTO employees (employee_id, name, password) VALUES ('$employee_id', '$name', '$password')"; // Use hashed password in production
        if ($conn->query($sql) === TRUE) {
            $message = "Registration successful!";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .logo {
            margin-bottom: 20px;
            max-width: 250px;
            height: auto;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #4e54c8;
            margin-bottom: 20px;
        }

        input {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4e54c8;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #8f94fb;
        }

        .message {
            color: red;
            text-align: center;
        }

        .redirect-button {
            display: block;
            margin-top: 10px;
            text-align: center;
            color: #4e54c8;
            cursor: pointer;
        }

        .redirect-button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <img src="signature.png" alt="Logo" class="logo">
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <input type="text" name="employee_id" placeholder="Employee ID" required>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
            <?php if (isset($message)): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        </form>
        <div class="redirect-button">
            <a href="index.html">Already have an account? Login</a>
	</div>

    </div>

    <p>Copyright @ <b>Office Pulse</b> 2024</p>
</body>
</html>