<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: OfficerLogin.php");
    exit();
}
//best off1
include 'connection.php';
include 'functions.php';

$username = $_SESSION['username'];

// Fetch officer's ID
$officer = fetchSingleRow($conn, "SELECT offID FROM officer_login WHERE username = ?", "s", $username);
$offID = $officer['offID'];

// Fetch officer's details
$officerDetails = fetchSingleRow($conn, "SELECT * FROM officers WHERE offID = ?", "i", $offID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quality Control Officer Dashboard</title>
    <link rel="stylesheet" href="off5.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="header">
                <h2><i class="fas fa-shield-alt"></i> Quality Control Officer</h2>
            </div>
            <ul>
                <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-users"></i> Field Officers</a></li>
                <li><a href="#"><i class="fas fa-clipboard-list"></i> Requests</a></li>
                <li><a href="#"><i class="fas fa-chart-line"></i> Analytics</a></li>
                <li><a href="#"><i class="fas fa-cogs"></i> Settings</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>

            <div class="content">
                <!-- Quality Check Form -->
                <section class="quality-check-form">
                    <h2><i class="fas fa-upload"></i> Upload Quality Check Form/Survey</h2>
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <label for="survey-name"><i class="fas fa-pencil-alt"></i> Survey Name:</label>
                        <input type="text" id="survey-name" name="survey-name" required>

                        <label for="survey-file"><i class="fas fa-file-upload"></i> Upload Survey Form:</label>
                        <input type="file" id="survey-file" name="survey-file" required>

                        <button type="submit" class="upload-btn"><i class="fas fa-cloud-upload-alt"></i> Upload Survey</button>
                    </form>
                </section>

                <!-- Previous Surveys Section -->
                <section class="previous-surveys">
                    <h2><i class="fas fa-history"></i> Previous Surveys</h2>
                    <table>
                        <thead>
                            <tr>
                                <th><i class="fas fa-book"></i> Survey Name</th>
                                <th><i class="fas fa-calendar-day"></i> Date</th>
                                <th><i class="fas fa-cogs"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Survey 1</td>
                                <td>2025-01-15</td>
                                <td><button><i class="fas fa-eye"></i> View</button></td>
                            </tr>
                            <tr>
                                <td>Survey 2</td>
                                <td>2025-01-20</td>
                                <td><button><i class="fas fa-eye"></i> View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>

    <script src="off5.js"></script>
</body>
</html>
