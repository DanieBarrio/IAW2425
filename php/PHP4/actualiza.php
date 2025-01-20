<?php
$servername = "";
$username = "";
$password = "";
$db="";


// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "UPDATE  SET nombre='Perry' WHERE id=4";

$sqli = mysqli_query($sql, $conn);
if ($sqli) {
  echo "Record updated successfully";
} else {
  echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?> 