<?php
// Start the session
session_start();
require "auth.php";
// Include the database connection
require 'db_connect.php'; // This file should contain the database connection code

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password
    $role = $_POST['role'];
    $program_id = $_POST['program_id'];

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO Users (name, email, password, role, program_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $email);
    $stmt->bindParam(3, $password);
    $stmt->bindParam(4, $role);
    $stmt->bindParam(5, $program_id);

    // Execute and check if insertion was successful
    if ($stmt->execute()) {
        // Set a session variable to indicate success
        $_SESSION['message'] = "New user added successfully!";
        // Redirect to avoid re-posting the form on refresh
        header("Location: addClient.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->errorInfo()[2];
    }

    // Close connection
    $stmt = null;
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
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
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 800px; /* Set maximum width to match table width */
            margin-left: auto;
            margin-right: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%; /* Set the width to 100% to match the table width */
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            width: 100%; /* Make the submit button as wide as the inputs */
        }
        input[type="submit"]:hover {
            background-color: #45a049;
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
    </style>
</head>
<body>

    <div class="header">
        <h1>Add New User</h1>
    </div>

    <div class="content">
        <!-- Display success or error messages from session -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p style='color:green;'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']); // Clear the session message
        }
        if (isset($_SESSION['error'])) {
            echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']); // Clear the session error message
        }
        ?>

        <form action="addClient.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="student">Student</option>
                <option value="instructor">Instructor</option>
                <option value="admin">Admin</option>
            </select><br><br>

            <label for="program">Program:</label>
            <select id="program" name="program_id" required>
                <!-- This is dynamically generated from the Programs table -->
                <?php
                // Fetch program options from the database using PDO
                $stmt = $conn->prepare("SELECT program_id, program_name FROM programs");
                $stmt->execute();
                
                // Fetch each row as an associative array using fetch(PDO::FETCH_ASSOC)
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row['program_id']}'>{$row['program_name']}</option>";
                }
                ?>
            </select><br><br>

            <!-- Submit button -->
            <input type="submit" value="Add User">
        </form>

        <!-- Back button -->
        <a href="client.php" class="back-btn">Back to Clients</a>

    </div>

</body>
</html>
