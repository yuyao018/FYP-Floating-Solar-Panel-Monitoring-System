<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "final";
$port = "3307";
$esp_ip = "192.168.97.15";   //ESP8266-01S IP Address
$esp_port = "80";          //Default HTTP port

if(isset($_GET["pv"]) && isset($_GET["pi"]) && isset($_GET["bl"]) && isset($_GET["li"]) && isset($_GET["at"]) && isset($_GET["bt"]) && isset($_GET["p"]) && isset($_GET["pu"]) && isset($_GET["ed"]) && isset($_GET["et"]) && isset($_GET["eu"])){
    $pv = $_GET["pv"];
    $pi = $_GET["pi"];
    $bl = $_GET["bl"];
    $li = $_GET["li"];
    $at = $_GET["at"];
    $bt = $_GET["bt"];
    $p = $_GET["p"];
    $pu = $_GET["pu"];
    $ed = $_GET["ed"];
    $et = $_GET["et"];
    $eu = $_GET["eu"];
    //create connection to MySQL
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //check connection
    if($conn -> connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    else{
        echo "MySQL successfully connected\n";
    }

    //Prepare and bind sql statement for traditional table
    $sql1 = $conn -> prepare("insert into ms(Voltage, Current, Power, Pused, Etoday, Etotal, Eused, BoxTemp, lightIntensity, BatteryLevel, BatteryTemp) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql1 -> bind_param("ddddddddddd",$pv, $pi, $p, $pu, $ed, $et, $eu, $at, $li, $bl, $bt);
    if($sql1->execute()){
        echo "New record inserted successfully into table floating\n";
    }else{
        echo "Error: " . $sql1 . "<br>" .$conn->error;
    }
    $sql1->close();
    $conn->close();
}
else if(isset($_GET['command'])){
    $command = $_GET['command'];
    echo "Received command: $command";
    $url = "http://$esp_ip:$esp_port/?command=$command";//Send command to ESP8266-01s
    file_get_contents($url);
}

/*if(isset($_GET["br"])){
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if($conn -> connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    else{
        echo "MySQL successfully connected\n";
    }
    $sql = "select BatteryLevel from ms order by ID DESC limit 1";
    $result = $conn->query($sql);
    $response = array();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['BatteryLevel'] = $row["BatteryLevel"];
    } else {
        $response['BatteryLevel'] = null;
    }
    $conn->close();
    $json_response = json_encode($response);
    $url = "http://$esp_ip:$esp_port/?battInit=$json_response"; //Send data retrieved from MySQL to ESP8266-01s
    file_get_contents($url);
}*/
?>
