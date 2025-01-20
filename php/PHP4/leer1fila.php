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

$sql = "SELECT * FROM usuarios Limit 2 ";
$result = mysqli_query($conn, $sql);
 

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    echo "id: " . $row["id"]."<br>". " Name: " . $row["nombre"]. " " . $row["apellido"]."<br>". " Telefono: " . $row["numero"]. "<br><br>";
  }
} else {
  echo "0 results";
}


?>