-- Create the database
CREATE DATABASE IF NOT EXISTS office_attendance;

-- Switch to the database
USE office_attendance;

-- Create the 'employees' table
CREATE TABLE IF NOT EXISTS employees (
    id INT NOT NULL AUTO_INCREMENT,
    employee_id VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

-- Create the 'attendance' table
CREATE TABLE IF NOT EXISTS attendance (
    id INT NOT NULL AUTO_INCREMENT,
    employee_id VARCHAR(50) NOT NULL,
    check_in_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('present', 'absent') DEFAULT 'absent',
    PRIMARY KEY (id),
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);
