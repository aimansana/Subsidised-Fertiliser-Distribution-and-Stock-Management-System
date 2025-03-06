<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: FarmerLogin.php");
    exit();
}

// Include the database connection
include 'connection.php';

//include functions
include 'functions.php';

// Get the logged-in farmer's ID
$username = $_SESSION['username'];


// Fetch farmer's ID from login table
$farmer= fetchSingleRow($conn, "SELECT FarmerID FROM farmer_login WHERE username = ?", "s", $username);
$farmerID = $farmer['FarmerID']??null;

// Fetch farmer's personal details
$farmerDetails=fetchSingleRow($conn,"SELECT fname, lname, phone_no, age, sex FROM farmers WHERE farmerID = ?","i",$farmerID);

// Fetch land details
$land_details =fetchAllRows($conn," SELECT landID, landlocation, soiltype FROM farmer_land WHERE farmerID = ?", "i", $farmerID);

// Fetch fertilizer request history
$requests =fetchAllRows($conn," SELECT fr.requestID, fr.landID, f.fertName, fr.quantityRequested, fr.requestDate, fr.status
FROM fertilizer_requests fr
JOIN fertilizers f ON fr.fertID = f.fertID
WHERE fr.farmerID = ?
ORDER BY fr.requestDate DESC
", "i", $farmerID);

$off=fetchSingleRow($conn,"SELECT registeredBy FROM farmers WHERE farmerID=?","i",$farmerID);
$offID=$off['registeredBy']??null;


//apply request
if (isset($_POST['submitrequest'])) {
    $landID = $_POST['landID'];
    $fertID = $_POST['fertID'];
    $quantity = $_POST['quantity'];

    // Validate Land ID (Check if land belongs to the farmer)
    $landExists = fetchSingleRow($conn, "SELECT * FROM farmer_land WHERE farmerID = ? AND landID = ?", "ii", $farmerID, $landID);
    $landIDexists=$landExists['landID']??null;
    if($landIDexists==null){
        echo "<script>alert('Invalid Land ID');</script>";
    }

    // Validate Fertilizer ID (Check if fertilizer exists)
    $fertExists = fetchSingleRow($conn, "SELECT * FROM fertilizers WHERE fertID = ?", "i", $fertID);
    $fertIDexists=$fertExists['fertID']??null;
    if($fertIDexists==null){
        echo "<script>alert('Invalid Fertilizer ID');</script>";
    }

  
    if ($landExists && $fertExists) {
        // Insert the fertilizer request if both exist       
        $result = executeQuery($conn,"INSERT INTO fertilizer_requests (farmerID, landID, fertID, quantityRequested,registeredBy, requestDate) VALUES (?, ?, ?,?, ?, NOW())", "iiiii", $farmerID, $landID, $fertID, $quantity,$offID);
        if ($result) {
            header("Location: FarmerDashboard.php");
           exit();
        }
        else {
            echo "<script>alert('Error in submitting request. Try again!');</script>";
        }
    }
}

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
            <strong>Name:</strong>  <?php echo $farmerDetails['fname'] . " ". $farmerDetails['lname']; ?></p>
            <p><i class="fas fa-phone"></i> 
            <strong>Phone:</strong> <?php echo $farmerDetails['phone_no']; ?></p>
            <p><i class="fas fa-venus-mars"></i> 
            <strong>Sex:</strong> <?php echo $farmerDetails['sex']; ?></p>
            <p><i class="fas fa-venus-mars"></i> 
            <strong>Age:</strong> <?php echo $farmerDetails['age']; ?></p>
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
            <form method="POST" action="">
                <label>Land ID:</label>
                <input type="text" name="landID" required placeholder="Enter Land ID">
                <label>Fertilizer ID:</label>
                <input type="text" name="fertID" required placeholder="Enter Fertilizer Name">
                <label>Quantity (kg):</label>
                <input type="number" name="quantity" required placeholder="Enter Quantity">
                <button type="submit" name="submitrequest" class="btn">Apply</button>
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

