<?php
session_start();
include 'db_connect.php';
require "auth.php";

// Check if user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch programs and semesters for dropdowns using PDO
$programs_query = "SELECT * FROM programs";
$stmt = $conn->prepare($programs_query);
$stmt->execute();
$programs_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$semesters_query = "SELECT * FROM semesters";
$stmt = $conn->prepare($semesters_query);
$stmt->execute();
$semesters_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$instructors_query = "SELECT * FROM users WHERE role = 'instructor'";
$stmt = $conn->prepare($instructors_query);
$stmt->execute();
$instructors_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $courseName = $_POST['course_name'];
    $credits = $_POST['credits'];
    $room = $_POST['room'];
    $programId = $_POST['program_id'];
    $semesterId = $_POST['semester_id'];
    $instructorId = $_POST['instructor_id'];

    // Sanitize inputs to prevent SQL injection
    $courseName = htmlspecialchars($courseName);
    $credits = htmlspecialchars($credits);
    $room = htmlspecialchars($room);
    $programId = htmlspecialchars($programId);
    $semesterId = htmlspecialchars($semesterId);
    $instructorId = htmlspecialchars($instructorId);

    // Insert the course into the database using PDO
    $insert_query = "INSERT INTO courses (course_name, credits, room, semester_id, instructor_id) 
                     VALUES (:course_name, :credits, :room, :semester_id, :instructor_id)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bindParam(':course_name', $courseName);
    $stmt->bindParam(':credits', $credits);
    $stmt->bindParam(':room', $room);
    $stmt->bindParam(':semester_id', $semesterId);
    $stmt->bindParam(':instructor_id', $instructorId);

    if ($stmt->execute()) {
        $message = "Course added successfully!";
    } else {
        $message = "Error: " . $stmt->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Course</title>
    <style>
        /* Basic Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 800px;
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
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-size: 1rem;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-group button {
            padding: 12px 20px;
            font-size: 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            font-size: 1.1rem;
            margin-top: 20px;
            color: green;
        }
        .error-message {
            text-align: center;
            font-size: 1.1rem;
            margin-top: 20px;
            color: red;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Insert a New Course</h1>
        </div>

        <!-- Display success or error message -->
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name" required>
            </div>

            <div class="form-group">
                <label for="credits">Credits:</label>
                <input type="number" id="credits" name="credits" required>
            </div>

            <div class="form-group">
                <label for="room">Room:</label>
                <input type="text" id="room" name="room" required>
            </div>

            <div class="form-group">
                <label for="program_id">Program:</label>
                <select id="program_id" name="program_id" required>
                    <option value="">Select Program</option>
                    <?php foreach ($programs_result as $program): ?>
                        <option value="<?php echo $program['program_id']; ?>"><?php echo $program['program_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="semester_id">Semester:</label>
                <select id="semester_id" name="semester_id" required>
                    <option value="">Select Semester</option>
                    <?php foreach ($semesters_result as $semester): ?>
                        <option value="<?php echo $semester['semester_id']; ?>"><?php echo $semester['semester_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="instructor_id">Instructor:</label>
                <select id="instructor_id" name="instructor_id" required>
                    <option value="">Select Instructor</option>
                    <?php foreach ($instructors_result as $instructor): ?>
                        <option value="<?php echo $instructor['user_id']; ?>"><?php echo $instructor['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit">Insert Course</button>
            </div>
        </form>
    </div>

</body>
</html>
