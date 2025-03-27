<?php 
session_start();
require_once('connection.php');
$msg = "";

if (isset($_POST['btnLogin'])) {
    $username = trim($_POST['txtUName']);
    $password = trim($_POST['txtPsw']);

    $stmt = $conn->prepare("SELECT Password FROM FARMER_login WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['Password'])) {
            $_SESSION['username'] = $username;
            header("Location: FarmerDashboard.php");
            exit();
        } else {
            $msg = "Invalid Password";
        }
    } else {
        $msg = "Invalid Username";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Login</title>
    <style>
        /* Fullscreen Background */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: url('images/farmerlogin2.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            backdrop-filter: blur(8px); /* Blur effect */
        }

        /* Login Box */
        .login-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        /* Logo */
        .logo {
            width: 80px;
            margin-bottom: 10px;
        }


        /* Heading */
        h2 {
            color: #fff;
            font-size: 26px;
        }

        /* Labels */
        label {
            display: block;
            font-weight: bold;
            margin: 10px 0 5px;
            color: #fff;
            text-align: left;
        }

        /* Input Fields */
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            outline: none;
            transition: 0.3s;
        }

        input::placeholder {
            color: #ddd;
        }

        input:focus {
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        /* Login Button */
        button {
            width: 100%;
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border: none;
            padding: 12px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 8px;
            transition: 0.3s;
        }

        button:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Forgot Password */
        p a {
            text-decoration: none;
            color: #fff;
            font-size: 14px;
        }

        p a:hover {
            text-decoration: underline;
        }

        /* Error Message */
        .error-msg {
            color: #ff4d4d;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="login-box">
    <img src="images/firm_logo1.png" alt="Farmer Logo" class="logo"> <!-- Add your logo here -->
        <h2>Farmer Login</h2>
        <form id="loginForm" method="post">
            <p class="error-msg"><?php echo $msg; ?></p>
            
            <label for="username">Username</label>
            <input type="text" id="username" name="txtUName" placeholder="Enter your username" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="txtPsw" placeholder="Enter your password" required>

            <button type="submit" name="btnLogin">Login</button>
            <p><a href="#">Forgot Password?</a></p>
        </form>
    </div>

</body>
</html>
