<?php
session_start();
include 'db_connect.php';
require "auth.php";

// Check if user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch all courses from the database
$courses_query = "
    SELECT c.course_id, c.course_name,  c.room, c.semester_id, c.instructor_id, 
           s.semester_name, p.program_name, u.name AS instructor_name
    FROM courses c
    LEFT JOIN semesters s ON c.semester_id = s.semester_id
    LEFT JOIN programs p ON s.program_id = p.program_id
    LEFT JOIN users u ON c.instructor_id = u.user_id
";
$stmt = $conn->prepare($courses_query);
$stmt->execute();
$courses_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Courses</title>
    <style>
        /* Basic Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 2rem;
            color: #333;
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
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .message {
            text-align: center;
            font-size: 1.1rem;
            margin-top: 20px;
            color: green;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>All Courses</h1>
        </div>

        <!-- Display the courses in a table -->
        <?php if (count($courses_result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                       
                        <th>Room</th>
                        <th>Semester</th>
                        <th>Program</th>
                        <th>Instructor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses_result as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                           
                            <td><?php echo htmlspecialchars($course['room']); ?></td>
                            <td><?php echo htmlspecialchars($course['semester_name']); ?></td>
                            <td><?php echo htmlspecialchars($course['program_name']); ?></td>
                            <td><?php echo htmlspecialchars($course['instructor_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="message">No courses found in the database.</div>
        <?php endif; ?>

        <!-- Back Button -->
        <a href="index.php" class="back-button">Back to Dashboard</a>
    </div>

</body>
</html>
