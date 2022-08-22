
<?php 
include 'connect.php';

#var_dump($_GET);
$id = $_GET['q'];
$sql = "SELECT name FROM miestai WHERE name like '$id%' limit 10 ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
 while($row = $result->fetch_assoc()) {
    #var_dump($row);
 echo $row['name']. "\n";
 }
} else {
 echo "nerasta";
}

?>