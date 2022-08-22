<?php

function WeekDay($date) {
  $dayofweek = date('w', strtotime($date));
  $weekday = array("Sekm","Pirm","Antr","Treč","Ketv","Penk","Šešt");
  echo $weekday[$dayofweek];
}

function MaxTemp($city,$date){
        include 'connect.php';
         $max =  "SELECT MAX(airTemperature) as maxTemp FROM long_term WHERE id LIKE '$city $date%' ";
         $result = $conn->query($max);
         $row = $result->fetch_assoc();
         echo round($row["maxTemp"], 0);

}

function MinTemp($city,$date){
  include 'connect.php';
  $max =  "SELECT MIN(airTemperature) as minTemp FROM long_term WHERE id LIKE '$city $date%' ";
  $result = $conn->query($max);
  $row = $result->fetch_assoc();
  echo round($row["minTemp"], 0);
}

function Icon($code){
 $Icons = array(
    "giedra"=> "wi-day-sunny",
    "mažai debesuota"=> "wi-day-cloudy",
    "debesuota su pragiedruliais"=> "wi-day-cloudy",
    "debesuota"=> "wi-cloudy",
    "nedidelis lietus"=> " wi-showers",
    "lietus"=> "wi-day-hail",
    "smarkus lietus"=> "wi-thunderstorm",
    "nedidelis sniegas"=> "wi-snow",
    "sniegas"=> "wi-snow",
    "smarkus sniegas"=> "wi-snow",
    "rūkas"=> "wi-fog",
 );

 echo $Icons[$code];
 #return $Icons[$code];
}

function exists($pavadinimas){
include "connect.php";
$sql_city = "SELECT name FROM miestai WHERE name='$pavadinimas'";
$result = $conn->query($sql_city);
if ($result->num_rows > 0) {
    return true;
} else {
  return false;
}
}

?>

<!DOCTYPE html>
<html lang="en" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Oras Lietuvoje</title
  >
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.9/css/weather-icons.css'>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'><link rel="stylesheet" href="./style.css">
<script type="text/javascript" src="jquery-1.4.2.min.js"></script>
 <script type="text/javascript" src="jquery.autocomplete.js"></script>

 <script> 
 jQuery(function(){ 
 $("#search").autocomplete("search.php");
 });
 </script>

</head>
<body>
<style>
  form {
  display : inline-flex;
}
</style>
<nav>
  <div class="wrap">
   <div class="search">
    <form method= 'get'> 
      <input type="text" id="search" name="q"  class="searchTerm" placeholder="Įveskite miestą">
      <button type="submit" action = "<?php $city = $_GET['q'] ;?>"  class="searchButton">
        <i class="fa fa-search"></i>
     </button>
</form>
   </div>
</div>
</nav>
<?php 
if (!exists($city)){
  $city='Panevėžys';
};
?>

