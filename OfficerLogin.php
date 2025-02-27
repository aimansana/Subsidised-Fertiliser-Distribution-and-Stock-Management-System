<?php 
session_start(); // Ensure session is started at the beginning
require_once('connection.php');

$msg = ""; // Initialize message variable

if (isset($_POST['btnLogin'])) {
    $username = $_POST['txtUName'];
    $password = $_POST['txtPsw'];

    // Use prepared statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT password FROM Officer_login WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        
        // Verify password using password_verify()
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;

            $stmt = $conn->prepare("SELECT offID FROM officer_login WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($offID);
            $stmt->fetch();
            $stmt->close();

            $stmt = $conn->prepare("SELECT role FROM officers WHERE offID = ?");
            $stmt->bind_param("i", $offID);
            $stmt->execute();
            $stmt->bind_result($role);
            $stmt->fetch();
            $stmt->close();
            echo $role;
            
            switch ($role) {
                case 'Field Officer': header("Location: off1.php"); exit();
                case 'Junior Officer': header("Location: Off2.php"); exit();
                case 'Senior Officer': header("Location: Off3.php"); exit();
                case 'Quality Control Officer': header("Location: Off4.php"); exit();
                case 'Subsidy Payment Officer': header("Location: Off5.php"); exit();
                default: $msg = "Invalid depat id";
            }
        }
        else {
            $msg = "Invalid Username or Password";
        }
    }else {
        $msg = "Invalid Username or Password";
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
                    <select id="officerType" required>
                        <option value="">-- Select Role --</option>
                        <option value="field">Field Officer</option>
                        <option value="junior">Junior Officer</option>
                        <option value="senior">Senior Officer</option>
                        <option value="quality">Quality Control Officer</option>
                        <option value="subsidy">Subsidy Payment Officer</option>
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
                <p class="error-msg" id="errorMsg"></p>
            </form>
        </div>
    </div>

    <!--
    <script src="OfficerLogin.js"></script>
    check js file 
    -->

</body>
</html>
