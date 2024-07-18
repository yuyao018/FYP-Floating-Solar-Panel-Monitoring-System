<?php
$username = 'root';
$password = '';
$database = 'final';
$servername='localhost:3307';
$table = "ms";
$table2 = "statistic";
$mysqli = new mysqli($servername, $username, $password, $database);
$timestamp = array();
$voltage = array();
// Checking for connections
if ($mysqli->connect_error) {
    die('Connect Error (' .
    $mysqli->connect_errno . ') '.
    $mysqli->connect_error);
}

$sql1 = " SELECT Timestamp FROM $table ORDER BY ID DESC LIMIT 120";
$result1 = $mysqli->query($sql1);
if($result1){
    while($row1=$result1->fetch_assoc()){
        $timestamp[] = $row1['Timestamp'];
    }
}

$sql2 = " SELECT energy_per_day FROM $table2 ORDER BY ID DESC LIMIT 7";
$result2 = $mysqli->query($sql2);
if($result2){
    while($row2=$result2->fetch_assoc()){
        $dailyEnergy[] = $row2['energy_per_day'];
    }
}
$sql3 = " SELECT Day FROM $table2 ORDER BY ID DESC LIMIT 7";
$result3 = $mysqli->query($sql3);
if($result3){
    while($row3=$result3->fetch_assoc()){
        $day[] = $row3['Day'];
    }
}

$sql4 = " SELECT Power FROM $table ORDER BY ID DESC LIMIT 1";
$result4 = $mysqli->query($sql4);
if($result4){
    while($row4=$result4->fetch_assoc()){
        $Power[] = $row4['Power'];
    }
}

$sql5 = " SELECT Etotal FROM $table ORDER BY ID DESC LIMIT 1";
$result5 = $mysqli->query($sql5);
if($result5){
    while($row5=$result5->fetch_assoc()){
        $Etotal[] = $row5['Etotal'];
    }
}

$sql6 = " SELECT BatteryLevel FROM $table ORDER BY ID DESC LIMIT 1";
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

$sql8 = " SELECT Etoday FROM $table ORDER BY ID DESC LIMIT 1";
$result8 = $mysqli->query($sql8);
if($result8){
    while($row8=$result8->fetch_assoc()){
        $Etoday[] = $row8['Etoday'];
    }
}

$sql9 = " SELECT Eused FROM $table ORDER BY ID DESC LIMIT 120";
$result9 = $mysqli->query($sql9);
if($result9){
    while($row9=$result9->fetch_assoc()){
        $Eused[] = $row9['Eused'];
    }
}

$sql10 = " SELECT Pused FROM $table ORDER BY ID DESC LIMIT 120";
$result10 = $mysqli->query($sql10);
if($result10){
    while($row10=$result10->fetch_assoc()){
        $Pused[] = $row10['Pused'];
    }
}

