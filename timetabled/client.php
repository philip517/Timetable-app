<?php

session_start();

include 'db_connect.php';
require "auth.php";
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            margin: 20px;
        }
        .back-btn {
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .back-btn:hover {
            background-color: #0b7dda;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p>Manage Clients Here</p>
    </div>

    <div class="content">
        <div class="btn-container">
            <a href="addClient.php" class="btn">Add New Client</a>
            <a href="view_clients.php" class="btn">View All Clients</a>
        </div>
         <!-- Back button -->
         <p align=center><a href="index.php"  class="back-btn">Back to Clients</a></p>
    </div>
</body>
</html>
