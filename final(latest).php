<?php
$username = 'root';
$password = '';
$database = 'final';
$servername='localhost:3307';
$table = 'ms';
$mysqli = new mysqli($servername, $username, $password, $database);
$timestamp = array();
$voltage = array();
// Checking for connections
if ($mysqli->connect_error) {
    die('Connect Error (' .
    $mysqli->connect_errno . ') '.
    $mysqli->connect_error);
}

$sql = " SELECT * FROM $table ORDER BY ID DESC LIMIT 1";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

$sql1 = " SELECT Timestamp FROM $table ORDER BY ID DESC LIMIT 120";
$result1 = $mysqli->query($sql1);
if($result1){
    while($row1=$result1->fetch_assoc()){
        $timestamp[] = $row1['Timestamp'];
    }
}

$sql2 = " SELECT Voltage FROM $table ORDER BY ID DESC LIMIT 120";
$result2 = $mysqli->query($sql2);
if($result2){
    while($row2=$result2->fetch_assoc()){
        $Voltage[] = $row2['Voltage'];
    }
}

$sql3 = " SELECT Current FROM $table ORDER BY ID DESC LIMIT 120";
$result3 = $mysqli->query($sql3);
if($result3){
    while($row3=$result3->fetch_assoc()){
        $Current[] = $row3['Current'];
    }
}

$sql4 = " SELECT Power FROM $table ORDER BY ID DESC LIMIT 120";
$result4 = $mysqli->query($sql4);
if($result4){
    while($row4=$result4->fetch_assoc()){
        $Power[] = $row4['Power'];
    }
}

$sql5 = " SELECT Etotal FROM $table ORDER BY ID DESC LIMIT 120";
$result5 = $mysqli->query($sql5);
if($result5){
    while($row5=$result5->fetch_assoc()){
        $Etotal[] = $row5['Etotal'];
    }
}

$sql6 = " SELECT BatteryLevel FROM $table ORDER BY ID ASC LIMIT 1";
$result6 = $mysqli->query($sql6);
if($result6){
    while($row6=$result6->fetch_assoc()){
        $BatteryLevel[] = $row6['BatteryLevel'];
    }
}

$sql7 = " SELECT BatteryTemp FROM $table ORDER BY ID DESC LIMIT 120";
$result7 = $mysqli->query($sql7);
if($result7){
    while($row7=$result7->fetch_assoc()){
        $BatteryTemp[] = $row7['BatteryTemp'];
    }
}

$sql8 = " SELECT Eused FROM $table ORDER BY ID DESC LIMIT 120";
$result8 = $mysqli->query($sql8);
if($result8){
    while($row8=$result8->fetch_assoc()){
        $Eused[] = $row8['Eused'];
    }
}

$sql9 = " SELECT Pused FROM $table ORDER BY ID DESC LIMIT 120";
$result9 = $mysqli->query($sql9);
if($result9){
    while($row9=$result9->fetch_assoc()){
        $Pused[] = $row9['Pused'];
    }
}

$sql10 = " SELECT BoxTemp FROM $table ORDER BY ID DESC LIMIT 120";
$result10 = $mysqli->query($sql10);
if($result10){
    while($row10=$result10->fetch_assoc()){
        $BoxTemp[] = $row10['BoxTemp'];
    }
}

