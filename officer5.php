<?php
echo "off5 is here";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subsidy Payment Officer Dashboard</title>
    <link rel="stylesheet" href="off.css">
</head>
<body>
    <header>
        <h1>Subsidy Payment Officer Dashboard</h1>
    </header>

    <main>
        <!-- Subsidy Payment Officer Dashboard -->
        <section id="subsidy-payment-dashboard" class="dashboard">
           
            <button onclick="viewPendingPayments()">View Pending Payments</button>
            <button onclick="viewFarmerDetails()">View Farmer Details</button>
            <button onclick="viewPreviousPayments()">View Previous Completed Payments</button>
        </section>
    </main>

    <!-- Linking subsidypayment.js -->
    <script src="off5.js"></script>
</body>
</html>

