<?php

$username="jpatel74";
$password="Pat3l120133";

require '/var/www/html/vendor/autoload.php';


$client = new Aws\Rds\RdsClient([
  'region'            => 'us-west-2',
    'version'           => 'latest',
]);
 

$result = $client->describeDBInstances([
    'DBInstanceIdentifier' => 'dbfirst'
]);


$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];


$conn = new mysqli($hostname,$username, $password);

$sql = "CREATE DATABASE IF NOT EXISTS  app";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}
$conn->close();

$link = mysqli_connect($endpoint,$username,$password,"app") or die("Error " . mysqli_error($link));

$create_table = 'CREATE TABLE IF NOT EXISTS records  
(
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(200) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    s3rawurl VARCHAR(255) NOT NULL,
    s3finishedurl VARCHAR(255) NOT NULL,
    status INT NOT NULL,
    issubscribed INT NOT NULL,
    PRIMARY KEY(id)
)';

$create_tbl = $link->query($create_table);

$create_admin = 'CREATE TABLE IF NOT EXIST users
(
	id INT NOT NULL AUTO_INCREMENT.
	username VARCHAR(20) NOT NULL,
	password VARCHAR(30) NOT NULL,
	feature INT(1) NULL
)';
$check_users = "SELECT * FROM users";
$chk_usr = $link->query($check_users);

if (mysql_num_rows($chk_usr)==0) { 
	echo "empty";
}