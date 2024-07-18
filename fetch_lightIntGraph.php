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

// query to retrieve data
$sql = "SELECT Timestamp, lightIntensity FROM ms";
$result = $conn->query($sql);

$dataPoints = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dataPoints[] = array("x" => $row["Timestamp"], "y" => $row["lightIntensity"]);
    }
}

$conn->close();

// output data in JSON format
echo json_encode($dataPoints);
?>