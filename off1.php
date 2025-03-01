<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: OfficerLogin.php");
    exit();
}

include 'connection.php';

$username = $_SESSION['username'];

// Fetch officer's ID
$stmt = $conn->prepare("SELECT offID FROM officer_login WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($offID);
$stmt->fetch();
$stmt->close();

// Fetch officer's details
$stmt = $conn->prepare("SELECT Fname, Lname, phone_no, email, age, sex FROM officers WHERE offID = ?");
$stmt->bind_param("i", $offID);
$stmt->execute();
$stmt->bind_result($oFname, $oLname, $ophone_no, $oemail, $oage, $osex);
$stmt->fetch();
$stmt->close();

$msg1 = $msg2 = $msg3 = "";
$land_details = $requests = [];
$farmerID = $firstName = $lastName = $age = $telNo = $sex = $addy = "";
$showUpdateForm = false; // Track if update form should be shown


//fetch all farmers under the officer
$query = "SELECT * FROM farmers WHERE registeredBy = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $offID);
$stmt->execute();
$result = $stmt->get_result();
$farmers = [];
while ($row = $result->fetch_assoc()) {
    $farmers[] = $row;
}
$stmt->close();

//fetch all land under the officer
$query = "SELECT * FROM farmer_land WHERE registeredBy = ? order by landID";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $offID);
$stmt->execute();
$result = $stmt->get_result();
$lands = [];
while ($row = $result->fetch_assoc()) {
    $lands[] = $row;
}
$stmt->close();

//fetch all fertilizer requests under the officer
$query = "SELECT fr.farmerID, fr.requestID, fr.landID, f.fertName, fr.quantityRequested, fr.requestDate, fr.status
FROM fertilizer_requests fr
JOIN fertilizers f ON fr.fertID = f.fertID
WHERE fr.registeredBy = ?
ORDER BY fr.requestDate DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $offID);
$stmt->execute();
$result = $stmt->get_result();
$fertilizer_requests = [];
while ($row = $result->fetch_assoc()) {
    $fertilizer_requests[] = $row;
}
$stmt->close();

// Display total counts
$query = "SELECT 
    (SELECT COUNT(*) FROM farmers WHERE registeredBy = ?) AS farmer_count,
    (SELECT COUNT(*) FROM farmer_land WHERE registeredBy = ?) AS land_count,
    (SELECT COUNT(*) FROM fertilizer_requests WHERE registeredBy = ?) AS request_count";

$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $offID, $offID, $offID);
$stmt->execute();
$stmt->bind_result($farmer_count, $land_count, $request_count);
$stmt->fetch();
$stmt->close();

// Step 1: Search FarmerID
if (isset($_POST['btnSearchID'])) {
    $farmerID = $_POST['txtFarmerID'];

    $query = "SELECT * FROM farmers WHERE FarmerID = ? AND registeredBy= ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $farmerID, $offID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch farmer's details
    if ($record = $result->fetch_assoc()) {
        $firstName = $record['FName'];
        $lastName = $record['LName'];
        $age = $record['age'];
        $sex = $record['sex'];
        $telNo = $record['phone_no'];
        $addy = $record['addy'];

        // Fetch farmer's land details
        $query1="SELECT landID, landlocation, soiltype FROM farmer_land WHERE farmerID = ?";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bind_param("i", $farmerID);
        $stmt1->execute();
        $result = $stmt1->get_result();
        while ($row = $result->fetch_assoc()) {
            $land_details[] = $row;
        }
        $stmt1->close();

        // Fetch farmer's fertilizer request history
        $query2="SELECT fr.requestID, fr.landID, f.fertName, fr.quantityRequested, fr.requestDate, fr.status
        FROM fertilizer_requests fr
        JOIN fertilizers f ON fr.fertID = f.fertID
        WHERE fr.farmerID = ?
        ORDER BY fr.requestDate DESC";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("i", $farmerID);
        $stmt2->execute();
        $result = $stmt2->get_result();
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
        $stmt2->close();

    } else {
        $msg1 = "No such Farmer ID exists.";
    }
    $stmt->close();

}

