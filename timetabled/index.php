<?php

session_start();
include 'db_connect.php';

// Check if user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['username'];

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
         /* Basic Styling */
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            padding: 20px;
            background-color: #4CAF50;
            color: white;
        }
        .header h1 {
            font-size: 2rem; /* Default font size for larger screens */
        }
        .header p {
            font-size: 1rem; /* Default font size for smaller text */
        }
        .button-container {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        .button-container a {
            padding: 15px 30px;
            font-size: 18px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
            margin: 10px;
            flex: 1 1 calc(33.33% - 20px);
            max-width: 250px;
        }
        .button-container a:hover {
            background-color: #45a049;
        }
        .logout-container {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }
        .logout-btn {
            background-color: rgb(0, 255, 136);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }
        .logout-btn:hover {
            background-color: #d32f2f;
        }

        /* Media Queries for Responsiveness */
        /* Tablet Devices (max-width: 768px) */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.75rem; /* Smaller font size for header */
            }
            .header p {
                font-size: 0.9rem; /* Smaller text for description */
            }
            .button-container {
                justify-content: center; /* Center buttons on smaller screens */
            }
            .button-container a {
                flex: 1 1 calc(50% - 20px); /* Buttons take up 50% width */
                max-width: 200px;
                font-size: 16px; /* Slightly smaller text on medium screens */
            }
        }

        /* Mobile Devices (max-width: 480px) */
        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.5rem; /* Even smaller header for mobile */
            }
            .header p {
                font-size: 0.85rem; /* Even smaller text for mobile */
            }
            .button-container a {
                flex: 1 1 100%; /* Buttons take up 100% width on small screens */
                max-width: 100%;
                font-size: 14px; /* Smaller font size on small screens */
            }
            .logout-btn {
                padding: 8px 16px; /* Smaller padding for mobile logout button */
                font-size: 14px; /* Smaller font size for logout button */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome, Admin <?php echo htmlspecialchars($adminName); ?>!</h1>
            <p>Choose an action to manage the school timetable system.</p>
        </div>

        <div class="button-container">
            <a href="client.php">Go to Client</a>
            <a href="insertCourse.php">Insert Course</a>
            <a href="viewCourses.php">View Course</a>
        </div>

        <!-- Centered Logout Button -->
        <div class="logout-container">
            <form method="POST" action="">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