$dailyEnergy = array_reverse($dailyEnergy);
$day = array_reverse($day);
$timestamp = array_reverse($timestamp);
$BatteryLevel = array_reverse($BatteryLevel);
$BatteryTemp = array_reverse($BatteryTemp);
$Eused = array_reverse($Eused);
$Pused = array_reverse($Pused);
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Floating Solar Panel Monitoring System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        .panel-overview, .control-panel, .panel-performance, .battery-performance, .earning,.inverter-performance{
            background-color: white;
            border: 2px #0bf solid;
        }
        .btn-on{
            background-color: #0d0;
        }
        .btn-off{
            background-color: #d00;
        }
        .battery-control-btn, .panel-control-btn{
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 8px;
            color: white;
        }
        .card-title{
            margin: 0;
            color: #387ADF;
        }
        select{
            padding: 5px 15px;
            background-color: #C4E4FF;
            border-radius: 5px;
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
    </style>
</head>
<body>
    <section id="navbar">
        <nav class="navbar bg-body-primary" style="margin: 0; padding: 0; width: 100%;">
            <div class="container-fluid" style="background-image: linear-gradient(to right, #525CEB, #5AB2FF); padding: 1.3% 0;">
                <a class="navbar-brand fs-5"><b>Floating Solar Panel Monitoring System</b></a>
                <div class="navbar-content d-flex fs-6"><p style="margin-bottom: 0;"><b>Dashboard</b></p></div>
            </div>
        </nav>
    </section>

    <section id="dashboard" style="margin-top: 100px;">
        <div class="container-fluid">
            <div class="row">
                <div class="first-panel col-lg-3 col-md-12">
                    <div class="panel-overview card" style="width: 100%; margin: 10px auto;">
                        <div class="card card-cover h-100 overflow-hidden text-bg-dark shadow-lg" style="background-image: url('./Image/panel\ picture\ -\ Copy.png'); background-size: cover;">
                            <div class="d-flex flex-column h-100 p-4 pb-3 text-white text-shadow-1">
                                <h3 class="pt-5 mt-5 mb-3 display-6 lh-1 fw-bold"></h3>
                                <ul class="d-flex flex-column list-unstyled" style="margin: 5px 0px;">
                                    <li class="d-flex align-items-center me-3">
                                        <small>Total Panel :</small>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <p class="fs-4" style="margin: 0;">1 Panel</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="inverter-performance card" style="width: 100%; margin: 0px 0px 10px 0px;">
                            <div class="card-body">
                                <div><p class="card-title fs-4 mb-3"><b>Power Consumption</b></p></div>
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
                <div class="monitoring-panel col-lg-6 col-md-12">
                    <div class="panel-performance card" style="width: 100%; margin: 10px auto;">
                        <div class="card-body pb-0">
                            <div class="row mb-2">
                                <div class="col-md-6 col-sm-12">
                                    <div class="card mb-2" style="width: 100%; height: 100px;">
                                        <div class="card-body p-2">
                                            <p class="card-title fs-5"><b>Status</b></p>
                                            <div class="d-flex">
                                                <div id="panel-status" class="fs-2" style="padding-left: 5px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="card" style="width: 100%; height: 100px;">
                                        <div class="card-body p-2">
                                            <p class="card-title fs-5"><b>Total Earning</b></p>
                                            <div class="d-flex">
                                                <div><p class="fs-2">RM</p></div>
                                                <div id="earning-reading" class="fs-2" style="padding-left: 5px;">0.00</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="card-title fs-4 mb-2"><b>Real-time Solar Panel Performance</b></p>
                            <div class="row">
                                <div class="col-md-4 col-sm-12" style="padding-right: 0;">
                                    <div class="card mb-2" style="width: 100%; height: 92px;">
                                        <div class="card-body p-2">
                                            <p class="card-title fs-6 power-title">Power</p>
                                            <div class="d-flex justify-content-start my-2">
                                                <div><i class="fa-solid fa-bolt-lightning fa-xl" style="color: #FFD43B; padding: 18px 0px 5px 10px;"></i></div>
                                                <div id="latestPower" class="fs-5" style="margin-left: 10px; padding: 2px 0px 0px 5px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12" style="padding-right: 0;">
                                    <div class="card mb-2" style="width: 100%; height: 92px;">
                                        <div class="card-body p-1">
                                            <P class="card-title fs-6">Today Energy <small>Generated</small></P>
                                            <div class="d-flex justify-content-start my-2">
                                                <div><i class="fa-solid fa-bolt-lightning fa-xl" style="color: #FFD43B; padding: 18px 0px 5px 10px;"></i></div>
                                                <div id="latestDailyEnergy" class="fs-5" style="margin-left: 10px; padding: 2px 0px 0px 5px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="card mb-2" style="width: 100%; height: 92px;">
                                        <div class="card-body p-1">
                                            <P class="card-title fs-6">Total Energy <small>Generated</small></P>
                                            <div class="d-flex justify-content-start my-2">
                                                <div><i class="fa-solid fa-bolt-lightning fa-xl" style="color: #FFD43B; padding: 18px 0px 5px 10px;"></i></div>
                                                <div id="latestTotalEnergy" class="fs-5" style="margin-left: 10px; padding: 2px 0px 0px 5px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between" style="padding: 20px 0px 20px 0px;">
                                        <div><p class="card-title fs-4"><b>Energy Generation</b></p></div>
                                        <div class="d-flex custom-select" style="width: 200px;">
                                            <div style="margin:auto;">
                                                <p style="color: #10439F; margin: auto; text-align: center;">Latest</p>
                                            </div>
                                            <div>
                                                <select id="statistic-select">
                                                    <option value="7">Daily</option>
                                                    <option value="12">Monthly</option>
                                                    <option value="10">Yearly</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="statistic-graph" style="margin-bottom: 10px">
                                        <div style="width: 100%; height: 25%;">
                                            <div><canvas id="statisticChart" style="max-height: 220px;"></canvas></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="3rd-panel col-lg-3 col-md-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="control-panel card" style="width: 100%; margin: 10px 0px;">
                                <div class="card-body">
                                    <p class="card-title fs-4"><b>Control Panel</b></p>
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="control-btn-title fs-5" style="color: #387ADF;">Battery</p>
                                            <button class="battery-control-btn btn-on" id="battery-control-btn" onclick="batteryButton()" disabled>ON</button>
                                        </div>
                                        <div class="col-6">
                                            <p class="control-btn-title fs-5" style="color: #387ADF;">Panel</p>
                                            <button class="panel-control-btn btn-on" id="panel-control-btn" onclick="panelButton()" disabled>ON</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="battery-performance card" style="width: 100%; margin: 0px 0px 20px 0px;">
                                <div class="card-body">
                                    <p class="card-title fs-4"><b>Battery Performance</b></p>
                                    <div class="battery" style="position: relative; top: 10%;">
                                        <div class="batteryBox">
                                            <div class="charge" id="chargeLevel"></div>
                                            <span id="chargeText" class="fs-5 fw-bold"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex custom-select justify-content-start" style="margin-top: 30px;">
                                        <div style="margin-right:10px;">
                                            <p class="card-title fs-4"><b>Battery Temperature</b></p>
                                        </div>
                                    </div>
                                    <div id="batteryTemp-graph">
                                        <div style="width: 100%; height: 25%;">
                                            <div><canvas id="batteryTempChart" style="max-height: 180px;"></canvas></div>
                                        </div>
                                    </div>
                                    <div><p class="current-bt fs-6 m-0">Current Temperature: <span class="bt-reading" id="bt-reading"></span> °C</p></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        //current battery temperature
        function fetch_batteryTemp(){
            fetch('fetch_batteryTemp.php')
            .then(response => response.json())
            .then(data => {
                const output = `${data.BatteryTemp}`;
                document.getElementById("bt-reading").innerText = output;
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload =fetch_batteryTemp();

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
        function initializeButtonState() {
            var batteryBtn = document.getElementById("battery-control-btn");
            var batteryStatus = localStorage.getItem("batteryStatus");
            if (batteryStatus === "0") {
                batteryBtn.classList.remove("btn-off");
                batteryBtn.classList.add("btn-on");
                batteryBtn.textContent = "ON";
            } else {
                batteryBtn.classList.remove("btn-on");
                batteryBtn.classList.add("btn-off");
                batteryBtn.textContent = "OFF";
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
        }
        document.addEventListener("DOMContentLoaded", initializeButtonState);

        function updatePanelStatus(){
            var batteryStatus = localStorage.getItem("batteryStatus");
            var panelStatus = localStorage.getItem("panelStatus");
            if(batteryStatus == 0 && panelStatus == 1) { document.getElementById("panel-status").textContent = "Active"; }
            else { document.getElementById("panel-status").textContent = "Inactive"; }
        }
        document.addEventListener("DOMContentLoaded", updatePanelStatus);

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

        //energy statistic graph
        const barColors = ["#5BBCFF","#FFFAB7","#FFD1E3","#7EA1FF","#A3FFD6","#7BD3EA","#F6D6D6"];
        const day = <?php echo json_encode($day); ?>;
        const statistic = <?php echo json_encode($dailyEnergy); ?>;
        const ctxStatistic = document.getElementById('statisticChart');
        const chartStatistic = new Chart(ctxStatistic, {
            type: 'bar',
            data: {
                labels: day,
                datasets: [{
                    data: statistic,
                    borderWidth: 1,
                    backgroundColor: barColors,
                    borderRadius: Number.MAX_VALUE,
                    barPercentage: 0.4
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
                            display: true
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 60,
                        ticks: {
                            stepSize: 15
                        }
                    }
                },
            }
            });
        function updateStatistic(){
            const timeframe = parseInt(document.getElementById("statistic-select").value);
            let fetchUrl = '';
            if(timeframe == 7){
                fetchUrl = 'fetch_dailyStatisticGraph.php';
                fetch(fetchUrl)
                .then(response => response.json())
                .then(data => {
                    const x = data.map(item => item.x);
                    const y = data.map(item => item.y);
                    while(x.length > timeframe){
                        x.shift();
                        y.shift();
                    }
                    chartStatistic.data.labels = x;
                    chartStatistic.data.datasets[0].data = y;
                    chartStatistic.update('none');
                })
                .catch(error => console.error('Error fetching data:', error));
                return;
            }
            else if(timeframe == 12){
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                chartStatistic.data.labels = months;
                chartStatistic.data.datasets[0].data = Array(12).fill(0); //y-axis are filled with zeros
                chartStatistic.update('none');
                return;
            }
            else if(timeframe == 10){
                const years = ['2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023', '2024'];
                chartStatistic.data.labels = years;
                chartStatistic.data.datasets[0].data = Array(12).fill(0); //y-axis are filled with zeros
                chartStatistic.update('none');
                return;
            }
        }
        document.getElementById("statistic-select").addEventListener("change", updateStatistic);

        //batteryTemp graph
        const BatteryTemp = <?php echo json_encode($BatteryTemp); ?>; //remember to change
        const ctxbt = document.getElementById('batteryTempChart');
        const chartbt = new Chart(ctxbt, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    data: BatteryTemp,
                    borderWidth: 2,
                    borderColor: '#FFC100',
                    cubicInterpolationMode: 'monotone',
                    pointRadius: 0.5,
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
                        max: 60,
                        ticks: {
                            stepSize: 10
                        }
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
        setInterval(updateStatistic, 30000);
        setInterval(updateInverterSelection, 30000);
        setInterval(updateBatteryTemp, 30000);

        //current battery level
        function fetch_batteryLevel(){
            fetch('fetch_latestBatteryLevel.php')
            .then(response => response.json())
            .then(data => {
                const output = `${data.BatteryLevel}`;
                document.getElementById("chargeLevel").style.width = output + "%";
                document.getElementById("chargeText").innerHTML = output + "%";
                if (output <= 10) {
                    alert ("Battery Level is too low! Switch to backup battery!");
                }
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload =fetch_batteryLevel();
        setInterval(fetch_batteryLevel, 30000);

        function fetch_latestPower(){
            fetch('fetch_latestPower.php')
            .then(response => response.json())
            .then(data => {
                const output = `${data.Power}`;
                document.getElementById("latestPower").innerText = Math.round(output).toFixed(2) + 'W';
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload = fetch_latestPower();
        function fetch_latestDailyEnergy(){
            fetch('fetch_latestEtoday.php')
            .then(response => response.json())
            .then(data => {
                const output = `${data.Etoday}`;
                document.getElementById("latestDailyEnergy").innerText = output + 'Wh';
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload =fetch_latestDailyEnergy();
        function fetch_latestEnergy(){
            fetch('fetch_latestEtotal.php')
            .then(response => response.json())
            .then(data => {
                const output = parseFloat(data.Etotal) + 31.58;
                document.getElementById("latestTotalEnergy").innerText = output.toFixed(2) + 'Wh';
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload =fetch_latestEnergy();

        function getEarning(){
            fetch('fetch_latestEtotal.php')
            .then(response => response.json())
            .then(data => {
                const output = parseFloat(data.Etotal) + 31.58;
                document.getElementById("earning-reading").innerText = (output * 0.218 / 1000).toFixed(4);
            })
            .catch(error => console.error('Error fetching data:', error));
        }
        window.onload = getEarning;

        let updateTime = 30000;
        setInterval(fetch_latestPower, updateTime);
        setInterval(fetch_latestDailyEnergy, updateTime);
        setInterval(fetch_latestEnergy, updateTime);
        setInterval(fetch_Eused, updateTime);
    </script>
</body>
</html>