//search land using landID
if (isset($_POST['btnSearchLand'])) {
    $landID = $_POST['txtLandID'];

    $query = "SELECT * FROM farmer_land WHERE landID = ? AND registeredBy= ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $landID, $offID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch land details
    if ($record = $result->fetch_assoc()) {
        $landID = $record['landID'];
        $landsize= $record['landSize'];
        $landlocation = $record['landLocation'];
        $soiltype = $record['soilType'];
        $farmerID = $record['farmerID'];


    } else {
        $msg1 = "No such Land ID exists.";
    }
    $stmt->close();
}

//search fertilizer request using requestID
if (isset($_POST['btnSearchRequest'])) {
    $requestID = $_POST['txtRequestID'];

    $query = "SELECT fr.requestID, fr.landID, f.fertName, fr.quantityRequested, fr.requestDate, fr.status
    FROM fertilizer_requests fr
    JOIN fertilizers f ON fr.fertID = f.fertID
    WHERE fr.requestID = ? AND fr.registeredBy= ?
    ORDER BY fr.requestDate DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $requestID, $offID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch fertilizer request details
    if ($record = $result->fetch_assoc()) {
        $requestID = $record['requestID'];
        $landID = $record['landID'];
        $fertName = $record['fertName'];
        $quantityRequested = $record['quantityRequested'];
        $requestDate = $record['requestDate'];
        $status = $record['status'];

    } else {
        $msg1 = "No such Request ID exists.";
    }
    $stmt->close();
}

// Step 3: Update Farmer Data
if (isset($_POST['btn_update_farmer'])) {
    $farmerID = $_POST['txtFarmerID'];
    $firstName = $_POST['txtFirstName'];
    $lastName = $_POST['txtLastName'];
    $age = $_POST['txtage'];
    $sex = $_POST['txtsex'];
    $telNo = $_POST['txtTelNo'];
    $addy = $_POST['txtaddy'];

    $query = "UPDATE farmers SET fname = ?, lname = ?, age = ?, sex = ?, phone_no = ?, addy=? WHERE FarmerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssisssi", $firstName, $lastName, $age, $sex, $telNo, $addy , $farmerID);
    $result = $stmt->execute();

    $msg2 = $result ? "Record updated successfully." : "Failed updating the record: " . $stmt->error;
    $stmt->close();
}

//update land details
if (isset($_POST['btn_update_land'])) {
    $landID = $_POST['txtLandID'];
    $soiltype = $_POST['txtSoilType'];
    $farmerID = $_POST['txtFarmerID'];

    $query = "UPDATE farmer_land SET soiltype = ?, farmerID = ? WHERE landID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $soiltype, $farmerID, $landID);
    $result = $stmt->execute();

    $msg2 = $result ? "Record updated successfully." : "Failed updating the record: " . $stmt->error;
    $stmt->close();
}

// Add New Farmer
if (isset($_POST['btn_add'])) {
    $firstName = $_POST['txtNewFirstName'];
    $lastName = $_POST['txtNewLastName'];
    $age = $_POST['txtNewAge'];
    $sex = $_POST['txtNewSex'];
    $telNo = $_POST['txtNewTelNo'];
    $addy = $_POST['txtNewAddy'];

    $query = "INSERT INTO farmers (fname, lname, age, sex, phone_no, addy, registeredBy) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssisssi", $firstName, $lastName, $age, $sex, $telNo, $addy, $offID);
    $result = $stmt->execute();

    $msg3 = $result ? "New farmer added successfully." : "Failed to add farmer: " . $stmt->error;
    $stmt->close();
}

