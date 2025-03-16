<?php

// Functions for database operations

// Function to get a single value
function getSingleValue($conn, $query, $param_type, $param_value) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($param_type, $param_value);
    $stmt->execute();
    $stmt->bind_result($value);
    $stmt->fetch();
    $stmt->close();
    return $value;
}

// Function to fetch a single row
function fetchSingleRow($conn, $query, $paramTypes, ...$params) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($paramTypes, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to fetch multiple rows
function fetchAllRows($conn, $query, $paramTypes, ...$params) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($paramTypes, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function for Insert, Update, and Delete queries
function executeQuery($conn, $query, $paramTypes, ...$params) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($paramTypes, ...$params);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>
