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
    <title>officer Login</title>
    <link rel="stylesheet" href="farmer.css">
</head>
<body>

    <!-- Login Section -->
    <div id="login-section" class="login-section">
        <h2>Login for officers</h2>
    
        <form id="login-form" action="OfficerLogin.php" method="post">
            <br>
            <label for="lblMsg"><b><?php echo $msg; ?></b></label>
            <br>
            <input type="text" name="txtUName" placeholder="Username" required>
            <br>
            <input type="password" name="txtPsw" placeholder="Password" required>
            <br>
            <br>
            <button type="submit" name="btnLogin" class="btn btn-success btn-lg">Log In</button>
        </form>
    </div>

    <!-- js files need to be checked -->
    <script src="farmer.js"></script>
</body>
</html>