// Add New Land
if (isset($_POST['btn_add_land'])) {
    $farmerID = $_POST['txtNewFarmerID'];
    $landlocation = $_POST['txtNewLandLocation'];
    $landsize = $_POST['txtNewLandSize'];
    $soiltype = $_POST['txtNewSoilType'];

    $query = "SELECT COUNT(*) FROM farmers WHERE FarmerID = ? AND registeredBy = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $farmerID, $offID);
    $stmt->execute();
    $stmt->bind_result($farmerExists);
    $stmt->fetch();
    $stmt->close();

    if ($farmerExists > 0) {

    $query = "INSERT INTO farmer_land (farmerID, landlocation,landsize, soiltype, registeredBy) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issi", $farmerID, $landlocation,$landsize, $soiltype, $offID);
    $result = $stmt->execute();

    $msg3 = $result ? "New land added successfully." : "Failed to add land: " . $stmt->error;
    $stmt->close();
} else {
    $msg3 = "Error: The provided Farmer ID does not belong to this officer.";
}
}

// Add New Fertilizer Request
if (isset($_POST['btn_add_request'])) {
    $farmerID = $_POST['txtNewFarmerID'];
    $landID = $_POST['txtNewLandID'];
    $fertID = $_POST['txtNewFertID'];
    $quantityRequested = $_POST['txtNewQuantityRequested'];
    
    $query = "SELECT COUNT(*) FROM farmers WHERE FarmerID = ? AND registeredBy = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $farmerID, $offID);
    $stmt->execute();
    $stmt->bind_result($farmerExists);
    $stmt->fetch();
    $stmt->close();

    if ($farmerExists > 0) {

        $query = "SELECT COUNT(*) FROM farmer_land WHERE landID = ? AND registeredBy = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $landID, $offID);
        $stmt->execute();
        $stmt->bind_result($landExists);
        $stmt->fetch();
        $stmt->close();

        if ($landExists > 0) {
    $query = "INSERT INTO fertilizer_requests (landID, fertID, quantityRequested, registeredBy) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $landID, $fertID, $quantityRequested, $offID);
    $result = $stmt->execute();

    $msg3 = $result ? "New fertilizer request added successfully." : "Failed to add fertilizer request: " . $stmt->error;
    $stmt->close();
        }
        else {
            $msg3 = "Error: The provided Land ID does not belong to this officer.";
        }
} else {
    $msg3 = "Error: The provided Farmer ID does not belong to this officer.";
}
}

//delete farmer
if (isset($_POST['btn_delete'])) {
    $farmerID = $_POST['txtFarmerID'];

    $query = "DELETE FROM farmers WHERE FarmerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $farmerID);
    $result = $stmt->execute();

    $msg2 = $result ? "Record deleted successfully." : "Failed deleting the record: " . $stmt->error;
    $stmt->close();
}

//delete land
if (isset($_POST['btn_delete_land'])) {
    $landID = $_POST['txtLandID'];

    $query = "DELETE FROM farmer_land WHERE landID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $landID);
    $result = $stmt->execute();

    $msg2 = $result ? "Record deleted successfully." : "Failed deleting the record: " . $stmt->error;
    $stmt->close();
}

