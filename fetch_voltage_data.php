<?php
$username = 'root';
$password = '';
$database = 'fyp 20th measurement';
$servername='localhost:3307';
$mysqli = new mysqli($servername, $username, $password, $database);

if ($mysqli->connect_error) {
    die('Connect Error (' .
    $mysqli->connect_errno . ') '.
    $mysqli->connect_error);
}

$timeframe = isset($_GET['timeframe']) ? (int)$_GET['timeframe'] : 120;
$timestamp = array();
$voltage = array();

$sql1 = "SELECT Timestamp FROM traditional_set1 ORDER BY ID DESC LIMIT $timeframe";
$result1 = $mysqli->query($sql1);
if($result1){
    while($row1 = $result1->fetch_assoc()){
        $timestamp[] = $row1['Timestamp'];
    }
}

$sql2 = "SELECT Voltage FROM traditional_set1 ORDER BY ID DESC LIMIT $timeframe";
$result2 = $mysqli->query($sql2);
if($result2){
    while($row2 = $result2->fetch_assoc()){
        $voltage[] = $row2['Voltage'];
    }
}
$timestamp = array_reverse($timestamp);
$voltage = array_reverse($voltage);
$mysqli->close();
echo json_encode(['timestamps' => $timestamp, 'voltage' => $voltage]);
?>