$sql11 = " SELECT lightIntensity FROM $table ORDER BY ID DESC LIMIT 120";
$result11 = $mysqli->query($sql11);
if($result11){
    while($row11=$result11->fetch_assoc()){
        $lightIntensity[] = $row11['lightIntensity'];
    }
}
$timestamp = array_reverse($timestamp);
$Voltage = array_reverse($Voltage);
$Current = array_reverse($Current);
$Power = array_reverse($Power);
$Etotal = array_reverse($Etotal);
$BatteryLevel = array_reverse($BatteryLevel);
$BatteryTemp = array_reverse($BatteryTemp);
$Eused = array_reverse($Eused);
$Pused = array_reverse($Pused);
$BoxTemp = array_reverse($BoxTemp);
$lightIntensity = array_reverse($lightIntensity);
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Dashboard - Floating Solar Panel Monitoring System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url(https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.min.css);
        @import url(https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css);
        *{
            padding: 0;
            margin: 0;
        }
        body{
            background-color: #EEF5FF;
            font-family: "Nunito", sans-serif;
            font-optical-sizing: auto;
        }
        .navbar{
            padding: 0px;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            position: fixed;
        }
        .navbar-brand{
            padding-left: 1%;
            color: white;
        }
        .navbar-content{
            padding-right: 1%;
            color: white;
        }
        .control-btn-title{
        margin-top: 10%;
        margin-bottom: 5%;
        }
        .control-btn{
            width: 100%;
        }
        .control-panel, .panel-overview, .panel-performance, .battery-performance, .inverter-performance{
            background-color: white;
            border: 2px #0bf solid;
        }
        .card-title{
            margin: 0;
            color: #387ADF;
        }
        .gauge-voltage, .gauge-current, .gauge-power, .gauge-energy, .gauge-BoxTemp, .gauge-lightInt{
            width: 100%;
            max-width: 250px;
            font-family: 'Roboto', sans-serif;
            font-size: 32px;
            color: #387ADF;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }
        .gauge-battery{
            width: 100%;
            max-width: 250px;
            font-family: 'Roboto', sans-serif;
            font-size: 32px;
            color: #C850C0;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
        }
        .gauge_body-voltage, .gauge_body-current, .gauge_body-power, .gauge_body-energy, .gauge_body-battery, .gauge_body-BoxTemp, .gauge_body-lightInt{
            width: 100%;
            height: 0;
            padding-bottom: 50%;
            background: #b4c0be;
            position: relative;
            border-top-left-radius: 100% 200%;
            border-top-right-radius: 100% 200%;
            overflow: hidden;
        }
        .gauge_fill-voltage{
            position: absolute;
            top: 100%;
            left: 0;
            width: inherit;
            height: 100%;
            background-color: #5BBCFF;
            transform-origin: center top;
        }
        .gauge_fill-current{
            position: absolute;
            top: 100%;
            left: 0;
            width: inherit;
            height: 100%;
            background-color: #F5DD61;
            transform-origin: center top;
        }
        .gauge_fill-power{
            position: absolute;
            top: 100%;
            left: 0;
            width: inherit;
            height: 100%;
            background-color: #FB88B4;
            transform-origin: center top;
        }
        .gauge_fill-energy{
            position: absolute;
            top: 100%;
            left: 0;
            width: inherit;
            height: 100%;
            background-color: #4158D0;
            transform-origin: center top;
        }
        .gauge_fill-BoxTemp{
            position: absolute;
            top: 100%;
            left: 0;
            width: inherit;
            height: 100%;
            background-color: #94FFD8;
            transform-origin: center top;
        }
        .gauge_fill-lightInt{
            position: absolute;
            top: 100%;
            left: 0;
            width: inherit;
            height: 100%;
            background-color: #FFBF78;
            transform-origin: center top;
        }
        .gauge_cover-voltage, .gauge_cover-current, .gauge_cover-power, .gauge_cover-energy, .gauge_cover-battery, .gauge_cover-BoxTemp, .gauge_cover-lightInt{
            width: 75%;
            height: 150%;
            background: white;
            position: absolute;
            border-radius: 50%;
            top: 25%;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding-bottom: 25%;
            box-sizing: border-box;
        }
        .p-battery{
            color: #387ADF;
        }
        .btn-on{
            background-color: #0d0;
        }
        .btn-off{
            background-color: #d00;
        }
        .battery-control-btn, .inverter-control-btn, .panel-control-btn, .fan-control-btn, .system-control-btn{
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 8px;
            color: white;
        }
        .battery{
            margin: 10px 0;
            display: flex;
            justify-content: center;
        }
        .batteryBox{
            height: 100px;
            width: 250px;
            border: 5px solid #3572EF;
            border-radius: 10px;
            position: relative;
        }
        .batteryBox::after{
            content: "";
            height: 10px;
            width: 20px;
            border: 5px solid #3572EF;
            position: relative;
            top: -58px;
            right: -100%;
        }
        .label{
            font-size: 1.2em;
            font-weight: 800;
            font-family: sans-serif;
            color: white;
            margin: 10px;
        }
        .indicator{
            height: 20px;
            width: 20px;
            border-radius: 50%;
            border: 1px solid white;
        }
        .charge{
            height: 100%;
            background: linear-gradient(27deg, blue, aqua);
            display: flex;
            justify-content: flex-start;
            align-items: center;
            font-size: 1.2em;
            font-weight: 800;
            font-family: sans-serif;
            color: white;
            position: relative;
            overflow: hidden;
            border-radius: 5px;
        }
        .charge::before{
            content: "";
            height: 100%;
            width: 100%;
            background: rgba(0, 0, 255, 0.2);
            position: absolute;
            animation: animate 5s linear infinite;
        }
        #chargeText{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            color: #050C9C;
        }
        @keyframes animate{
            0%{
                transform: translateX(-100%);
            }
            100%{
                transform: translateX(100%);
            }
        }
        .current-bt{
            color: #387ADF;
            text-align: center;
        }
        select{
            padding: 5px 15px;
            background-color: #C4E4FF;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <section id="navbar">
        <nav class="navbar bg-body-primary" style="margin: 0; padding: 0; width: 100%;">
            <div class="container-fluid" style="background-image: linear-gradient(to right, #525CEB, #5AB2FF); padding: 1.3% 0;">
                <a class="navbar-brand fs-5" href="final(latest).php"><b>Floating Solar Panel Monitoring System</b></a>
                <div class="navbar-content d-flex fs-5"><p style="margin-bottom: 0; margin-left: 10px;"><b>Administrative Dashboard</b></p></div>
            </div>
        </nav>
    </section>

    <section id="dashboard" style="margin-top: 100px;">
        <div class="container-fluid">
            <div class="row">
                <div class="first-panel col-lg-3 col-md-12">
                    <div class="panel-overview card" style="width: 100%; margin: 10px 0px;">
                        <div class="card card-cover h-100 overflow-hidden text-bg-dark shadow-lg" style="background-image: url('./Image/panel\ picture\ -\ Copy.png'); background-size: cover;">
                            <div class="d-flex flex-column h-100 p-4 pb-3 text-white text-shadow-1">
                                <h3 class="pt-5 mt-5 mb-3 display-6 lh-1 fw-bold"></h3>
                                <ul class="d-flex flex-column list-unstyled" style="margin: 10px 0px;">
                                    <li class="d-flex align-items-center me-3"><small>Total Panel :</small></li>
                                    <li class="d-flex align-items-center"><p class="fs-4 fw-bold" style="margin: 0;">1 Panel</p></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="control-panel card" style="width: 100%; margin: 10px 0px;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mx-4 mb-3">
                                <div><p class="fs-3 mb-0">Status</p></div>
                                <div style="background-color: #0bf; padding: 0px 2px; border-radius: 2px;"></div>
                                <div class="fs-3 mb-0" id="panel-status"></div>
                            </div>
                            <p class="card-title fs-3"><b>Control Panel</b><small> (Relay)</small></p>
                            <div class="row">
                                <div class="col-4">
                                    <p class="control-btn-title fs-5" style="color: #387ADF;">Battery</p>
                                    <button class="battery-control-btn btn-on" id="battery-control-btn" onclick="batteryButton()">ON</button>
                                </div>
                                <div class="col-4">
                                    <p class="control-btn-title fs-5" style="color: #387ADF;">Inverter</p>
                                    <button class="inverter-control-btn btn-on" id="inverter-control-btn" onclick="inverterButton()">ON</button>
                                </div>
                                <div class="col-4">
                                    <p class="control-btn-title fs-5" style="color: #387ADF;">Panel</p>
                                    <button class="panel-control-btn btn-on" id="panel-control-btn" onclick="panelButton()">ON</button>
                                </div>
                                <div class="col-4">
                                    <p class="control-btn-title fs-5" style="color: #387ADF;">Fan</p>
                                    <button class="fan-control-btn btn-on" id="fan-control-btn" onclick="fanButton()">ON</button>
                                </div>
                                <div class="col-4">
                                    <p class="control-btn-title fs-5" style="color: #387ADF;">System</p>
                                    <button class="system-control-btn btn-on" id="system-control-btn" onclick="systemButton()">ON</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="battery-performance card" style="width: 100%; margin: 10px 0px;">
                            <div class="card-body">
                                <p class="card-title fs-4"><b>Battery Performance</b></p>
                                <div class="row">
                                    <div class="col">
                                        <div><p class="p-battery fs-5 mt-2" style="margin: 0;"><bold>Battery Level</bold></p></div>
                                        <div class="battery" style="position: relative; top: 10%;">
                                            <div class="batteryBox">
                                                <div class="charge" id="chargeLevel"></div>
                                                <span id="chargeText" class="fs-5 fw-bold"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div><p class="p-battery fs-5 mt-4">Battery Temperature</p></div>
                                        <div id="batteryTemp-graph" style="margin-bottom: 10px">
                                            <div style="width: 100%; height: 15%;">
                                                <div><canvas id="batteryTempChart" style="max-height: 180px;"></canvas></div>
                                            </div>
                                        </div>
                                        <div><p class="current-bt fs-6 m-0">Current Temperature: <span class="bt-reading" id="bt-reading"></span> °C</p></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="inverter-performance card" style="width: 100%; margin: 0px 0px 10px 0px;">
                            <div class="card-body">
                                <div><p class="card-title fs-4 mb-3"><b>Inverter Performance</b></p></div>
                                <div class="d-flex custom-select justify-content-end">
                                    <div style="margin-right:10px;">
                                        <p style="color: #10439F; margin: auto; text-align: center;">Latest</p>
                                    </div>
                                    <div>
                                        <select id="tInverter-select">
                                            <option value="120">1 hour</option>
                                            <option value="240">2 hour</option>
                                            <option value="360">3 hour</option>
                                            <option value="480">4 hour</option>
                                            <option value="600">5 hour</option>
                                            <option value="720">6 hour</option>
                                            <option value="840">7 hour</option>
                                            <option value="960">8 hour</option>
                                            <option value="1080">9 hour</option>
                                            <option value="1200">10 hour</option>
                                            <option value="1320">11 hour</option>
                                            <option value="1440">12 hour</option>
                                            <option value="1560">13 hour</option>
                                            <option value="1680">14 hour</option>
                                            <option value="1800">15 hour</option>
                                            <option value="1920">16 hour</option>
                                            <option value="2040">17 hour</option>
                                            <option value="2160">18 hour</option>
                                            <option value="2280">19 hour</option>
                                            <option value="2400">20 hour</option>
                                            <option value="2520">21 hour</option>
                                            <option value="2640">22 hour</option>
                                            <option value="2760">23 hour</option>
                                            <option value="2880">24 hour</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="inverter-graph">
                                    <div style="width: 100%; height: 180px;">
                                        <div><canvas id="inverterChart" style="max-height: 180px;"></canvas></div>
                                    </div>
                                </div>
                                <div><p class="current-bt fs-6 m-0">Total Energy Consumption: <span class="Eused-reading" id="Eused-reading"></span> Wh</p></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="monitoring-panel col-lg-9 col-md-12" style="padding: 0;">
                    <div class="panel-performance card" style="width: 96%; margin: 10px 10px;">
                        <div class="card-body">
                            <p class="card-title fs-3"><b>Real-time Solar Panel Performance</b></p>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between" style="padding: 20px 0px 20px 0px;">
                                                <div><p class="card-title fs-4"><b>Voltage</b></p></div>
                                                <div class="d-flex custom-select" style="width: 200px;">
                                                    <div style="margin:auto;">
                                                        <p style="color: #10439F; margin: auto; text-align: center;">Latest</p>
                                                    </div>
                                                    <div>
                                                        <select id="tVoltage-select">
                                                            <option value="120">1 hour</option>
                                                            <option value="240">2 hour</option>
                                                            <option value="360">3 hour</option>
                                                            <option value="480">4 hour</option>
                                                            <option value="600">5 hour</option>
                                                            <option value="720">6 hour</option>
                                                            <option value="840">7 hour</option>
                                                            <option value="960">8 hour</option>
                                                            <option value="1080">9 hour</option>
                                                            <option value="1200">10 hour</option>
                                                            <option value="1320">11 hour</option>
                                                            <option value="1440">12 hour</option>
                                                            <option value="1560">13 hour</option>
                                                            <option value="1680">14 hour</option>
                                                            <option value="1800">15 hour</option>
                                                            <option value="1920">16 hour</option>
                                                            <option value="2040">17 hour</option>
                                                            <option value="2160">18 hour</option>
                                                            <option value="2280">19 hour</option>
                                                            <option value="2400">20 hour</option>
                                                            <option value="2520">21 hour</option>
                                                            <option value="2640">22 hour</option>
                                                            <option value="2760">23 hour</option>
                                                            <option value="2880">24 hour</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="gauge-voltage">
                                                <div class="gauge_body-voltage">
                                                    <div class="gauge_fill-voltage"></div>
                                                    <div class="gauge_cover-voltage fs-4" id="gauge_cover-voltage"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div id="voltage-graph" style="margin-bottom: 10px">
                                                <div style="width: 100%; height: 25%;">
                                                    <div><canvas id="voltageChart" style="max-height: 220px;"></canvas></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between" style="padding: 20px 0px 20px 0px;">
                                                <div><p class="card-title fs-4"><b>Current</b></p></div>
                                                <div class="d-flex custom-select" style="width: 200px;">
                                                    <div style="margin:auto;">
                                                        <p style="color: #10439F; margin: auto; text-align: center;">Latest</p>
                                                    </div>
                                                    <div>
                                                        <select id="tCurrent-select">
                                                            <option value="120">1 hour</option>
                                                            <option value="240">2 hour</option>
                                                            <option value="360">3 hour</option>
                                                            <option value="480">4 hour</option>
                                                            <option value="600">5 hour</option>
                                                            <option value="720">6 hour</option>
                                                            <option value="840">7 hour</option>
                                                            <option value="960">8 hour</option>
                                                            <option value="1080">9 hour</option>
                                                            <option value="1200">10 hour</option>
                                                            <option value="1320">11 hour</option>
                                                            <option value="1440">12 hour</option>
                                                            <option value="1560">13 hour</option>
                                                            <option value="1680">14 hour</option>
                                                            <option value="1800">15 hour</option>
                                                            <option value="1920">16 hour</option>
                                                            <option value="2040">17 hour</option>
                                                            <option value="2160">18 hour</option>
                                                            <option value="2280">19 hour</option>
                                                            <option value="2400">20 hour</option>
                                                            <option value="2520">21 hour</option>
                                                            <option value="2640">22 hour</option>
                                                            <option value="2760">23 hour</option>
                                                            <option value="2880">24 hour</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="gauge-current">
                                                <div class="gauge_body-current">
                                                    <div class="gauge_fill-current"></div>
                                                    <div class="gauge_cover-current fs-4" id="gauge_cover-current"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div id="current-graph" style="margin-bottom: 10px">
                                                <div style="width: 100%; height: 25%;">
                                                    <div><canvas id="currentChart" style="max-height: 220px;"></canvas></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between" style="padding: 20px 0px 20px 0px;">
                                                <div><p class="card-title fs-4"><b>Power</b></p></div>
                                                <div class="d-flex custom-select" style="width: 200px;">
                                                    <div style="margin:auto;">
                                                        <p style="color: #10439F; margin: auto; text-align: center;">Latest</p>
                                                    </div>
                                                    <div>
                                                        <select id="tPower-select">
                                                            <option value="120">1 hour</option>
                                                            <option value="240">2 hour</option>
                                                            <option value="360">3 hour</option>
                                                            <option value="480">4 hour</option>
                                                            <option value="600">5 hour</option>
                                                            <option value="720">6 hour</option>
                                                            <option value="840">7 hour</option>
                                                            <option value="960">8 hour</option>
                                                            <option value="1080">9 hour</option>
                                                            <option value="1200">10 hour</option>
                                                            <option value="1320">11 hour</option>
                                                            <option value="1440">12 hour</option>
                                                            <option value="1560">13 hour</option>
                                                            <option value="1680">14 hour</option>
                                                            <option value="1800">15 hour</option>
                                                            <option value="1920">16 hour</option>
                                                            <option value="2040">17 hour</option>
                                                            <option value="2160">18 hour</option>
                                                            <option value="2280">19 hour</option>
                                                            <option value="2400">20 hour</option>
                                                            <option value="2520">21 hour</option>
                                                            <option value="2640">22 hour</option>
                                                            <option value="2760">23 hour</option>
                                                            <option value="2880">24 hour</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="gauge-power">
                                                <div class="gauge_body-power">
                                                    <div class="gauge_fill-power"></div>
                                                    <div class="gauge_cover-power fs-4" id="gauge_cover-power"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div id="power-graph" style="margin-bottom: 10px">
                                                <div style="width: 100%; height: 25%;">
                                                    <div><canvas id="powerChart" style="max-height: 220px;"></canvas></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between" style="padding: 20px 0px 20px 0px;">
                                                <div><p class="card-title fs-4"><b>Energy</b></p></div>
                                                <div class="d-flex custom-select" style="width: 200px;">
                                                    <div style="margin:auto;">
                                                        <p style="color: #10439F; margin: auto; text-align: center;">Latest</p>
                                                    </div>
                                                    <div>
                                                        <select id="tEnergy-select">
                                                            <option value="120">1 hour</option>
                                                            <option value="240">2 hour</option>
                                                            <option value="360">3 hour</option>
                                                            <option value="480">4 hour</option>
                                                            <option value="600">5 hour</option>
                                                            <option value="720">6 hour</option>
                                                            <option value="840">7 hour</option>
                                                            <option value="960">8 hour</option>
                                                            <option value="1080">9 hour</option>
                                                            <option value="1200">10 hour</option>
                                                            <option value="1320">11 hour</option>
                                                            <option value="1440">12 hour</option>
                                                            <option value="1560">13 hour</option>
                                                            <option value="1680">14 hour</option>
                                                            <option value="1800">15 hour</option>
                                                            <option value="1920">16 hour</option>
                                                            <option value="2040">17 hour</option>
                                                            <option value="2160">18 hour</option>
                                                            <option value="2280">19 hour</option>
                                                            <option value="2400">20 hour</option>
                                                            <option value="2520">21 hour</option>
                                                            <option value="2640">22 hour</option>
                                                            <option value="2760">23 hour</option>
                                                            <option value="2880">24 hour</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="gauge-energy">
                                                <div class="gauge_body-energy">
                                                    <div class="gauge_fill-energy"></div>
                                                    <div class="gauge_cover-energy fs-4" id="gauge_cover-energy"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div id="energy-graph" style="margin-bottom: 10px">
                                                <div style="width: 100%; height: 25%;">
                                                    <div><canvas id="energyChart" style="max-height: 220px;"></canvas></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between" style="padding: 20px 0px 20px 0px;">
                                                <div><p class="card-title fs-4"><b>Control Box Temperature</b></p></div>
                                                <div class="d-flex custom-select" style="width: 200px;">
                                                    <div style="margin:auto;">
                                                        <p style="color: #10439F; margin: auto; text-align: center;">Latest</p>
                                                    </div>
                                                    <div>
                                                        <select id="tBoxTemp-select">
                                                            <option value="120">1 hour</option>
                                                            <option value="240">2 hour</option>
                                                            <option value="360">3 hour</option>
                                                            <option value="480">4 hour</option>
                                                            <option value="600">5 hour</option>
                                                            <option value="720">6 hour</option>
                                                            <option value="840">7 hour</option>
                                                            <option value="960">8 hour</option>
                                                            <option value="1080">9 hour</option>
                                                            <option value="1200">10 hour</option>
                                                            <option value="1320">11 hour</option>
                                                            <option value="1440">12 hour</option>
                                                            <option value="1560">13 hour</option>
                                                            <option value="1680">14 hour</option>
                                                            <option value="1800">15 hour</option>
                                                            <option value="1920">16 hour</option>
                                                            <option value="2040">17 hour</option>
                                                            <option value="2160">18 hour</option>
                                                            <option value="2280">19 hour</option>
                                                            <option value="2400">20 hour</option>
                                                            <option value="2520">21 hour</option>
                                                            <option value="2640">22 hour</option>
                                                            <option value="2760">23 hour</option>
                                                            <option value="2880">24 hour</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="gauge-BoxTemp">
                                                <div class="gauge_body-BoxTemp">
                                                    <div class="gauge_fill-BoxTemp"></div>
                                                    <div class="gauge_cover-BoxTemp fs-4" id="gauge_cover-BoxTemp"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div id="BoxTemp-graph" style="margin-bottom: 10px">
                                                <div style="width: 100%; height: 25%;">
                                                    <div><canvas id="BoxTempChart" style="max-height: 220px;"></canvas></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between" style="padding: 20px 0px 20px 0px;">
                                                <div><p class="card-title fs-4"><b>Light Intensity</b></p></div>
                                                <div class="d-flex custom-select" style="width: 200px;">
                                                    <div style="margin:auto;">
                                                        <p style="color: #10439F; margin: auto; text-align: center;">Latest</p>
                                                    </div>
                                                    <div>
                                                        <select id="tlightInt-select">
                                                            <option value="120">1 hour</option>
                                                            <option value="240">2 hour</option>
                                                            <option value="360">3 hour</option>
                                                            <option value="480">4 hour</option>
                                                            <option value="600">5 hour</option>
                                                            <option value="720">6 hour</option>
                                                            <option value="840">7 hour</option>
                                                            <option value="960">8 hour</option>
                                                            <option value="1080">9 hour</option>
                                                            <option value="1200">10 hour</option>
                                                            <option value="1320">11 hour</option>
                                                            <option value="1440">12 hour</option>
                                                            <option value="1560">13 hour</option>
                                                            <option value="1680">14 hour</option>
                                                            <option value="1800">15 hour</option>
                                                            <option value="1920">16 hour</option>
                                                            <option value="2040">17 hour</option>
                                                            <option value="2160">18 hour</option>
                                                            <option value="2280">19 hour</option>
                                                            <option value="2400">20 hour</option>
                                                            <option value="2520">21 hour</option>
                                                            <option value="2640">22 hour</option>
                                                            <option value="2760">23 hour</option>
                                                            <option value="2880">24 hour</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="gauge-lightInt">
                                                <div class="gauge_body-lightInt">
                                                    <div class="gauge_fill-lightInt"></div>
                                                    <div class="gauge_cover-lightInt fs-4" id="gauge_cover-lightInt"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div id="lightInt-graph" style="margin-bottom: 10px">
                                                <div style="width: 100%; height: 25%;">
                                                    <div><canvas id="lightIntChart" style="max-height: 220px;"></canvas></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>

    <script>
        //voltage
        function setGaugeValue_voltage(){
            const gauge = document.querySelector(".gauge-voltage");
            fetch('fetch_latestVoltage.php')
            .then(response => response.json())
            .then(data => {
                const value = `${data.Voltage}`;
                if(value < 0 || value > 21){
                    return;
                }
                gauge.querySelector(".gauge_fill-voltage").style.transform = `rotate(${value/42}turn)`;
                document.getElementById("gauge_cover-voltage").innerText = value + "V";
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload = setGaugeValue_voltage();

        //current
        function setGaugeValue_current(){
            const gauge = document.querySelector(".gauge-current");
            fetch('fetch_latestCurrent.php')
            .then(response => response.json())
            .then(data => {
                const value = `${data.Current}`;
                if(value < 0 || value > 21){
                    return;
                }
                gauge.querySelector(".gauge_fill-current").style.transform = `rotate(${value/42}turn)`;
                document.getElementById("gauge_cover-current").innerText = value + "A";
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload = setGaugeValue_current();

        //power
        function setGaugeValue_power(){
            const gauge = document.querySelector(".gauge-power");
            fetch('fetch_latestPower.php')
            .then(response => response.json())
            .then(data => {
                const value = `${data.Power}`;
                if(value < 0 || value > 20){
                    return;
                }
                gauge.querySelector(".gauge_fill-power").style.transform = `rotate(${value/40}turn)`;
                document.getElementById("gauge_cover-power").innerText = value + "W";
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload = setGaugeValue_power();

        //energy
        function setGaugeValue_energy(){
            const gauge = document.querySelector(".gauge-energy");
            fetch('fetch_latestEtotal.php')
            .then(response => response.json())
            .then(data => {
                const value = `${data.Etotal}`;
                if(value < 0 || value > 100){
                    return;
                }
                gauge.querySelector(".gauge_fill-energy").style.transform = `rotate(${value/200}turn)`;
                document.getElementById("gauge_cover-energy").innerText = value + "Wh";
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload = setGaugeValue_energy();

        //box temperature
        function setGaugeValue_BoxTemp(){
            const gauge = document.querySelector(".gauge-BoxTemp");
            fetch('fetch_latestBoxTemp.php')
            .then(response => response.json())
            .then(data => {
                const value = `${data.BoxTemp}`;
                if(value < 0 || value > 40){
                    return;
                }
                gauge.querySelector(".gauge_fill-BoxTemp").style.transform = `rotate(${value/80}turn)`;
                document.getElementById("gauge_cover-BoxTemp").innerText = value + "°C";
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload = setGaugeValue_BoxTemp();
        
        //light intensity
        function setGaugeValue_lightInt(){
            const gauge = document.querySelector(".gauge-lightInt");
            fetch('fetch_latestLightInt.php')
            .then(response => response.json())
            .then(data => {
                const value = `${data.lightIntensity}`;
                if(value < 0 || value > 100){
                    return;
                }
                gauge.querySelector(".gauge_fill-lightInt").style.transform = `rotate(${value/200}turn)`;
                document.getElementById("gauge_cover-lightInt").innerText = value + "%";
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload = setGaugeValue_lightInt();

        //current battery temperature
        function fetch_batteryTemp(){
            fetch('fetch_batteryTemp.php')
            .then(response => response.json())
            .then(data => {
                const output = `${data.BatteryTemp}`;
                document.getElementById("bt-reading").innerText = output;
                if (output < 15 || output > 35){
                    alert ("Hardware Malfunction Detected! Please Check the NTC Thermistor Sensor.");
                }
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload =fetch_batteryTemp();

        //current battery level
        function fetch_batteryLevel(){
            fetch('fetch_latestBatteryLevel.php')
            .then(response => response.json())
            .then(data => {
                const output = `${data.BatteryLevel}`;//0;
                document.getElementById("chargeLevel").style.width = output + "%";
                document.getElementById("chargeText").innerHTML = output + "%";
                if (output <= 10) {
                    alert ("Battery Level is too low! Switch to backup battery!");
                }
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload =fetch_batteryLevel();

        //current total energy comsumption
        function fetch_Eused(){
            fetch('fetch_latestEused.php')
            .then(response => response.json())
            .then(data => {
                const output = `${data.Eused}`;
                document.getElementById("Eused-reading").innerText = output;
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload =fetch_Eused();
        
        //battery button
        function batteryButton(){
            var button = document.getElementById("battery-control-btn");
            if(button.classList.contains("btn-on")){
                button.classList.remove("btn-on");
                button.classList.add("btn-off");
                button.textContent = "OFF";
                localStorage.setItem("batteryStatus", "0");
            }
            else{
                button.classList.remove("btn-off");
                button.classList.add("btn-on");
                button.textContent = "ON";
                localStorage.setItem("batteryStatus", "1");
            }
            updatePanelStatus();
        }
        //inverter button
        function inverterButton(){
            var button = document.getElementById("inverter-control-btn");
            if(button.classList.contains("btn-on")){
                button.classList.remove("btn-on");
                button.classList.add("btn-off");
                button.textContent = "OFF";
                localStorage.setItem("inverterStatus", "0");
            }
            else{
                button.classList.remove("btn-off");
                button.classList.add("btn-on");
                button.textContent = "ON";
                localStorage.setItem("inverterStatus", "1");
            }
        }
        //panel button
        function panelButton(){
            var button = document.getElementById("panel-control-btn");
            if(button.classList.contains("btn-on")){
                button.classList.remove("btn-on");
                button.classList.add("btn-off");
                button.textContent = "OFF";
                localStorage.setItem("panelStatus", "0");
            }
            else{
                button.classList.remove("btn-off");
                button.classList.add("btn-on");
                button.textContent = "ON";
                localStorage.setItem("panelStatus", "1");
            }
            updatePanelStatus();
        }
        //fan button
        function fanButton(){
            var button = document.getElementById("fan-control-btn");
            if(button.classList.contains("btn-on")){
                button.classList.remove("btn-on");
                button.classList.add("btn-off");
                button.textContent = "OFF";
                localStorage.setItem("fanStatus", "0");
            }
            else{
                button.classList.remove("btn-off");
                button.classList.add("btn-on");
                button.textContent = "ON";
                localStorage.setItem("fanStatus", "1");
            }
        }
        //system button
        function systemButton(){
            var button = document.getElementById("system-control-btn");
            if(button.classList.contains("btn-on")){
                button.classList.remove("btn-on");
                button.classList.add("btn-off");
                button.textContent = "OFF";
                localStorage.setItem("systemStatus", "0");
            }
            else{
                button.classList.remove("btn-off");
                button.classList.add("btn-on");
                button.textContent = "ON";
                localStorage.setItem("systemStatus", "1");
            }
            updatePanelStatus();
        }

        function initializeButtonState() {
            var batteryBtn = document.getElementById("battery-control-btn");
            var batteryStatus = localStorage.getItem("batteryStatus");
            if (batteryStatus === "1") {
                batteryBtn.classList.remove("btn-off");
                batteryBtn.classList.add("btn-on");
                batteryBtn.textContent = "ON";
            } else {
                batteryBtn.classList.remove("btn-on");
                batteryBtn.classList.add("btn-off");
                batteryBtn.textContent = "OFF";
            }

            var inverterBtn = document.getElementById("inverter-control-btn");
            var inverterStatus = localStorage.getItem("inverterStatus");
            if (inverterStatus === "1") {
                inverterBtn.classList.remove("btn-off");
                inverterBtn.classList.add("btn-on");
                inverterBtn.textContent = "ON";
            } else {
                inverterBtn.classList.remove("btn-on");
                inverterBtn.classList.add("btn-off");
                inverterBtn.textContent = "OFF";
            }

            var panelBtn = document.getElementById("panel-control-btn");
            var panelStatus = localStorage.getItem("panelStatus");
            if (panelStatus === "1") {
                panelBtn.classList.remove("btn-off");
                panelBtn.classList.add("btn-on");
                panelBtn.textContent = "ON";
            } else {
                panelBtn.classList.remove("btn-on");
                panelBtn.classList.add("btn-off");
                panelBtn.textContent = "OFF";
            }

            var fanBtn = document.getElementById("fan-control-btn");
            var fanStatus = localStorage.getItem("fanStatus");
            if (fanStatus === "1") {
                fanBtn.classList.remove("btn-off");
                fanBtn.classList.add("btn-on");
                fanBtn.textContent = "ON";
            } else {
                fanBtn.classList.remove("btn-on");
                fanBtn.classList.add("btn-off");
                fanBtn.textContent = "OFF";
            }

            var systemBtn = document.getElementById("system-control-btn");
            var systemBtn = localStorage.getItem("systemStatus");
            if (systemBtn === "1") {
                systemBtn.classList.remove("btn-off");
                systemBtn.classList.add("btn-on");
                systemBtn.textContent = "ON";
            } else {
                systemBtn.classList.remove("btn-on");
                systemBtn.classList.add("btn-off");
                systemBtn.textContent = "OFF";
            }
        }
        document.addEventListener("DOMContentLoaded", initializeButtonState);

        function updatePanelStatus(){
            var batteryStatus = localStorage.getItem("batteryStatus");
            var panelStatus = localStorage.getItem("panelStatus");
            var systemStatus = localStorage.getItem("systemStatus");
            if(batteryStatus != 1 && panelStatus == 1 && systemStatus == 1) { document.getElementById("panel-status").textContent = "Active"; }
            else { document.getElementById("panel-status").textContent = "Inactive"; }
        }
        document.addEventListener("DOMContentLoaded", updatePanelStatus);

        //AJAX to control Arduino NANO
        $(document).ready(function(){
            $('#battery-control-btn').click(function(){
                $.get("fyp.php", {command: "a"});
            });

            $('#inverter-control-btn').click(function(){
                $.get("fyp.php", {command: "b"});
            });

            $('#panel-control-btn').click(function(){
                $.get("fyp.php", {command: "c"});
            });

            $('#fan-control-btn').click(function(){
                $.get("fyp.php", {command: "d"});
            });

            $('#system-control-btn').click(function(){
                $.get("fyp.php", {command: "e"});
            });
        })

        //inverter graph
        const timestamps = <?php echo json_encode($timestamp); ?>;
        const InvPower = <?php echo json_encode($Pused); ?>; //remember to change
        const ctxInv = document.getElementById('inverterChart');
        const chartInv = new Chart(ctxInv, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    data: InvPower,
                    borderWidth: 1,
                    borderColor: '#0C359E',
                    cubicInterpolationMode: 'monotone',
                    pointRadius: 0.5
                }]
            },
            options: {
                plugins: {
                    legend:{
                        display: false,
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 10,
                        min: 0,
                        ticks: {
                            stepSize: 2
                        }
                    }
                },
            }
            });
        function updateInverter(){
            fetch('fetch_PusedGraph.php')
                .then(response => response.json())
                .then(data => {
                    // Add the new data
                    data.forEach(item => {
                        timestamps.push(item.x);
                        InvPower.push(item.y);
                    });
                    // If the data exceeds the selected timeframe, remove the oldest points
                    while (timestamps.length > timeframe) {
                        timestamps.shift();
                        InvPower.shift();
                    }
                    // Update the chart
                    chartInv.data.labels = timestamps;
                    chartInv.data.datasets[0].data = InvPower;
                    chartInv.update('none');
                })
                .catch(error => console.error('Error fetching data:', error));
            }
        function updateInverterSelection(){
            const tInv = parseInt(document.getElementById("tInverter-select").value);
            // If the data exceeds the selected timeframe, remove the oldest points
            const newTimestamps = timestamps.slice(-tInv);
            const newInv = InvPower.slice(-tInv);
            // Update the chart
            chartInv.data.labels = newTimestamps;
            chartInv.data.datasets[0].data = newInv;
            chartInv.update('none');
            updateInverter();
        }
        document.getElementById("tInverter-select").addEventListener("change", updateInverterSelection);

        //battery temperature graph
        const batteryTemp = <?php echo json_encode($BatteryTemp); ?>;
        const ctxbt = document.getElementById('batteryTempChart');
        const chartbt = new Chart(ctxbt, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    data: batteryTemp,
                    borderWidth: 1,
                    borderColor: '#0C359E',
                    cubicInterpolationMode: 'monotone',
                    pointRadius: 0.5
                }]
            },
            options: {
                plugins: {
                    legend:{
                        display: false,
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 50,
                    }
                },
            }
            });
        function updateBatteryTemp(){
            const tBt = 120;
            const newTimestamps = timestamps.slice(-tBt);
            const newBt = batteryTemp.slice(-tBt);
            chartbt.data.labels = newTimestamps;
            chartbt.data.datasets[0].data = newBt;
            chartbt.update('none');
            fetch('fetch_batteryTempGraph.php')
                .then(response => response.json())
                .then(data => {
                    // Add the new data
                    data.forEach(item => {
                        timestamps.push(item.x);
                        batteryTemp.push(item.y);
                    });
                    // If the data exceeds the selected timeframe, remove the oldest points
                    while (timestamps.length > timeframe) {
                        timestamps.shift();
                        batteryTemp.shift();
                    }
                    // Update the chart
                    chartbt.data.labels = timestamps;
                    chartbt.data.datasets[0].data = batteryTemp;
                    chartbt.update('none');
                })
                .catch(error => console.error('Error fetching data:', error));
            }

        //voltage graph
        const voltage = <?php echo json_encode($Voltage); ?>;
        const ctxVoltage = document.getElementById('voltageChart');
        const chartVoltage = new Chart(ctxVoltage, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    data: voltage,
                    borderWidth: 1,
                    borderColor: '#0C359E',
                    cubicInterpolationMode: 'monotone',
                    pointRadius: 0.5
                }]
            },
            options: {
                plugins: {
                    legend:{
                        display: false,
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 25,
                    }
                },
            }
            });
        function updateVoltage(){
            fetch('fetch_voltageGraph.php')
                .then(response => response.json())
                .then(data => {
                    // Add the new data
                    data.forEach(item => {
                        timestamps.push(item.x);
                        voltage.push(item.y);
                    });
                    // If the data exceeds the selected timeframe, remove the oldest points
                    while (timestamps.length > timeframe) {
                        timestamps.shift();
                        voltage.shift();
                    }
                    // Update the chart
                    chartVoltage.data.labels = timestamps;
                    chartVoltage.data.datasets[0].data = voltage;
                    chartVoltage.update('none');
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        function updateVoltageSelection(){
            const tVoltage = parseInt(document.getElementById("tVoltage-select").value);
            // If the data exceeds the selected timeframe, remove the oldest points
            const newTimestamps = timestamps.slice(-tVoltage);
            const newVoltage = voltage.slice(-tVoltage);
            // Update the chart
            chartVoltage.data.labels = newTimestamps;
            chartVoltage.data.datasets[0].data = newVoltage;
            chartVoltage.update('none');
            updateVoltage();
        }
        document.getElementById("tVoltage-select").addEventListener("change", updateVoltageSelection);

        //current graph
        const current = <?php echo json_encode($Current); ?>;
        const ctxCurrent = document.getElementById('currentChart');
        const chartCurrent = new Chart(ctxCurrent, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    data: current,
                    borderWidth: 1,
                    borderColor: '#0C359E',
                    cubicInterpolationMode: 'monotone',
                    pointRadius: 0.5
                }]
            },
            options: {
                plugins: {
                    legend:{
                        display: false,
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 1.5,
                        ticks: {
                            stepSize: 0.3
                        }
                    }
                },
            }
            });
        function updateCurrent(){
            fetch('fetch_currentGraph.php')
                .then(response => response.json())
                .then(data => {
                    // Add the new data
                    data.forEach(item => {
                        timestamps.push(item.x);
                        current.push(item.y);
                    });
                    // If the data exceeds the selected timeframe, remove the oldest points
                    while (timestamps.length > timeframe) {
                        timestamps.shift();
                        current.shift();
                    }
                    // Update the chart
                    chartCurrent.data.labels = timestamps;
                    chartCurrent.data.datasets[0].data = current;
                    chartCurrent.update('none');
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        function updateCurrentSelection(){
            const tCurrent = parseInt(document.getElementById("tCurrent-select").value);
            // If the data exceeds the selected timeframe, remove the oldest points
            const newTimestamps = timestamps.slice(-tCurrent);
            const newCurrent = current.slice(-tCurrent);
            // Update the chart
            chartCurrent.data.labels = newTimestamps;
            chartCurrent.data.datasets[0].data = newCurrent;
            chartCurrent.update('none');
            updateCurrent();
        }
        document.getElementById("tCurrent-select").addEventListener("change", updateCurrentSelection);

        //power graph
        const power = <?php echo json_encode($Power); ?>;
        const ctxPower = document.getElementById('powerChart');
        const chartPower = new Chart(ctxPower, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    data: power,
                    borderWidth: 1,
                    borderColor: '#0C359E',
                    cubicInterpolationMode: 'monotone',
                    pointRadius: 0.5
                }]
            },
            options: {
                plugins: {
                    legend:{
                        display: false,
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 20,
                        ticks: {
                            stepSize: 5
                        }
                    }
                },
            }
            });
        function updatePower(){
            fetch('fetch_powerGraph.php')
                .then(response => response.json())
                .then(data => {
                    // Add the new data
                    data.forEach(item => {
                        timestamps.push(item.x);
                        power.push(item.y);
                    });
                    // If the data exceeds the selected timeframe, remove the oldest points
                    while (timestamps.length > timeframe) {
                        timestamps.shift();
                        power.shift();
                    }
                    // Update the chart
                    chartPower.data.labels = timestamps;
                    chartPower.data.datasets[0].data = power;
                    chartPower.update('none');
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        function updatePowerSelection(){
            const tPower = parseInt(document.getElementById("tPower-select").value);
            // If the data exceeds the selected timeframe, remove the oldest points
            const newTimestamps = timestamps.slice(-tPower);
            const newPower = power.slice(-tPower);
            // Update the chart.......
            chartPower.data.labels = newTimestamps;
            chartPower.data.datasets[0].data = newPower;
            chartPower.update('none');
            updatePower();
        }
        document.getElementById("tPower-select").addEventListener("change", updatePowerSelection);

        //energy graph
        const energy = <?php echo json_encode($Etotal); ?>;
        const ctxEnergy = document.getElementById('energyChart');
        const chartEnergy = new Chart(ctxEnergy, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    data: energy,
                    borderWidth: 1,
                    borderColor: '#0C359E',
                    cubicInterpolationMode: 'monotone',
                    pointRadius: 0.5
                }]
            },
            options: {
                plugins: {
                    legend:{
                        display: false,
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20
                        }
                    }
                },
            }
            });
        function updateEnergy(){
            fetch('fetch_energyGraph.php')
                .then(response => response.json())
                .then(data => {
                    // Add the new data
                    data.forEach(item => {
                        timestamps.push(item.x);
                        energy.push(item.y);
                    });
                    // If the data exceeds the selected timeframe, remove the oldest points
                    while (timestamps.length > timeframe) {
                        timestamps.shift();
                        energy.shift();
                    }
                    // Update the chart
                    chartEnergy.data.labels = timestamps;
                    chartEnergy.data.datasets[0].data = energy;
                    chartEnergy.update('none');
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        function updateEnergySelection(){
            const tEnergy = parseInt(document.getElementById("tEnergy-select").value);
            // If the data exceeds the selected timeframe, remove the oldest points
            const newTimestamps = timestamps.slice(-tEnergy);
            const newEnergy = energy.slice(-tEnergy);
            // Update the chart
            chartEnergy.data.labels = newTimestamps;
            chartEnergy.data.datasets[0].data = newEnergy;
            chartEnergy.update('none');
            updateEnergy();
        }
        document.getElementById("tEnergy-select").addEventListener("change", updateEnergySelection);

        //box temperature graph
        const BoxTemp = <?php echo json_encode($BoxTemp); ?>;
        const ctxBoxTemp = document.getElementById('BoxTempChart');
        const chartBoxTemp = new Chart(ctxBoxTemp, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    data: BoxTemp,
                    borderWidth: 1,
                    borderColor: '#0C359E',
                    cubicInterpolationMode: 'monotone',
                    pointRadius: 0.5
                }]
            },
            options: {
                plugins: {
                    legend:{
                        display: false,
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 50,
                        ticks: {
                            stepSize: 10
                        }
                    }
                },
            }
            });
        function updateBoxTemp(){
            fetch('fetch_BoxTempGraph.php')
                .then(response => response.json())
                .then(data => {
                    // Add the new data
                    data.forEach(item => {
                        timestamps.push(item.x);
                        BoxTemp.push(item.y);
                    });
                    // If the data exceeds the selected timeframe, remove the oldest points
                    while (timestamps.length > timeframe) {
                        timestamps.shift();
                        BoxTemp.shift();
                    }
                    // Update the chart
                    chartBoxTemp.data.labels = timestamps;
                    chartBoxTemp.data.datasets[0].data = BoxTemp;
                    chartBoxTemp.update('none');
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        function updateBoxTempSelection(){
            const tBoxTemp = parseInt(document.getElementById("tBoxTemp-select").value);
            // If the data exceeds the selected timeframe, remove the oldest points
            const newTimestamps = timestamps.slice(-tBoxTemp);
            const newBoxTemp = BoxTemp.slice(-tBoxTemp);
            // Update the chart
            chartBoxTemp.data.labels = newTimestamps;
            chartBoxTemp.data.datasets[0].data = newBoxTemp;
            chartBoxTemp.update('none');
            updateBoxTemp();
        }
        document.getElementById("tBoxTemp-select").addEventListener("change", updateBoxTempSelection);

        //light intensity graph
        const lightInt = <?php echo json_encode($lightIntensity); ?>;
        const ctxlightInt = document.getElementById('lightIntChart');
        const chartlightInt = new Chart(ctxlightInt, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    data: lightInt,
                    borderWidth: 1,
                    borderColor: '#0C359E',
                    cubicInterpolationMode: 'monotone',
                    pointRadius: 0.5
                }]
            },
            options: {
                plugins: {
                    legend:{
                        display: false,
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20
                        }
                    }
                },
            }
            });
        function updatelightInt(){
            fetch('fetch_lightIntGraph.php')
                .then(response => response.json())
                .then(data => {
                    // Add the new data
                    data.forEach(item => {
                        timestamps.push(item.x);
                        lightInt.push(item.y);
                    });
                    // If the data exceeds the selected timeframe, remove the oldest points
                    while (timestamps.length > timeframe) {
                        timestamps.shift();
                        lightInt.shift();
                    }
                    // Update the chart
                    chartlightInt.data.labels = timestamps;
                    chartlightInt.data.datasets[0].data = lightInt;
                    chartlightInt.update('none');
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        function updateLightIntSelection(){
            const tlightInt = parseInt(document.getElementById("tlightInt-select").value);
            // If the data exceeds the selected timeframe, remove the oldest points
            const newTimestamps = timestamps.slice(-tlightInt);
            const newlightInt = lightInt.slice(-tlightInt);
            // Update the chart
            chartlightInt.data.labels = newTimestamps;
            chartlightInt.data.datasets[0].data = newlightInt;
            chartlightInt.update('none');
            updatelightInt();
        }
        document.getElementById("tlightInt-select").addEventListener("change", updateLightIntSelection);

        setInterval(function() {
            var d = new Date(); // current date
            var h = d.getHours();
            var m = d.getMinutes();
            var s = d.getSeconds();
            console.log(h, m, s); // Log the current time for debugging
            if (h == 0 && m == 0 && s == 0) {
                $.get("fyp.php", {command: "f"});
            }
        }, 1000);

        let updateTime = 30000;
        setInterval(updateVoltageSelection, updateTime);
        setInterval(updateCurrentSelection, updateTime);
        setInterval(updatePowerSelection, updateTime);
        setInterval(updateEnergySelection, updateTime);
        setInterval(updateBoxTempSelection, updateTime);
        setInterval(setGaugeValue_BoxTemp, updateTime);
        setInterval(updateLightIntSelection, updateTime);
        setInterval(updateBatteryTemp, updateTime);
        setInterval(updateInverterSelection, updateTime);
        setInterval(fetch_batteryTemp, updateTime);
        setInterval(fetch_batteryLevel, updateTime);
        setInterval(fetch_Eused, updateTime);
        setInterval(setGaugeValue_energy, updateTime);
        setInterval(setGaugeValue_power, updateTime);
        setInterval(setGaugeValue_current, updateTime);
        setInterval(setGaugeValue_voltage, updateTime);
        setInterval(setGaugeValue_lightInt, updateTime);
    </script>
</body>
</html>