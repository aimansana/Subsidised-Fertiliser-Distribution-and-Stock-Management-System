<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: OfficerLogin.php");
    exit();
}

include 'connection.php';
include 'functions.php';

$username = $_SESSION['username'];

// Fetch officer's ID
$officer = fetchSingleRow($conn, "SELECT offID FROM officer_login WHERE username = ?", "s", $username);
$offID = $officer['offID'];

// Fetch officer's details
$officerDetails = fetchSingleRow($conn, "SELECT * FROM officers WHERE offID = ?", "i", $offID);

// Fetch Junior Officers and their stock
$juniorOfficers = fetchAllRows($conn, "
    SELECT o.offID, o.regionID, COALESCE(fs.total_stock, 0) AS total_stock
    FROM officers o
    LEFT JOIN fertilizer_stock fs ON o.offID = fs.officerID
    WHERE o.supervisorID = ?
", "i", $offID);

// Set total stock available
$totalstock = 5000;
$allocatedstock = 0;

if (isset($_POST['stockbtn'])) {
    $allocations = $_POST['allocations']; // Get input as associative array
    $total_allocated = array_sum($allocations); // Sum of all inputs

    if ($total_allocated <= $totalstock) {
        foreach ($allocations as $juniorID => $amount) {
            if ($amount > 0) {
                // Store allocation in the fertilizer_allocation table
                $query = "INSERT INTO fertilizer_allocation (officerID, amount_allocated, allocated_by) VALUES (?, ?, ?)";
                executeQuery($conn, $query, "iii", $juniorID, $amount, $offID);

                // Check if junior officer already has a stock record
                $existingStock = fetchSingleRow($conn, "SELECT total_stock FROM fertilizer_stock WHERE officerID = ?", "i", $juniorID);

                if ($existingStock) {
                    // Update existing stock
                    $updateQuery = "UPDATE fertilizer_stock SET total_stock = total_stock + ? WHERE officerID = ?";
                    executeQuery($conn, $updateQuery, "di", $amount, $juniorID);
                } else {
                    // Insert new stock record
                    $insertQuery = "INSERT INTO fertilizer_stock (officerID, total_stock) VALUES (?, ?)";
                    executeQuery($conn, $insertQuery, "id", $juniorID, $amount);
                }

                $allocatedstock += $amount;
            }
        }

        echo "<script>alert('Stock allocated successfully!');</script>";
    } else {
        echo "<script>alert('Total allocated stock exceeds available stock!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior Officer Dashboard</title>
    <link rel="stylesheet" href="">
    
</head>
<body>
    
    <header>
        <h1>Senior Officer Dashboard</h1>
    </header>

    <h2>Profile</h2>
    <h3>Your Profile</h3>
    <table class="profile-table">
        <tr><th>ID:</th><td><?= htmlspecialchars($officerDetails['offID']) ?></td></tr>
        <tr><th>Name:</th><td><?= htmlspecialchars($officerDetails['Fname'] . " " . $officerDetails['Lname']); ?></td></tr>
        <tr><th>Designation:</th><td>Senior Officer</td></tr>
        <tr><th>Email:</th><td><?= htmlspecialchars($officerDetails['email']); ?></td></tr>
        <tr><th>Phone:</th><td><?= htmlspecialchars($officerDetails['phone_no']); ?></td></tr>
        <tr><th>Age:</th><td><?= htmlspecialchars($officerDetails['age']); ?></td></tr>
        <tr><th>Sex:</th><td><?= htmlspecialchars($officerDetails['sex']); ?></td></tr>
    </table>

    <hr>

    <h2>STOCK OVERVIEW</h2>
    <h3>Junior Officers and their Stock</h3>
    <table border="1">
        <tr>
            <th>#</th>
            <th>Junior Officer</th>
            <th>Region ID</th>
            <th>Total Stock</th>
        </tr>
        <?php
        $index = 1;
        foreach ($juniorOfficers as $officer): ?>
            <tr>
                <td><?= $index++ ?></td>
                <td><?= $officer['offID'] ?></td> 
                <td><?= $officer['regionID'] ?: 'N/A' ?></td>
                <td><?= $officer['total_stock'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <hr>

    <h2>STOCK ALLOCATION</h2>
    <p>Total Stock: <?= $totalstock ?></p>

    <form method="post">
        <table border="1">
            <tr>
                <th>#</th>
                <th>Junior Officer</th>
                <th>Stock Allocated</th>
            </tr>
            <?php
            $index = 1;
            foreach ($juniorOfficers as $officer): ?>
                <tr>
                    <td><?= $index++ ?></td>
                    <td><?= $officer['offID'] ?></td> 
                    <td>
                        <input type="number" name="allocations[<?= $officer['offID'] ?>]" min="0" required>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>            
        <button type="submit" name="stockbtn">Allocate Stock</button>
    </form>

</body>
</html>
