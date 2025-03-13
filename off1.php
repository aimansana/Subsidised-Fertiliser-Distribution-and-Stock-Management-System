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

$msg1 = $msg2 = $msg3 = "";
$land_details = $requests = [];
$farmerID = $firstName = $lastName = $age = $telNo = $sex = $addy = "";
$showUpdateForm = false;

// Fetch all farmers under the officer
$farmers = fetchAllRows($conn, "SELECT * FROM farmers WHERE registeredBy = ?", "i", $offID);

// Fetch all land under the officer
$lands = fetchAllRows($conn, "SELECT * FROM farmer_land WHERE registeredBy = ? ORDER BY landID", "i", $offID);

// Fetch all fertilizer requests under the officer
$fertilizer_requests = fetchAllRows($conn, "SELECT fr.farmerID, fr.requestID, fr.landID, f.fertName, fr.quantityRequested, fr.requestDate, fr.status FROM fertilizer_requests fr JOIN fertilizers f ON fr.fertID = f.fertID WHERE fr.registeredBy = ? ORDER BY fr.requestDate DESC", "i", $offID);

// Display total counts
$counts = fetchSingleRow($conn, "SELECT (SELECT COUNT(*) FROM farmers WHERE registeredBy = ?) AS farmer_count, (SELECT COUNT(*) FROM farmer_land WHERE registeredBy = ?) AS land_count, (SELECT COUNT(*) FROM fertilizer_requests WHERE registeredBy = ?) AS request_count", "iii", $offID, $offID, $offID);
$farmer_count = $counts['farmer_count'];
$land_count = $counts['land_count'];
$request_count = $counts['request_count'];

// Search Farmer by ID
if (isset($_POST['btnSearchID'])) {
    $farmerID = $_POST['txtFarmerID'];
    $farmer = fetchSingleRow($conn, "SELECT * FROM farmers WHERE FarmerID = ? AND registeredBy = ?", "ii", $farmerID, $offID);

    if ($farmer) {
        $land_details = fetchAllRows($conn, "SELECT landID, landlocation, soiltype FROM farmer_land WHERE farmerID = ?", "i", $farmerID);
        $requests = fetchAllRows($conn, "SELECT fr.requestID, fr.landID, f.fertName, fr.quantityRequested, fr.requestDate, fr.status FROM fertilizer_requests fr JOIN fertilizers f ON fr.fertID = f.fertID WHERE fr.farmerID = ? ORDER BY fr.requestDate DESC", "i", $farmerID);
    } else {
        $msg1 = "No such Farmer ID exists.";
    }
}

// Search Land by ID
if (isset($_POST['btnSearchLand'])) {
    $landID = $_POST['txtLandID'];
    $land = fetchSingleRow($conn, "SELECT * FROM farmer_land WHERE landID = ? AND registeredBy = ?", "ii", $landID, $offID);
    $msg1 = $land ? "" : "No such Land ID exists.";
}

// Search Fertilizer Request by ID
if (isset($_POST['btnSearchRequest'])) {
    $requestID = $_POST['txtRequestID'];
    $request = fetchSingleRow($conn, "SELECT fr.requestID, fr.landID, f.fertName, fr.quantityRequested, fr.requestDate, fr.status FROM fertilizer_requests fr JOIN fertilizers f ON fr.fertID = f.fertID WHERE fr.requestID = ? AND fr.registeredBy = ?", "ii", $requestID, $offID);
    $msg1 = $request ? "" : "No such Request ID exists.";
}

// Update Farmer Data
if (isset($_POST['btn_update_farmer'])) {
    $result = executeQuery($conn, "UPDATE farmers SET fname = ?, lname = ?, age = ?, sex = ?, phone_no = ?, addy = ? WHERE FarmerID = ?", "ssisssi", $_POST['txtFirstName'], $_POST['txtLastName'], $_POST['txtage'], $_POST['txtsex'], $_POST['txtTelNo'], $_POST['txtaddy'], $_POST['txtFarmerID']);
    header("Location: off1.php");
    exit();
}