<div class="container" id="wrapper">
  <div class="container-fluid" id="current-weather">
    <div class="row">
      
      <!-- Right panel -->
      <div class="col-md-4 col-sm-5">
        <h5><spam id="cityName"><?php echo $city ?></spam>, <spam id="cityCode">LT</spam></h5>
        <h6 id="localDate"><script>var d=new Date()
                  var weekday=new Array("Sekmadienis","Pirmadienis","Antradienis","Trečiadienis","Ketvirtadienis",
                              "Penktadienis","Šeštadienis")
                  document.write(weekday[d.getDay()])</script></h6>
        <h5 id="localTime"><script>
          setInterval(function(){
    $("#localTime").text(new Date().toLocaleTimeString('en-US', {
    hour12: false,
  }));
  }, 1000);
        </script>
          </h5>
        <a id="refreshButton" href="./galutinis.php?q=<?php echo $city ?>"><i class="fa fa-refresh fa-fw" aria-hidden="true"></i> Atnaujinti</a>
      </div>
      <?php include 'connect.php';
                date_default_timezone_set('Europe/Vilnius');
                $date = date('Y-m-d H:00:00');
                $id = $city . ' ' . $date;
                #echo $date;
                $sql = "SELECT conditionCode, airTemperature, windSpeed,relativeHumidity FROM long_term WHERE id = '$id'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $pavadinimas = $row["conditionCode"];
                $temp = $row["airTemperature"];
                $wind_D = $row["windSpeed"];
                $humid = $row["relativeHumidity"];
                ?>
      
      <!-- Center panel -->
      <div class="col-md-5 col-sm-7" style="margin: 10px auto;padding:0;">
        <div class="row">

          <i class="wi <?php Icon($pavadinimas)?>" id ="main-icon" style="font-size: 85px;"></i>
          <div>
            <spam id="mainTemperature"> <?php echo $temp; ?></spam>
            <p id="tempDescription"><?php echo ucfirst($pavadinimas) ?></p>
          </div>
          <p style="font-size: 1.5rem;">°C
        </div>
      </div>
      
      <!-- Left panel -->
      <div class="col-xs-12 col-sm-12 col-md-3 row" style="text-align: right;">
        <div class="col-md-12 col-sm-3 col-xs-3 side-weather-info">
          <h6>Dregmė: <spam id="humidity"><?php echo $humid; ?></spam>%</h6>
        </div>
        <div class="col-md-12 col-sm-3 col-xs-3 side-weather-info">
          <h6>Vėjas: <spam id="wind"><?php echo $wind_D; ?></spam> m/s</h6>
        </div>

        <div class="col-md-12 col-sm-3 col-xs-3 side-weather-info">
          <h6>Aukščiausia: <spam id="mainTempHot">
            <?php 
          $data= date('Y-m-d');
          MaxTemp($city,$data);?></spam>°</h6>
        </div>

        <div class="col-md-12 col-sm-3 col-xs-3 side-weather-info">
          <h6>Žemiausia: <spam id="mainTempLow"><?php MinTemp($city,$data); ?></spam>°</h6>
        </div>
      </div>
      
    </div>
  </div>
  
  
  <!-- 4 days forecast -->
  <div class="container-fluid">
    <div class="row" style="padding: 2px;">
      
      <!-- Day 1 -->
      <div class="col-md-3 col-sm-6 day-weather-box">
        <div class="col-sm-12 day-weather-inner-box">
          <div class="col-sm-8 forecast-main">
          <?php
                date_default_timezone_set('Europe/Vilnius');
                $data_1 = date('Y-m-d 12:00:00',strtotime('+1 days'));
                $day_1 = date('Y-m-d',strtotime($data_1));
                $id = $city. ' ' . $data_1;
                $sql = "SELECT conditionCode, airTemperature FROM long_term WHERE id = '$id'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $pavadinimas = $row["conditionCode"];
                $temp = $row["airTemperature"];
                ?>
            <p id="forecast-day-1-name"><?php WeekDay($data_1)?></p>
            <div class="row">
              <h5 id="forecast-day-1-main"><?php echo round($temp, 0);?>°</h5>
              <i class="wi <?php Icon($pavadinimas)?>" id="forecast-day-1-icon"></i>
            </div>
          </div>
          <div class="col-sm-4 forecast-min-low">
            <p><spam class="high-temperature" id="forecast-day-1-ht"><?php 
          MaxTemp($city,$day_1); ?></spam></p>
            <p><spam class="low-temperature" id="forecast-day-1-lt"><?php MinTemp($city,$day_1)?></spam></p>
          </div>
        </div>
      </div>
      
      <!-- Day 2 -->
      <div class="col-md-3 col-sm-6 day-weather-box">
        <div class="col-sm-12 day-weather-inner-box">
          <div class="col-sm-8 forecast-main">
          <?php
                date_default_timezone_set('Europe/Vilnius');
                $data_2 = date('Y-m-d 12:00:00',strtotime('+2 days'));
                $day_2 = date('Y-m-d',strtotime($data_2));
                $id = $city. ' ' . $data_2;
                $sql = "SELECT conditionCode, airTemperature FROM long_term WHERE id = '$id'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $pavadinimas = $row["conditionCode"];
                $temp = $row["airTemperature"];

                ?>
            <p id="forecast-day-2-name"><?php WeekDay($data_2)?></p>
            <div class="row">
              <h5 id="forecast-day-2-main"><?php echo round($temp, 0);?>°</h5>
              <i class="wi <?php Icon($pavadinimas)?>" id="forecast-day-2-icon"></i>
            </div>
          </div>
          <div class="col-sm-4 forecast-min-low">
            <p><spam class="high-temperature" id="forecast-day-2-ht"><?php MaxTemp($city,$day_2)?></spam></p>
            <p><spam class="low-temperature" id="forecast-day-2-lt"><?php MinTemp($city,$day_2)?></spam></p>
          </div>
        </div>
      </div>
      
      <!-- Day 3 -->
      <div class="col-md-3 col-sm-6 day-weather-box">
        <div class="col-sm-12 day-weather-inner-box">
          <div class="col-sm-8 forecast-main">
          <?php
                date_default_timezone_set('Europe/Vilnius');
                $data_3 = date('Y-m-d 12:00:00',strtotime('+3 days'));
                $day_3 = date('Y-m-d',strtotime($data_3)); 
                $id = $city. ' ' . $data_3;
                $sql = "SELECT conditionCode, airTemperature FROM long_term WHERE id = '$id'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $pavadinimas = $row["conditionCode"];
                $temp = $row["airTemperature"];
                ?>
            <p id="forecast-day-3-name"><?php WeekDay($data_3)?></p>
            <div class="row">
              <h5 id="forecast-day-3-main"><?php echo round($temp, 0);?>°</h5>
              <i class="wi <?php Icon($pavadinimas)?>" id="forecast-day-3-icon"></i>
            </div>
          </div>
          <div class="col-sm-4 forecast-min-low">
            <p><spam class="high-temperature" id="forecast-day-3-ht"><?php MaxTemp($city,$day_3)?></spam></p>
            <p><spam class="low-temperature" id="forecast-day-3-lt"><?php MinTemp($city,$day_3)?></spam></p>
          </div>
        </div>
      </div>
      
      <!-- Day 4 -->
      <div class="col-md-3 col-sm-6 day-weather-box">
        <div class="col-sm-12 day-weather-inner-box">
          <div class="col-sm-8 forecast-main">
          <?php
                date_default_timezone_set('Europe/Vilnius');
                $data_4 = date('Y-m-d 12:00:00',strtotime('+4 days')); 
                $day_4 = date('Y-m-d',strtotime($data_4));
                $id = $city. ' ' . $data_4;
                $sql = "SELECT conditionCode, airTemperature FROM long_term WHERE id = '$id'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $pavadinimas = $row["conditionCode"];
                $temp = $row["airTemperature"];
                ?>
            <p id="forecast-day-4-name"><?php WeekDay($data_4)?></p>
            <div class="row">
              <h5 id="forecast-day-4-main"><?php echo round($temp, 0);?>°</h5>
              <i class="wi <?php Icon($pavadinimas)?>" id="forecast-day-4-icon"></i>
            </div>
          </div>
          <div class="col-sm-4 forecast-min-low">
            <p><spam class="high-temperature" id="forecast-day-4-ht"><?php MaxTemp($city,$day_4)?></spam></p>
            <p><spam class="low-temperature" id="forecast-day-4-lt"><?php MinTemp($city,$day_4)?></spam></p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>

<!-- partial -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/js/bootstrap.min.js'></script><script  src="./script.js"></script>




</body>
</html>
