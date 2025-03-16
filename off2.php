<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: OfficerLogin.php");
    exit();
}

// Include the database connection
include 'connection.php';

// Include the functions
include 'functions.php';

// Get the logged-in officer's username
$username = $_SESSION['username'];


// Fetch officer's ID
$officer = fetchSingleRow($conn, "SELECT offID FROM officer_login WHERE username = ?", "s", $username);
$offID = $officer['offID'] ?? null;

// Fetch officer's details
$officerDetails = fetchSingleRow($conn, "SELECT Fname, Lname, phone_no, email, age, sex FROM officers WHERE offID = ?", "i", $offID);


//ALL RECORDS

// Fetch all Field Officers 
$field_officers = fetchAllRows($conn, "
    SELECT o.offID, o.Fname, o.Lname, o.phone_no, o.email, o.age, 
           COUNT(DISTINCT f.requestID) AS request_count,
           COUNT(DISTINCT fa.farmerID) AS farmer_count
    FROM officers o
    LEFT JOIN fertilizer_requests f ON o.offID = f.registeredBy
    LEFT JOIN farmers fa ON o.offID = fa.registeredBy
    WHERE o.supervisorID = ?
    GROUP BY o.offID, o.Fname, o.Lname, o.phone_no, o.email, o.age
", "i", $offID);

// Fetch all fertilizer requests
$requests = fetchAllRows($conn, "
    SELECT f.requestID, f.registeredBy, f.farmerID, f.landID, f.quantityRequested, 
           f.requestDate, f.status
    FROM fertilizer_requests f
    JOIN officers o ON f.registeredBy = o.offID
    WHERE o.supervisorID = ?
    ORDER BY f.requestDate DESC
", "i", $offID);

//count status
$statusCounts = [
    'Pending' => 0,
    'Approved' => 0,
    'Rejected' => 0
];

foreach ($requests as $request) {
    if (isset($statusCounts[$request['status']])) {
        $statusCounts[$request['status']]++;
    }
}

// Output the counts
//echo "Pending: " . $statusCounts['Pending'] . "<br>";
//echo "Approved: " . $statusCounts['Approved'] . "<br>";
//echo "Rejected: " . $statusCounts['Rejected'] . "<br>";



//SEARCH BY ID

//Field Officer by ID
if (isset($_POST['searcho1btn'])) {
    $search = intval($_POST['search']); // Ensure ID is an integer

    // Fetch the Field Officer details
    $searchrow = fetchSingleRow($conn, "
        SELECT offID, Fname, Lname, phone_no, email 
        FROM officers 
        WHERE supervisorID = ? AND offID = ?", 
        "ii", $offID, $search
    );

    // If a Field Officer is found, fetch their fertilizer requests
    if ($searchrow) 
    {
        $searchoffreq = fetchAllRows($conn,"SELECT requestID, registeredBy, farmerID, landID, quantityRequested, requestDate, status
                  FROM fertilizer_requests
                  WHERE registeredBy = ? 
                  ORDER BY requestDate DESC", "i", $search);
    }
}

//Request by ID
if (isset($_POST['searchreqbtn'])) {
    $search = intval($_POST['search']); // Ensure ID is an integer

    // Ensure request is made by a Field Officer under this Junior Officer
    $searchreq = fetchSingleRow(
        $conn,
        "SELECT fr.requestID, fr.landID, fr.farmerID, fr.quantityRequested, fr.requestDate, fr.registeredBy, fr.status
        FROM fertilizer_requests fr
        JOIN officers o ON fr.registeredBy = o.offID
        WHERE fr.requestID = ? AND o.supervisorID = ? 
        ORDER BY fr.requestDate DESC",
        "ii",
        $search,
        $offID
    );
}

// Approve or reject fertilizer requests
if (isset($_POST['approvebtn']) || isset($_POST['rejectbtn'])) {
    $requestID = $_POST['requestID'];
    $status = isset($_POST['approvebtn']) ? 'Approved' : 'Rejected';

    $result = executeQuery($conn, "UPDATE fertilizer_requests SET status = ?, reviewedBy=? WHERE requestID = ?", "sii", $status, $offID,$requestID);
    if ($result) {
        header("Location: Off2.php");
        exit();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Junior Officer Dashboard</title>
    <link rel="stylesheet" href="off2.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Fertilizer Management</h2>
            </div>
            <ul>
    <li><a href="#" data-section="profile"><i class="fas fa-user"></i> Profile</a></li>
    <li><a href="#" data-section="field-officers"><i class="fas fa-users"></i> Field Officers</a></li>
    <li><a href="#" data-section="requests"><i class="fas fa-file-alt"></i> Farmer Requests</a></li>
    <li><a href="#" data-section="approval"><i class="fas fa-file-alt"></i> Approval</a></li>
    <li><a href="#" data-section="analytics"><i class="fas fa-chart-bar"></i> Analytics</a></li>
    <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
    </ul>

        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Dashboard</h2>
                <div class="user-info">
                    <img src="user-avatar.png" alt="User Avatar">
                    <p>Junior Officer</p>
                </div>
            </div>

            <!-- Profile Section -->
            <div class="section active" id="profile">
                <h3><i class="fas fa-user"></i>Junior Officer Dashboard</h3>
                <h2>Welcome, <?php echo $officerDetails['Fname'] . " " . $officerDetails['Lname']; ?></h2>
                <div class="profile-container">
                    <img src="images/field_officer2.jpeg" alt="Profile Picture" class="profile-pic">
                    <table class="profile-table">
                        <tr><th>First name:</th><td><?php echo $officerDetails['Fname']; ?></td></tr>
                        <tr><th>Last name:</th><td><?php echo $officerDetails['Lname']; ?></td></tr>
                        <tr><th>Sex:</th><td><?php echo $officerDetails['sex']; ?></td></tr>
                        <tr><th>Designation:</th><td>Junior Officer</td></tr>
                        <tr><th>Email:</th><td><?php echo $officerDetails['email']; ?></td></tr>
                        <tr><th>Phone:</th><td><?php echo $officerDetails['phone_no']; ?></td></tr>
                    </table>
                </div>
            </div>

            <!-- Field Officers Section -->
            <div class="section" id="field-officers">
                <h3><i class="fas fa-users"></i> Field Officer Details</h3>
                <form method="post">
                    <input type="text" name="search" placeholder="Search by ID">
                    <button type="submit" name="searcho1btn">Search</button>
                </form>
                
                <?php if (!empty($searchrow)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $search; ?></td>
                            <td><?php echo $searchrow['Fname']; ?></td>
                            <td><?php echo $searchrow ['Lname']; ?></td>
                            <td><?php echo $searchrow['phone_no']; ?></td>
                            <td><?php echo $searchrow['email']; ?></td>
                        </tr>
                    </tbody>
                </table>
                
                <h4> Requests </h4>
        <?php if(!empty($searchoffreq)): ?>
        <!--requests under the field off -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Request ID</th>
                        <th>Land ID</th>
                        <th>Farmer ID</th>
                        <th>Quantity Requested</th>
                        <th>Request Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php $index = 1; // Initialize counter ?>
                <?php foreach ($searchoffreq as $req): ?>
                    <tr>
                        <td><?php echo $index++; ?></td>
                        <td><?php echo $req['requestID']; ?></td>
                        <td><?php echo $req['landID']; ?></td>
                        <td><?php echo $req['farmerID']; ?></td>
                        <td><?php echo $req['quantityRequested']; ?></td>
                        <td><?php echo $req['requestDate']; ?></td>
                        <td><?php echo $req['status']; ?></td>
                    </tr>
                </tbody>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No requests found</p>
        <?php endif; ?>
    <?php else: ?>
        <p>No Field Officer found</p>
    <?php endif; ?>
    

    <h4> All Field Officers </h4>
    <table class=" data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php $index = 1; // Initialize counter ?>
            <?php foreach ($field_officers as $field_officer): ?>
                <tr>
                    <td><?php echo $index++; ?></td>
                    <td><?php echo $field_officer['offID']; ?></td>
                    <td><?php echo $field_officer['Fname']; ?></td>
                    <td><?php echo $field_officer['Lname']; ?></td>
                    <td><?php echo $field_officer['phone_no']; ?></td>
                    <td><?php echo $field_officer['email']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
            </div>

            <!-- Requests Section -->
            <div class="section" id="requests">
                <h3><i class="fas fa-file-alt"></i> Fertilizer Requests</h3>
                <form method="post">
        <input type="text" name="search" placeholder="Search by ID">
        <button type="submit" name="searchreqbtn">Search</button>
    </form>
    <?php if (!empty($searchreq)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                        <th>Request ID</th>
                    <th>Land ID</th>
                    <th>Farmer ID</th>
                    <th>Quantity Requested</th>
                    <th>Request Date</th>
                    <th>RegisterdBy</th>
                    <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td><?php echo $search; ?></td>
                    <td><?php echo $searchreq['landID']; ?></td>
                    <td><?php echo $searchreq['farmerID']; ?></td>
                    <td><?php echo $searchreq['quantityRequested']; ?></td>
                    <td><?php echo $searchreq['requestDate']; ?></td>
                    <td><?php echo $searchreq['registeredBy']; ?></td>
                    <td><?php echo $searchreq['status']; ?></td>
                
                        </tr>
                        
                    </tbody>
                </table>
                <?php else: ?>
        <p>No requests found</p>
    <?php endif; ?>

    <h3>All Fertilizer Requests</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Request ID</th>
                <th>Land ID</th>
                <th>Farmer ID</th>
                <th>Quantity Requested</th>
                <th>Request Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $index = 1; // Initialize counter ?>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo $index++; ?></td>
                    <td><?php echo $request['requestID']; ?></td>
                    <td><?php echo $request['landID']; ?></td>
                    <td><?php echo $request['farmerID']; ?></td>
                    <td><?php echo $request['quantityRequested']; ?></td>
                    <td><?php echo $request['requestDate']; ?></td>
                    <td><?php echo $request['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
            </div>

            <div class="section" id="approval">
    <h3>Approval Requests</h3>
    <table class= "data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Request ID</th>
                <th>Land ID</th>
                <th>Farmer ID</th>
                <th>Quantity Requested</th>
                <th>Request Date</th>
                <th>Status</th>
                <th> Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $index = 1; // Initialize counter ?>
            <?php foreach (array_reverse($requests) as $request): ?>
                <?php if ($request['status'] === 'Pending'): ?>
                    <tr>
                        <td><?php echo $index++; ?></td>
                        <td><?php echo $request['requestID']; ?></td>
                        <td><?php echo $request['landID']; ?></td>
                        <td><?php echo $request['farmerID']; ?></td>
                        <td><?php echo $request['quantityRequested']; ?></td>
                        <td><?php echo $request['requestDate']; ?></td>
                        <td><?php echo $request['status']; ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="requestID" value="<?php echo $request['requestID']; ?>">
                                <button type="submit" name="approvebtn">Approve</button>
                                <button type="submit" name="rejectbtn">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>


            </div>

            <!-- Analytics Section -->
            <div class="section" id="analytics">
            <h3><i class="fas fa-chart-bar"></i>Analytics</h3>

<!-- Requests by Officers Table -->
<h4>Requests by Officers</h4>
<table border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Officer ID</th>
            <th>No. of Requests</th>
        </tr>
    </thead>
    <tbody>
        <?php $index = 1; ?>
        <?php foreach ($field_officers as $field_officer): ?>
            <tr>
                <td><?php echo $index++; ?></td>
                <td><?php echo $field_officer['offID']; ?></td>
                <td><?php echo $field_officer['request_count']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<canvas id="requestsChart" ></canvas>

<!-- Count of Farmers Table -->
<h4>Count of Farmers</h4>
<table border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Officer ID</th>
            <th>No. of Farmers</th>
        </tr>
    </thead>
    <tbody>
        <?php $index = 1; ?>
        <?php foreach ($field_officers as $field_officer): ?>
            <tr>
                <td><?php echo $index++; ?></td>
                <td><?php echo $field_officer['offID']; ?></td>
                <td><?php echo $field_officer['farmer_count']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<canvas id="farmersChart"></canvas>

<!-- Count of Request Status Table -->
<h4>Count of Request Status</h4>
<table border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Status</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
        <?php $index = 1; ?>
        <?php foreach ($statusCounts as $status => $count): ?>
            <tr>
                <td><?php echo $index++; ?></td>
                <td><?php echo $status; ?></td>
                <td><?php echo $count; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<canvas id="statusChart"></canvas>

<!-- Pass PHP data to JavaScript -->
<script>
    const officerLabels = <?php echo json_encode(array_column($field_officers, 'offID')); ?>;
    const requestData = <?php echo json_encode(array_column($field_officers, 'request_count')); ?>;
    const farmerData = <?php echo json_encode(array_column($field_officers, 'farmer_count')); ?>;
    const statusLabels = <?php echo json_encode(array_keys($statusCounts)); ?>;
    const statusData = <?php echo json_encode(array_values($statusCounts)); ?>;
</script>
            </div>

        </div>
    </div>
    
    <script src="off2.js"></script>
</body>
</html>
