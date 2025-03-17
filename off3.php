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
    <title>Senior Officer Dashboard</title>
    <link rel="stylesheet" href="off3.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    
    <div class="sidebar">
        <img src=".png" alt="Logo">
        <div class="sidebar-header">Dashboard</div>
        <ul>
            <li><a href="#" onclick="showSection('profileSection')">Profile</a></li>
            <li><a href="#" onclick="showSection('stockSection')">Stock Overview</a></li>
            <li><a href="#" onclick="showSection('districtSection')">District Management</a></li>
            <li><a href="#" onclick="showSection('requestSection')">Requests</a></li>
            <li><a href="#" onclick="showSection('officerSection')">Officer Management</a></li>
            <li><a href="logout.php" >Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h1>Senior Officer Dashboard</h1>
        </header>

        <!-- Profile Section -->
        <section class="card section" id="profileSection">
            <h2>Profile</h2>
            <div class="profile-container">
                <img src="profile.jpg" alt="Senior Officer" class="profile-image">
                <div class="profile-details">
                    <p><strong>Name:</strong> Jane Doe</p>
                    <p><strong>Role:</strong> Senior Officer</p>
                    <p><strong>Email:</strong> janedoe@example.com</p>
                    <p><strong>Phone:</strong> +1234567890</p>
                </div>
            </div>
        </section>

        <!-- Stock Overview Section -->
        <section class="card section" id="stockSection">
            <h2>Stock Overview</h2>
            <canvas id="stockChart"></canvas>
            <p>Total Stock Available: <span id="totalStock">1000</span> units</p>
        </section>

        <!-- District Management Section -->
        <section class="card section" id="districtSection">
            <h2>District Management</h2>
            <div class="district-table">
                <table>
                    <tr>
                        <th>District</th>
                        <th>Stock Available</th>
                        <th>Allocate Stock</th>
                    </tr>
                    <tr>
                        <td>District A</td>
                        <td id="districtAStock">200</td>
                        <td><button onclick="allocateStock('districtAStock')">Allocate 50</button></td>
                    </tr>
                    <tr>
                        <td>District B</td>
                        <td id="districtBStock">300</td>
                        <td><button onclick="allocateStock('districtBStock')">Allocate 50</button></td>
                    </tr>
                </table>
            </div>
        </section>

        <!-- Requests Section -->
        <section class="card section" id="requestSection">
            <h2>Request Approvals</h2>
            <button onclick="approveRequest()">Approve Request</button>
            <button onclick="rejectRequest()">Reject Request</button>
        </section>

        <!-- Officer Management Section -->
        <section class="card section" id="officerSection">
            <h2>Officer Management</h2>
            <p>Assign Junior Officer to District:</p>
            <select id="districtSelect">
                <option value="District A">District A</option>
                <option value="District B">District B</option>
            </select>
            <input type="text" id="officerName" placeholder="Officer Name">
            <button onclick="assignOfficer()">Assign</button>
            <div id="assignmentResult"></div>
        </section>
    </div>

    <script src="off3.js"></script>
</body>
</html>
