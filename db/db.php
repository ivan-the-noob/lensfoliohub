<?php
$servername = "localhost";  
$username = "diana";         
$password = "lens";             
$dbname = "LENSFOLIOHUB";   

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
