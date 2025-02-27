<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: OfficerLogin.php");
    exit();
}

// Include the database connection
include 'connection.php';

// Get the logged-in officer's username
$username = $_SESSION['username'];

// Fetch officer's login details
$stmt = $conn->prepare("SELECT offID FROM officer_login WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($juniorID);
$stmt->fetch();
$stmt->close();

// Fetch officer's personal details
$stmt = $conn->prepare("SELECT Fname, Lname, phone_no, email, age, sex FROM officers WHERE offID = ?");
$stmt->bind_param("i", $juniorID);
$stmt->execute();
$stmt->bind_result($oFname, $oLname, $ophone_no, $oemail, $oage, $osex);
$stmt->fetch();
$stmt->close();

// Fetch Field Officers under this Junior Officer
$field_officers = [];
$query = "SELECT offID, Fname, Lname, phone_no, email,age,sex FROM officers WHERE supervisorID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $juniorID);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $field_officers[] = $row;
}
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer's Dashboard</title>
    <link rel="stylesheet" href="farmer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div id="officer-dashboard" class="officer-tab-container">
        <h1>Field Officer's Dashboard</h1>
        <h2>Welcome, <?php echo htmlspecialchars($oFname) . " " . htmlspecialchars($oLname); ?>!</h2>

        <div class="card-container">
            <!-- Profile Card -->
            <div class="card">
                <i class="fas fa-user icon"></i>
                <h3>Officer's Profile</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($oFname) . " " . htmlspecialchars($oLname); ?></p>
                <p><strong>Phone No:</strong> <?php echo htmlspecialchars($ophone_no); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($oage); ?></p>
                <p><strong>Sex:</strong> <?php echo htmlspecialchars($osex); ?></p>
            </div>
        </div>
        <?php if (!empty($field_officers)): ?>
            <br>
    <table border="1">
        <thead>
            <tr>
                <th>Officer ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($field_officers as $officer): ?>
                <tr>
                    <td><?php echo htmlspecialchars($officer['offID']); ?></td>
                    <td><?php echo htmlspecialchars($officer['Fname']); ?></td>
                    <td><?php echo htmlspecialchars($officer['Lname']); ?></td>
                    <td><?php echo htmlspecialchars($officer['phone_no']); ?></td>
                    <td><?php echo htmlspecialchars($officer['email']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No field officers found under your supervision.</p>
<?php endif; ?>

        <a href="OfficerLogin.php" class="logout-btn">Logout</a> <!-- Logout Button -->
</body>
</html>
