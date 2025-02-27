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
    <link rel="stylesheet" href="FarmerLogin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div id="farmer-dashboard" class="farmer-tab-container">
        <h1>Farmer's Dashboard</h1>
        <h2>Welcome, <?php echo htmlspecialchars($fname); ?>!</h2>

        <div class="card-container">
            <!-- Profile Card -->
            <div class="card">
                <i class="fas fa-user icon"></i>
                <h3>Farmer Profile</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($fname) . " ". htmlspecialchars($lname); ?></p>
                <p><strong>Phone No:</strong> <?php echo htmlspecialchars($phone_no); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
                <p><strong>Sex:</strong> <?php echo htmlspecialchars($sex); ?></p>
            </div>

            <!-- Land Details Card -->
            <div class="card">
                <i class="fas fa-tractor icon"></i>
                <h3>Land Details</h3>
                <?php if (empty($land_details)): ?>
                    <p>No land records found.</p>
                <?php else: ?>
                    <?php foreach ($land_details as $land): ?>
                        <p><strong>Land ID:</strong> <?php echo htmlspecialchars($land['landID']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($land['landlocation']); ?></p>
                        <p><strong>Soil Type:</strong> <?php echo htmlspecialchars($land['soiltype']); ?></p>
                        <hr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Request Status Card -->
            <div class="card">
                <i class="fas fa-check-circle icon"></i>
                <h2>Fertilizer Request History</h2>
                
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
            </div>
        </div>

        <a href="FarmerLogin.php" class="logout-btn">Logout</a> <!-- Logout Button -->
    </div>

    <!--<script src="farmer.js"></script>-->
</body>
</html>
