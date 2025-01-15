<?php 

$servername = "sql308.thsite.top";
$username = "thsi_38097478";
$password = "puteros";


// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";


?>