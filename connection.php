<?php
// connect to databse boilerplate
$servername = "localhost";
$username = "u896018919_scribloADMIN";
$password = "2P=u>3LQ*S@N";
$dbname = "u896018919_scriblo";

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "scriblo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
?>