//delete fertilizer request
if (isset($_POST['btn_delete_request'])) {
    $requestID = $_POST['txtRequestID'];

    $query = "DELETE FROM fertilizer_requests WHERE requestID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $requestID);
    $result = $stmt->execute();

    $msg2 = $result ? "Record deleted successfully." : "Failed deleting the record: " . $stmt->error;
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer's Dashboard</title>
</head>
<body>

<h1>Field Officer's Dashboard</h1>
        <h2>Welcome, <?php echo htmlspecialchars($oFname) . " " . htmlspecialchars($oLname); ?>!</h2>
            <h3>Officer's Profile</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($oFname) . " " . htmlspecialchars($oLname); ?></p>
                <p><strong>Phone No:</strong> <?php echo htmlspecialchars($ophone_no); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($oage); ?></p>
                <p><strong>Sex:</strong> <?php echo htmlspecialchars($osex); ?></p>
<hr>
<h2>Search Farmer by ID</h2>
<form method="post">
    <label for="txtFarmerID">Enter Farmer ID:</label>
    <input type="text" id="txtFarmerID" name="txtFarmerID" required>
    <button type="submit" name="btnSearchID">Search</button>
</form>
<?php if (!empty($msg1)) echo "<p>$msg1</p>"; ?>

<?php if (!empty($firstName)) : ?>
<h3>Farmer Details</h3>
<p><strong>Farmer ID:</strong> <?php echo htmlspecialchars($farmerID); ?></p>
<p><strong>First Name:</strong> <?php echo htmlspecialchars($firstName); ?></p>
<p><strong>Last Name:</strong> <?php echo htmlspecialchars($lastName); ?></p>
<p><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
<p><strong>Sex:</strong> <?php echo htmlspecialchars($sex); ?></p>
<p><strong>Phone Number:</strong> <?php echo htmlspecialchars($telNo); ?></p>
<p><strong>Address:</strong> <?php echo htmlspecialchars($addy); ?></p>

<h2>Update Farmer Details</h2>
<form method="post">
    <input type="hidden" name="txtFarmerID" value="<?php echo htmlspecialchars($farmerID); ?>">
    
    <label for="txtFirstName">First Name:</label>
    <input type="text" id="txtFirstName" name="txtFirstName" value="<?php echo htmlspecialchars($firstName); ?>" required>
    <br>

    <label for="txtLastName">Last Name:</label>
    <input type="text" id="txtLastName" name="txtLastName" value="<?php echo htmlspecialchars($lastName); ?>" required>
    <br>

    <label for="txtage">Age:</label>
    <input type="number" id="txtage" name="txtage" value="<?php echo htmlspecialchars($age); ?>" required>
    <br>

    <label for="txtsex">Sex:</label>
    <select id="txtsex" name="txtsex" required>
        <option value="Male" <?php echo ($sex == 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo ($sex == 'Female') ? 'selected' : ''; ?>>Female</option>
        <option value="Other" <?php echo ($sex == 'Other') ? 'selected' : ''; ?>>Other</option>
    </select>
    <br>

    <label for="txtTelNo">Phone Number:</label>
    <input type="tel" id="txtTelNo" name="txtTelNo" value="<?php echo htmlspecialchars($telNo); ?>" required>
    <br>

    <label for="txtaddy">Address:</label>
    <input type="text" id="txtaddy" name="txtaddy" value="<?php echo htmlspecialchars($addy); ?>" required>
    <br>

    <button type="submit" name="btn_update">Save</button>
</form>
<br>

<h3>Land Details</h3>
<?php if (!empty($land_details)): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Land ID</th>
                <th>Location</th>
                <th>Soil Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($land_details as $land): ?>
                <tr>
                    <td><?php echo htmlspecialchars($land['landID']); ?></td>
                    <td><?php echo htmlspecialchars($land['landlocation']); ?></td>
                    <td><?php echo htmlspecialchars($land['soiltype']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No land records found.</p>
<?php endif; ?>

<h3>Fertilizer Request History</h3> 
                <?php if (!empty($requests)): ?>
                    <table border="1">
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
<?php else: ?>
    <p>No farmer details found.</p>
<?php endif; ?>
<h2>Add New Farmer</h2>
        <form method="post">
            <label for="txtNewFirstName">First Name:</label>
            <input type="text" name="txtNewFirstName" required placeholder="First Name">
            <br>
            <label for="txtNewLastName">Last Name:</label>
            <input type="text" name="txtNewLastName" required placeholder="Last Name">
            <br>
            <label for="txtNewSex">sex:</label>
            <select name="txtNewSex" required>                  
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <br>
            <label for="txtNewAge">Age:</label>
            <input type="number" name="txtNewAge" required placeholder="Age">
            <br>
            <label for="txtNewTelNo">Phone Number:</label>
            <input type="tel" name="txtNewTelNo" required placeholder="Phone Number">
            <br>
            <label for="txtNewAddy">Address:</label>
            <input type="text" name="txtNewAddy" required placeholder="Address">
            <br>
            <button type="submit" name="btn_add">Add Farmer</button>
        </form>
<hr>
<h2>Search Land by ID</h2>
<form method="post">
    <label for="txtLandID">Enter Land ID:</label>
    <input type="text" id="txtLandID" name="txtLandID" required>
    <button type="submit" name="btnSearchLand">Search</button>
</form>
<?php if (!empty($msg1)) echo "<p>$msg1</p>"; ?>

<?php if (!empty($landID)) : ?>
<h3>Land Details</h3>
<p><strong>Land ID:</strong> <?php echo htmlspecialchars($landID); ?></p>
<p><strong>Location:</strong> <?php echo htmlspecialchars($landlocation); ?></p>
<p><strong>land size:</strong> <?php echo htmlspecialchars($landsize ); ?></p>
<p><strong>Soil Type:</strong> <?php echo htmlspecialchars($soiltype); ?></p>
<p><strong>Farmer ID:</strong> <?php echo htmlspecialchars($farmerID); ?></p>

<h2>Update Land Details</h2>
<form method="post">
    <input type="hidden" name="txtLandID" value="<?php echo htmlspecialchars($landID); ?>">
    
    <label for="txtSoilType">Soil Type:</label>
    <input type="text" id="txtSoilType" name="txtSoilType" value="<?php echo htmlspecialchars($soiltype); ?>" required>
    <br>

    <label for="txtFarmerID">Farmer ID:</label>
    <input type="number" id="txtFarmerID" name="txtFarmerID" value="<?php echo htmlspecialchars($farmerID); ?>" required>
    <br>

    <button type="submit" name="btn_update_land">Save</button>
</form>

<?php else: ?>
    <p>No land details found.</p>
<?php endif; ?>
<h2>Add New Land</h2>
        <form method="post">
            <lable for="txtNewFarmerID">Farmer ID:</lable>
            <input type="number" name="txtNewFarmerID" required placeholder="Farmer ID">
            <br>
            <label for="txtNewLandLocation">Location:</label>
            <input type="text" name="txtNewLandLocation" required placeholder="Location">
            <br>
            <label for="txtNewLandSize">Land Size:</label>
            <input type="number" step="0.01" name="txtNewLandSize" required placeholder="Land Size">
            <br>
            <label for="txtNewSoilType">Soil Type:</label>
            <input type="text" name="txtNewSoilType" required placeholder="Soil Type">
            <br>
            <button type="submit" name="btn_add_land">Add Land</button>
        </form>
        <?php if (!empty($msg3)) echo "<p>$msg3</p>"; ?>
        <hr>
<h2>Search Fertilizer Request by ID</h2>
<form method="post">
    <label for="txtRequestID">Enter Request ID:</label>
    <input type="text" id="txtRequestID" name="txtRequestID" required>
    <button type="submit" name="btnSearchRequest">Search</button>
</form>
<?php if (!empty($msg1)) echo "<p>$msg1</p>"; ?>

<?php if (!empty($requestID)) : ?>
<h3>Fertilizer Request Details</h3>
<p><strong>Request ID:</strong> <?php echo htmlspecialchars($requestID); ?></p>
<p><strong>Land ID:</strong> <?php echo htmlspecialchars($landID); ?></p>
<p><strong>Fertilizer Name:</strong> <?php echo htmlspecialchars($fertName); ?></p>
<p><strong>Quantity Requested:</strong> <?php echo htmlspecialchars($quantityRequested); ?></p>
<p><strong>Request Date:</strong> <?php echo htmlspecialchars($requestDate); ?></p>
<p><strong>Status:</strong> <?php echo htmlspecialchars($status); ?></p>

<h2>Update Fertilizer Request Details</h2>
<form method="post">
    <input type="hidden" name="txtRequestID" value="<?php echo htmlspecialchars($requestID); ?>">
    
    <label for="txtLandID">Land ID:</label>
    <input type="number" id="txtLandID" name="txtLandID" value="<?php echo htmlspecialchars($landID); ?>" required>
    <br>

    <label for="txtFertID">Fertilizer ID:</label>
    <input type="number" id="txtFertID" name="txtFertID" value="<?php echo htmlspecialchars($fertID); ?>" required>
    <br>

    <label for="txtQuantityRequested">Quantity Requested:</label>
    <input type="number" id="txtQuantityRequested" name="txtQuantityRequested" value="<?php echo htmlspecialchars($quantityRequested); ?>" required>
    <br>

    <button type="submit" name="btn_update_request">Save</button>
</form>
<br>
<?php else: ?>
    <p>No fertilizer request details found.</p>
<?php endif; ?>
<h2>Add New Fertilizer Request</h2>
        <form method="post">
            <label for="txtNewLandID">Land ID:</label>
            <input type="number" name="txtNewLandID" required placeholder="Land ID">
            <br>
            <label for="txtNewFertID">Fertilizer ID:</label>
            <input type="number" name="txtNewFertID" required placeholder="Fertilizer ID">
            <br>
            <label for="txtNewQuantityRequested">Quantity Requested:</label>
            <input type="number" name="txtNewQuantityRequested" required placeholder="Quantity Requested">
            <br>
            <button type="submit" name="btn_add_request">Add Request</button>
        </form>
        <?php if (!empty($msg1)) echo "<p>$msg1</p>"; ?>
        <hr>

<h2>Manage Farmer Records</h2>
<?php if (!empty($farmers)): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Farmer ID</th>
                <th>Name</th>
                <th>Phone no</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($farmers as $farmer): ?>
                <tr>
                    <td><?php echo htmlspecialchars($farmer['farmerID']); ?></td>
                    <td><?php echo htmlspecialchars($farmer['FName']) . " " . htmlspecialchars($farmer['LName']); ?></td>
                    <td><?php echo htmlspecialchars($farmer['phone_no']); ?></td>
                    <td><?php echo htmlspecialchars($farmer['age']); ?></td>
                    <td><?php echo htmlspecialchars($farmer['sex']); ?></td>
                    <td><?php echo htmlspecialchars($farmer['addy']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No farmer records found.</p>
<?php endif; ?>
<hr>

<h2>Manage Land Records</h2>
<?php if (!empty($lands)): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Land ID</th>
                <th>Farmer ID</th>
                <th>Location</th>
                <th>Soil Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lands as $land): ?>
                <tr>
                    <td><?php echo htmlspecialchars($land['landID']); ?></td>
                    <td><?php echo htmlspecialchars($land['farmerID']); ?></td>
                    <td><?php echo htmlspecialchars($land['landLocation']); ?></td>
                    <td><?php echo htmlspecialchars($land['soilType']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No land records found.</p>
<?php endif; ?>
<hr>
<h2>Manage Fertilizer Requests</h2>
<?php if (!empty($fertilizer_requests)): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Land ID</th>
                <th>Farmer ID</th>
                <th>Fertilizer Name</th>
                <th>Quantity Requested</th>
                <th>Request Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fertilizer_requests as $req): ?>
                <tr>
                    <td><?php echo htmlspecialchars($req['requestID']); ?></td>
                    <td><?php echo htmlspecialchars($req['landID']); ?></td>
                    <td><?php echo htmlspecialchars($req['farmerID']); ?></td>
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
<hr>
<h2>Statistics</h2>
<table border="1">
    <thead>
        <tr>
            <th>Category</th>
            <th>Total count</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Farmers</td>
            <td><?php echo $farmer_count; ?></td>
        </tr>
        <tr>
            <td>Lands</td>
            <td><?php echo $land_count; ?></td>
        </tr>
        <tr>
            <td>Fertilizer Requests</td>
            <td><?php echo $request_count; ?></td>
        </tr>
    </tbody>
</body>
</html>
