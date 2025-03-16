<?php 
session_start(); 
require_once('connection.php');
require_once('functions.php'); // Import helper functions

$msg = ""; // Initialize message variable

if (isset($_POST['btnLogin'])) {
    $username = trim($_POST['txtUName']);
    $password = trim($_POST['txtPsw']);
    $selectedRole = trim($_POST['officerType']);

    if (empty($selectedRole)) {
        $msg = "Please select your role.";
    } else {
        // Fetch officer ID and hashed password
        $officer = fetchSingleRow($conn, "SELECT offID, password FROM officer_login WHERE username = ?", "s", $username);

        if ($officer) {
            $offID = $officer['offID'];
            $hashedPassword = $officer['password'];

            // Verify password
            if (password_verify($password, $hashedPassword)) {
                // Fetch role from `officers` table
                $role = getSingleValue($conn, "SELECT role FROM officers WHERE offID = ?", "i", $offID);

                if ($role && strtolower($role) === strtolower($selectedRole)) {
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;

                    // Redirect based on role
                    $dashboardPages = [
                        'Field Officer' => 'off1.php',
                        'Junior Officer' => 'Off2.php',
                        'Senior Officer' => 'Off3.php',
                        'Quality Control Officer' => 'Off5.php',
                        'Subsidy Payment Officer' => 'Off4.php'
                    ];

                    if (isset($dashboardPages[$role])) {
                        header("Location: " . $dashboardPages[$role]);
                        exit();
                    } else {
                        $msg = "Invalid role.";
                    }
                } else {
                    $msg = "Error: Selected role does not match your assigned role.";
                }
            } else {
                $msg = "Invalid Username or Password.";
            }
        } else {
            $msg = "Invalid Username or Password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Login | Fertilizer Distribution</title>
    <link rel="stylesheet" href="OfficerLogin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" defer></script>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <img src="images/indialogoimages6.jpeg" alt="Bharat Logo" class="logo">
            <h2>Officer Login</h2>
            <p class="subtext">Access your panel to manage fertilizer distribution.</p>
            
            <form id="officerLoginForm" method="POST" action="OfficerLogin.php">
                <div class="input-group">
                    <label for="officerType">Select Officer Type</label>
                    <select id="officerType" name="officerType" required> <!-- Added name attribute -->
                        <option value="">-- Select Role --</option>
                        <option value="Field Officer">Field Officer</option>
                        <option value="Junior Officer">Junior Officer</option>
                        <option value="Senior Officer">Senior Officer</option>
                        <option value="Quality Control Officer">Quality Control Officer</option>
                        <option value="Subsidy Payment Officer">Subsidy Payment Officer</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="txtUName" placeholder="Enter username" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="txtPsw" placeholder="Enter password" required>
                </div>

                <button type="submit" name="btnLogin" class="login-btn">Login</button>
                <p class="error-msg"><?= $msg ?></p>
            </form>
        </div>
    </div>

</body>
</html>
