<?php
// database connection
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "final";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT BatteryLevel FROM ms ORDER BY ID DESC LIMIT 1";
$result = $conn->query($sql);
$response = array();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['BatteryLevel'] = $row["BatteryLevel"];
} else {
    $response['BatteryLevel'] = null;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
