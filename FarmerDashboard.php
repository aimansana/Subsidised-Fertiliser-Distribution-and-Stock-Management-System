<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: FarmerLogin.php");
    exit();
}

// Include the database connection
include 'connection.php';

// Get the logged-in farmer's ID
$username = $_SESSION['username'];

// Fetch farmer's ID from login table
$stmt = $conn->prepare("SELECT FarmerID FROM farmer_login WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($farmerID);
$stmt->fetch();
$stmt->close();

// Fetch farmer's personal details
$stmt = $conn->prepare("SELECT fname, lname, phone_no, age, sex FROM farmers WHERE farmerID = ?");
$stmt->bind_param("i", $farmerID);
$stmt->execute();
$stmt->bind_result($fname, $lname, $phone_no, $age, $sex);
$stmt->fetch();
$stmt->close();

// Fetch land details
$land_details = [];
$stmt = $conn->prepare("SELECT landID, landlocation, soiltype FROM farmer_land WHERE farmerID = ?");
$stmt->bind_param("i", $farmerID);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $land_details[] = $row;
}
$stmt->close();

// Fetch fertilizer request history
$requests = [];
$stmt = $conn->prepare("
SELECT fr.requestID, fr.landID, f.fertName, fr.quantityRequested, fr.requestDate, fr.status
FROM fertilizer_requests fr
JOIN fertilizers f ON fr.fertID = f.fertID
WHERE fr.farmerID = ?
ORDER BY fr.requestDate DESC
");
$stmt->bind_param("i", $farmerID);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}
$stmt->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="FarmerDashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="images/farmer1.jpg" alt="Profile Picture">
            <h2>Farmer Panel</h2>
        </div>
        <ul class="nav-links">
            <li><a href="#" onclick="showSection('profile')"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="#" onclick="showSection('land')"><i class="fas fa-map-marked-alt"></i> Land Details</a></li>
            <li><a href="#" onclick="showSection('requests')"><i class="fas fa-tasks"></i> Requests</a></li>
            <li><a href="#" onclick="showSection('apply-request')"><i class="fas fa-file-signature"></i> Apply Request</a></li>
            <li><a href="#" onclick="showSection('support')"><i class="fas fa-headset"></i> Support</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Dashboard</h1>
        </header>

        <!-- Profile Section -->
        <section id="profile" class="section active">
            <h2><i class="fas fa-user"></i> Profile</h2>
            <img src="images/farmer1.jpg" class="profile-pic" alt="Profile Picture">
            <p><i class="fas fa-user"></i>
            <strong>Name:</strong>  <?php echo htmlspecialchars($fname) . " ". htmlspecialchars($lname); ?></p>
            <p><i class="fas fa-phone"></i> 
            <strong>Phone:</strong> <?php echo htmlspecialchars($phone_no); ?></p>
            <p><i class="fas fa-venus-mars"></i> 
            <strong>Sex:</strong> <?php echo htmlspecialchars($sex); ?></p>
            <p><i class="fas fa-venus-mars"></i> 
            <strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
            <p><i class="fas fa-users"></i> 
            <strong>Category:</strong> General</p>
            <p><i class="fas fa-id-card"></i> 
            <strong>Aadhaar No:</strong> 1234-5678-9101</p>
        </section>
        

        <!-- Land Details Section -->
        <section id="land" class="section">
            <h2>Land Details</h2>
            <?php if (empty($land_details)): ?>
                    <p>No land records found.</p>
            <?php else: ?>
                
            <table>
                <tr>
                    <th>Land ID</th>
                    <th>Location</th>
                    <th>Size (Acres)</th>
                    <th>Soil Type</th>
                </tr>
                <?php foreach ($land_details as $land): ?>
                <tr>
                    <td><?php echo htmlspecialchars($land['landID']); ?></td>
                    <td><?php echo htmlspecialchars($land['landlocation']); ?></td>
                    <td>5</td>
                    <td><?php echo htmlspecialchars($land['soiltype']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </section>

        <!-- Requests Section -->
        <section id="requests" class="section">
            <h2>Fertilizer Requests</h2>
            <?php if (!empty($requests)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Land ID</th>
                            <th>Fertilizer Name</th>
                            <th>Quantity Requested</th>
                            <th>Request Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $req): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($req['requestID']); ?></td>
                                <td><?php echo htmlspecialchars($req['landID']); ?></td>
                                <td><?php echo htmlspecialchars($req['fertName']); ?></td>
                                <td><?php echo htmlspecialchars($req['quantityRequested']); ?></td>
                                <td><?php echo htmlspecialchars($req['requestDate']); ?></td>
                                <td><?php echo htmlspecialchars($req['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p class="no-data">No fertilizer requests found.</p>
                <?php endif; ?>
        </section>

        <!-- Apply Request Section -->
        <section id="apply-request" class="section">
            <h2>Apply for Fertilizer</h2>
            <form>
                <label>Land ID:</label>
                <input type="text" placeholder="Enter Land ID">
                <label>Fertilizer:</label>
                <input type="text" placeholder="Enter Fertilizer Name">
                <label>Quantity (kg):</label>
                <input type="number" placeholder="Enter Quantity">
                <button type="submit" class="btn">Apply</button>
            </form>
        </section>

        <!-- Support Section -->
        <section id="support" class="section">
            <h2>Support</h2>
            <p>Contact us at <strong>support@agriculture.gov</strong></p>
        </section>
    </div>

    <script src="FarmerDashboard.js"></script>
</body>
</html>
