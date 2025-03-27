<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['offID'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$officer_id = $_SESSION['offID'];

// ✅ Fix: Use 'offID' instead of 'id'
$query = "SELECT * FROM officers WHERE offID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $officer_id);
$stmt->execute();
$result = $stmt->get_result();
$officer = $result->fetch_assoc();

echo json_encode($officer);
?>