// Update Land Details
if (isset($_POST['btn_update_land'])) {
    $result = executeQuery($conn, "UPDATE farmer_land SET soiltype = ?, farmerID = ? WHERE landID = ?", "sii", $_POST['txtSoilType'], $_POST['txtFarmerID'], $_POST['txtLandID']);
    header("Location: off1.php");
    exit();
}

// Add New Farmer
if (isset($_POST['btn_add'])) {
    $hashedPassword = password_hash($_POST['txtNewPass'], PASSWORD_DEFAULT);
    executeQuery($conn, "INSERT INTO farmers (fname, lname, age, sex, phone_no, addy, registeredBy) VALUES (?, ?, ?, ?, ?, ?, ?)", "ssisssi", $_POST['txtNewFirstName'], $_POST['txtNewLastName'], $_POST['txtNewAge'], $_POST['txtNewSex'], $_POST['txtNewTelNo'], $_POST['txtNewAddy'], $offID);
    header("Location: off1.php");
    exit();
}

// Add New Land
if (isset($_POST['btn_add_land'])) {
    executeQuery($conn, "INSERT INTO farmer_land (farmerID, landlocation, landsize, soiltype, registeredBy) VALUES (?, ?, ?, ?, ?)", "isdsi", $_POST['txtNewFarmerID'], $_POST['txtNewLandLocation'], $_POST['txtNewLandSize'], $_POST['txtNewSoilType'], $offID);
    header("Location: off1.php");
    exit();
}

