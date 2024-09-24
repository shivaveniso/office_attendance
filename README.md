# Office Attendance Application

This is a web-based office attendance tracking application. It allows employees to log in, mark their attendance, and view their attendance records.

## Prerequisites

Before you begin, ensure you have met the following requirements:

- **Docker**: Make sure Docker is installed on your local machine. You can download it from [here](https://www.docker.com/get-started).
- **Docker Compose**: Docker Compose is also required. It typically comes with Docker, but you can check its installation by running `docker-compose --version`.

## Getting Started.

Follow these steps to set up and run the application locally:

### 1. Clone the Repository

Clone this repository to your local machine using the following command:

```bash
git clone https://github.com/shivaveniso/office_attendance.git
```
### 2. Change to the office_attedance dir 
```bash
cd office_attendance
```
### 3. Set Up Environment Variables

The application requires environment variables to run. Use the provided env-template file to create a .env file with your specific configurations.
```bash
cp env-template .env
```
### 4. Build and Run the Application
Use Docker Compose to build and run the application:
```bash
docker-compose up --build
```
### 5. Access the Application

Once the application is up and running, you can access it in your web browser at:
```bash
http://localhost:8080
```
