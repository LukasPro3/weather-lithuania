<?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "orai";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        $conn->set_charset("utf8");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $salygos = array("clear"=>"giedra", "isolated-clouds"=>"mažai debesuota", "scattered-clouds"=>"debesuota su pragiedruliais","overcast"=>"debesuota", "light-rain"=>"nedidelis lietus", "moderate-rain"=>"lietus","heavy-rain"=>"smarkus lietus","sleet"=>"šlapdriba","light-snow"=>"sniegas","moderate-snow"=>"sniegas","heavy-snow"=>"smarkus sniegas","fog"=>"rūkas","na"=>"oro sąlygos nenustatytos");
        #$miestai = array("Vilnius","Kaunas","Klaipeda","Panevezys","Siauliai");
        #foreach($miestai as $miestas ){
        $sql_city = "SELECT code FROM miestai";
        $result = $conn->query($sql_city);
        while($row = $result->fetch_assoc()) {
        $miestas = $row["code"];
        $string = file_get_contents("https://api.meteo.lt/v1/places/$miestas/forecasts/long-term");
        $json = json_decode($string, true);

       
        for ($x = 0; $x <= 72; $x++) {
            foreach ($json['forecastTimestamps'][$x] as $key => $value) {
                #echo "$key : $value". PHP_EOL;
                if( $key == 'forecastTimeUtc'){
                    $forecastTimeUtc=$value;
                   }
                elseif($key == 'airTemperature'){
                    $airTemperature= $value;
                   }
                elseif($key == 'windSpeed'){
                    $windSpeed= $value;
                   }
                elseif($key == 'windGust'){
                    $windGust= $value;
                   }
                elseif($key == 'windDirection'){
                    $windDirection= $value;
                   }
                elseif($key == 'cloudCover'){
                    $cloudCover= $value;
                   }
                elseif($key == 'seaLevelPressure'){
                    $seaLevelPressure= $value;
                   }
                elseif($key == 'relativeHumidity'){
                    $relativeHumidity= $value;
                   }
                elseif($key == 'totalPrecipitation'){
                    $totalPrecipitation= $value;
                   }
                elseif($key == 'conditionCode'){
                    $conditionCode= $salygos[$value];
                   }
            }
            $city = $json['place']['name'];   
            $id = $city . ' ' . $forecastTimeUtc;
            $sql = " INSERT INTO long_term (id, city, forecastTimeUtc, airTemperature, windSpeed, windGust, windDirection, cloudCover, seaLevelPressure, relativeHumidity, totalPrecipitation, conditionCode)
            VALUES ('$id','$city','$forecastTimeUtc','$airTemperature','$windSpeed','$windGust','$windDirection','$cloudCover','$seaLevelPressure','$relativeHumidity','$totalPrecipitation','$conditionCode') " ;
        if ($conn->query($sql) === TRUE) {
                #echo "New record created successfully"."<br>";
            }
        else {
            $up_sql = ("UPDATE long_term SET airTemperature = '$airTemperature', windSpeed = '$windSpeed', windGust = '$windGust', windDirection = '$windDirection', cloudCover = '$cloudCover', seaLevelPressure ='$seaLevelPressure', relativeHumidity = '$relativeHumidity',totalPrecipitation = '$totalPrecipitation',conditionCode='$conditionCode' WHERE id = '$id'");
            if ($conn->query($up_sql) === TRUE) {
               # echo "Updated successfully"."<br>";
            }
            else{
                echo "Error: " . $up_sql . "<br>" . $conn->error."<br>";
            }
        }
    }#echo "END1 <br>";
}

#echo "END!!!!!!!!";
        $conn->close();
        ?>