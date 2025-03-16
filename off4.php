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
    <title>Subsidy Payment Officer Dashboard</title>
    <link rel="stylesheet" href="off4.css">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="header">
                <h2><i class="fas fa-credit-card"></i> Subsidy Payment Officer</h2>
            </div>
            <ul>
                <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-hand-holding-usd"></i> Pending Payments</a></li>
                <li><a href="#"><i class="fas fa-user-check"></i> Farmer Details</a></li>
                <li><a href="#"><i class="fas fa-history"></i> Previous Payments</a></li>
                <li><a href="#"><i class="fas fa-cogs"></i> Settings</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <h1>Dashboard</h1>

            <div class="content">
                <!-- Pending Payments Section -->
                <section class="pending-payments">
                    <h2><i class="fas fa-spinner"></i> View Pending Payments</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Farmer Name</th>
                                <th>Amount</th>
                                <th>Reason for Delay</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Farmer 1</td>
                                <td>$500</td>
                                <td>Verification Pending</td>
                                <td><button>Process</button></td>
                            </tr>
                            <tr>
                                <td>Farmer 2</td>
                                <td>$300</td>
                                <td>Pending Approval</td>
                                <td><button>Process</button></td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <!-- Farmer Details Section -->
                <section class="farmer-details">
                    <h2><i class="fas fa-user"></i> View Farmer Details</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Farmer Name</th>
                                <th>Farm Location</th>
                                <th>Eligibility Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Farmer 1</td>
                                <td>Location 1</td>
                                <td>Eligible</td>
                            </tr>
                            <tr>
                                <td>Farmer 2</td>
                                <td>Location 2</td>
                                <td>Not Eligible</td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <!-- Previous Completed Payments Section -->
                <section class="previous-payments">
                    <h2><i class="fas fa-check-circle"></i> View Previous Completed Payments</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Farmer Name</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Farmer 1</td>
                                <td>$500</td>
                                <td>2025-01-15</td>
                            </tr>
                            <tr>
                                <td>Farmer 2</td>
                                <td>$300</td>
                                <td>2025-01-20</td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>

    <script src="off4.js"></script>
</body>
</html>