// Add New Fertilizer Request
if (isset($_POST['btn_add_request'])) {
    executeQuery($conn, "INSERT INTO fertilizer_requests (landID, farmerID, fertID, quantityRequested, registeredBy) VALUES (?, ?, ?, ?, ?)", "iiiii", $_POST['txtNewLandID'], $_POST['txtNewFarmerID'], $_POST['txtNewFertID'], $_POST['txtNewQuantityRequested'], $offID);
    header("Location: off1.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Officer Dashboard</title>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS (Your stylesheet) -->
    <link rel="stylesheet" href="off1.css"></head>
<body>

<div class="sidebar">
    <div class="logo">
        <img src="images/firm_logo1.png" alt="Logo">
        <h2><i class="fa-solid fa-leaf"></i> Field Officer</h2>
    </div>
    <ul>
        <li><a href="#" data-target="profile-section"><i class="fa-solid fa-user"></i> Profile</a></li>
        <li><a href="#" data-target="manage-farmers-section"><i class="fa-solid fa-tractor"></i> Farmers</a></li>
        <li><a href="#" data-target="manage-land-section"><i class="fa-solid fa-tree"></i> Land</a></li>
        <li><a href="#" data-target="request-section"><i class="fa-solid fa-file-alt"></i> Requests</a></li>
        <li><a href="#" data-target="analytics-section"><i class="fa-solid fa-chart-bar"></i> Analytics</a></li>
        <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
    </ul>
    <!-- Sections -->
<section id="profile-section">Profile Content</section>
<section id="manage-farmers-section" style="display: none;">Farmers Content</section>
<section id="manage-land-section" style="display: none;">Land Content</section>
<section id="request-section" style="display: none;">Requests Content</section>
<section id="analytics-section" style="display: none;">Analytics Content</section>    
</div>

<div class="main-content">
    <header>
        <h1>Dashboard</h1>
    </header>

    <section id="profile-section" class="section active">
        <h3>Your Profile</h3>
        <div class="profile-container">
            <img src="images/farmer1.jpg" alt="Profile Picture" class="profile-pic">
            <table class="profile-table">
                <tr><th>ID:</th><td><?php echo htmlspecialchars($officerDetails['offID']) ?></td></tr>
                <tr><th>Name:</th><td><?php echo htmlspecialchars($officerDetails['Fname']) . " " . htmlspecialchars($officerDetails['Lname']); ?></td></tr>
                <tr><th>Designation:</th><td>Field Officer</td></tr>
                <tr><th>Email:</th><td><?php echo htmlspecialchars($officerDetails['email']); ?> </td></tr>
                <tr><th>Phone:</th><td><?php echo htmlspecialchars($officerDetails['phone_no']); ?> </td></tr>
                <tr><th>Age:</th><td><?php echo htmlspecialchars($officerDetails['age']); ?></td></tr>
                <tr><th>Sex:</th><td><?php echo htmlspecialchars($officerDetails['sex']); ?></td></tr>
            </table>
        </div>
    </section>

    <section id="manage-farmers-section" class="section">
        <h2>Manage Farmers</h2>
        <form method="post">
        <label for="txtFarmerID">Enter Farmer ID:</label>
        <input type="text" id="txtFarmerID" name="txtFarmerID" required>
        <button type="submit" name="btnSearchID" id="search-btn">Search</button>
    </form>
    <?php if (!empty($msg1)) echo "<p>$msg1</p>"; ?>

<?php if (!empty($farmer)) : ?>
    <h3>Farmer Details</h3>
    <p><strong>Farmer ID:</strong> <?php echo htmlspecialchars($farmer['farmerID']); ?></p>
    <p><strong>First Name:</strong> <?php echo htmlspecialchars($farmer['FName']); ?></p>
    <p><strong>Last Name:</strong> <?php echo htmlspecialchars($farmer['LName']); ?></p>
    <p><strong>Age:</strong> <?php echo htmlspecialchars($farmer['age']); ?></p>
    <p><strong>Sex:</strong> <?php echo htmlspecialchars($farmer['sex']); ?></p>
    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($farmer['phone_no']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($farmer['addy']); ?></p>
    <p><strong>category:</strong> <?php echo htmlspecialchars($farmer['category']); ?></p>
    <p><strong>adhar no.:</strong> <?php echo htmlspecialchars($farmer['adhar']); ?></p>

    <h2>Update Farmer Details</h2>
    <form method="post">
        <input type="hidden" name="txtFarmerID" value="<?php echo htmlspecialchars($farmer['farmerID']); ?>">
        <label>First Name: </label>
            <input type="text" name="txtFirstName" value="<?php echo htmlspecialchars($farmer['FName']); ?>" required><br>
        <label>Last Name: </label>
            <input type="text" name="txtLastName" value="<?php echo htmlspecialchars($farmer['LName']); ?>" required><br>
        <label>Age: </label>
            <input type="number" name="txtage" value="<?php echo htmlspecialchars($farmer['age']); ?>" required><br>
        <label>Sex:</label>
            <select name="txtsex" required>
                <option value="Male" <?php echo ($farmer['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($farmer['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo ($farmer['sex'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select><br>
        <label>Phone Number: </label>
            <input type="tel" name="txtTelNo" value="<?php echo htmlspecialchars($farmer['phone_no']); ?>" required><br>
        <label>Address: </label>
            <input type="text" name="txtaddy" value="<?php echo htmlspecialchars($farmer['addy']); ?>" required><br>
        <label>category: </label>
            <input type="text" name="txtcategory" value="<?php echo htmlspecialchars($farmer['category']); ?>" required><br>
        <label>adhar no.: </label>
            <input type="text" name="txtadhar" value="<?php echo htmlspecialchars($farmer['adhar']); ?>" required><br>
        <button type="submit" name="btn_update_farmer">Save</button>
    </form>

<?php endif; ?>


    <h2>Add New Farmer</h2>
    <form method="post">
        <label>First Name: </label>
            <input type="text" name="txtNewFirstName" required>
            <br>
        <label>Last Name: </label>
            <input type="text" name="txtNewLastName" required>
            <br>

        <label>Sex:</label>
            <select name="txtNewSex" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <br>

        <label>Age: </label>
            <input type="number" name="txtNewAge" required>
            <br>

        <label>Phone Number: </label>
            <input type="tel" name="txtNewTelNo" required>
            <br>

        <label>Address: </label>
            <input type="text" name="txtNewAddy" required>
            <br>

        <label>category: </label>
            <input type="text" name="txtNewCategory" required>
            <br>

        <label>adhar no.: </label>
            <input type="text" name="txtNewAdhar" required>
            <br>

        <label>Username: </label>
            <input type="text" name="txtNewUser" required>
            <br>

        <label>Password: </label>
            <input type="password" name="txtNewPass" required>
            <br>

        <button type="submit" name="btn_add" id="add-farmer-btn">Add Farmer</button>
    </form>

<h3>All Farmers</h3>
<table>
    <tr>
        <th>Farmer ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Age</th>
        <th>sex</th>
        <th>Phone Number</th>
        <th>Address</th>
        <th>category</th>
        <th>adhar no.</th>
    </tr>
    <?php foreach ($farmers as $farmer) : ?>
        <tr>
            <td><?php echo $farmer['farmerID']; ?></td>
            <td><?php echo $farmer['FName']; ?></td>
            <td><?php echo $farmer['LName']; ?></td>
            <td><?php echo $farmer['age']; ?></td>
            <td><?php echo $farmer['sex']; ?></td>
            <td><?php echo $farmer['phone_no']; ?></td>
            <td><?php echo $farmer['addy']; ?></td>
            <td><?php echo $farmer['category']; ?></td>
            <td><?php echo $farmer['adhar']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</section>

    <section id="manage-land-section" class="section">
        <h2>Manage Land</h2>
        <h2>Search Land by ID</h2>
    <form method="post">
        <label for="txtLandID">Enter Land ID:</label>
        <input type="text" id="txtLandID" name="txtLandID" required>
        <button type="submit" name="btnSearchLand" id="search-land-btn">Search</button>
    </form>
    <?php if (!empty($msg1)) echo "<p>$msg1</p>"; ?>

<?php if (!empty($land)) : ?>
    <h3>Land Details</h3>
    <p><strong>Land ID:</strong> <?php echo htmlspecialchars($land['landID']); ?></p>
    <p><strong>Farmer ID:</strong> <?php echo htmlspecialchars($land['farmerID']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($land['landLocation']); ?></p>
    <p><strong>Size:</strong> <?php echo htmlspecialchars($land['landSize']); ?></p>
    <p><strong>Soil Type:</strong> <?php echo htmlspecialchars($land['soilType']); ?></p>

    <h2>Update Land Details</h2>
    <form method="post">
        <input type="hidden" name="txtLandID" value="<?php echo htmlspecialchars($land['landID']); ?>">
        <label>Soil Type: </label>
            <input type="text" name="txtSoilType" value="<?php echo htmlspecialchars($land['soilType']); ?>" required><br>
        <label>Farmer ID:</label>
            <input type="number" name="txtFarmerID" value="<?php echo htmlspecialchars($land['farmerID']); ?>" required><br>
        <button type="submit" name="btn_update_land">Save</button>
    </form>
    <?php endif; ?>

    <h2>Add New Land</h2>
    <form method="post">
        <label>Farmer ID: </label>
            <input type="number" name="txtNewFarmerID" required><br>
        <label>Location: </label>
            <input type="text" name="txtNewLandLocation" required><br>
        <label>Size: </label>
            <input type="number" name="txtNewLandSize" required><br>
        <label>Soil Type: </label>
            <input type="text" name="txtNewSoilType" required><br>
        <button type="submit" name="btn_add_land" id="add-land-btn" >Add Land</button>
    </form>


    <h3> All Lands</h3>
        <table>
    <tr>
        <th>Land ID</th>
        <th>Farmer ID</th>
        <th>Location</th>
        <th>Size</th>
        <th>Soil Type</th>
    </tr>
    <?php foreach ($lands as $land) : ?>
        <tr>
            <td><?php echo $land['landID']; ?></td>
            <td><?php echo $land['farmerID']; ?></td>
            <td><?php echo $land['landLocation']; ?></td>
            <td><?php echo $land['landSize']; ?></td>
            <td><?php echo $land['soilType']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
        
    </section>
    <section id="request-section" class="section">
        <h2>Manage Requests</h2>
        <h2>Search Fertilizer Request by ID</h2>
    <form method="post">
        <label for="txtRequestID">Enter Request ID:</label>
        <input type="text" id="txtRequestID" name="txtRequestID" required>
        <button type="submit" name="btnSearchRequest" id="search-request-btn">Search</button>
    </form>
    <?php if (!empty($msg1)) echo "<p>$msg1</p>"; ?>
    <?php if (!empty($request)) : ?>

    <h3>Fertilizer Request Details</h3>
    <p><strong>Request ID:</strong> <?php echo htmlspecialchars($request['requestID']); ?></p>
    <p><strong>Land ID:</strong> <?php echo htmlspecialchars($request['landID']); ?></p>
    <p><strong>Fertilizer Name:</strong> <?php echo htmlspecialchars($request['fertName']); ?></p>
    <p><strong>Quantity Requested:</strong> <?php echo htmlspecialchars($request['quantityRequested']); ?></p>
    <p><strong>Request Date:</strong> <?php echo htmlspecialchars($request['requestDate']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($request['status']); ?></p>

    <form method="post">
        <input type="hidden" name="txtRequestID" value="<?php echo htmlspecialchars($request['requestID']); ?>">
        <label>Status:</label>
            <select name="txtStatus" required>
                <option value="Pending" <?php echo ($request['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Approved" <?php echo ($request['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                <option value="Rejected" <?php echo ($request['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
            </select><br>
        <button type="submit" name="btn_update_request">Save</button>
    </form>

<?php endif; ?>

        <h2>Add New Fertilizer Request</h2>
    <form method="post">
        <label>Land ID:</label>
             <input type="number" name="txtNewLandID" required><br>
        <label>Farmer ID:</label>
             <input type="number" name="txtNewFarmerID" required><br>
        <label>Fertilizer ID: </label>
            <input type="number" name="txtNewFertID" required><br>
        <label>Quantity Requested: </label>
            <input type="number" name="txtNewQuantityRequested" required><br>
        <button type="submit" name="btn_add_request" id="add-request-btn">Add Request</button>
    </form>
        <h3>All Fertilizer Requests</h3>
<table border="1">
    <tr>
        <th>Request ID</th>
        <th>Land ID</th>
        <th>Fertilizer Name</th>
        <th>Quantity Requested</th>
        <th>Request Date</th>
        <th>Status</th>
    </tr>
    <?php foreach ($fertilizer_requests as $request) : ?>
        <tr>
            <td><?php echo $request['requestID']; ?></td>
            <td><?php echo $request['landID']; ?></td>
            <td><?php echo $request['fertName']; ?></td>
            <td><?php echo $request['quantityRequested']; ?></td>
            <td><?php echo $request['requestDate']; ?></td>
            <td><?php echo $request['status']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
    </section>


    <section id="analytics-section" class="section">

        <h2>Analytics</h2>
        <!-- Chart Container -->
<div style="width: 80%; max-width: 600px; margin: auto;">
<canvas id="analytics-chart" width="600" height="400"></canvas>
</div>

<!-- Pass PHP data to JavaScript -->
<script>
    const farmerCount = <?php echo $farmer_count; ?>;
    const landCount = <?php echo $land_count; ?>;
    const requestCount = <?php echo $request_count; ?>;
</script>
    </section>

</div>
<script src="off1.js"> </script>

</body>
</html>
