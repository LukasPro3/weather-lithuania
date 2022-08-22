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
        $string = file_get_contents("https://api.meteo.lt/v1/places");
        $json = json_decode($string, true);
        for ($x = 0; $x < 2243; $x++) {
          foreach ($json[$x] as $key => $value) {
           # echo "$key : $value". PHP_EOL;
            if( $key == 'code'){
                $code=$value;
               }
            elseif($key == 'name'){
                $name = $value;
               }
            elseif($key == 'administrativeDivision'){
                $administrativeDivision = $value;
               }
            elseif($key == 'countryCode'){
                $countryCode= $value;
               }
        }
            if ($countryCode == 'LT'){
            $sql = " INSERT INTO miestai (code,name,administrativeDivision,countryCode)
            VALUES ('$code','$name','$administrativeDivision','$countryCode') " ;
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully"."<br>";
            }
            else{
                echo "Error: " . $up_sql . "<br>" . $conn->error."<br>";
            }
        }
        }
      
    
        #echo $json["code"];
        #var_dump($json);



?>