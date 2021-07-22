<?php 
 $servername = "localhost";
 $username = "root";
 $password = "mysql";
 $dbname = "pms";
 error_reporting(E_ALL ^ E_WARNING);
 $conn = mysqli_connect($servername, $username, $password, $dbname); // Create connection
 if (!$conn) {
 die("Connection failed: " . mysqli_connect_error());
 }
 

?>