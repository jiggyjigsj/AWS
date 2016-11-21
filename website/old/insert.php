<?php
include '../password.php';
require 'vendor/autoload.php';
$conn = new mysqli($hostname,$username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "CREATE DATABASE IF NOT EXISTS  school";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}
$conn->close();
echo "<br>";
$conn = new mysqli($hostname,$username, $password, "school");

$sql1 = "CREATE TABLE IF NOT EXISTS student ( id INT(6) AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), age INT(3))";
if ($conn->query($sql1) === TRUE) {
    echo "Table created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

echo "<br>";
$sql3 = "INSERT INTO `student` (`name`,`age`) VALUES ('Driscoll Lara',2),('Price Vang',99),('James Holt',70),('Zeph Calderon',26),('Akeem Vincent',22)";
if ($conn->query($sql3) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error creating database: " . $conn->error;
}
$conn->close();
?>
