<?php

$username="jpatel74";
$password="Pat3l120133";

require '/var/www/html/vendor/autoload.php';

$client = new Aws\Rds\RdsClient([
  'region'            => 'us-west-2',
    'version'           => 'latest',
	'credentials' => [
'key'    => 'AKIAIKYMAUHZJ7CYJEJQ',
'secret' => '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'],
]); 

$result = $client->describeDBInstances([
    'DBInstanceIdentifier' => 'dbfirst'
]);

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
$endpoint=$endpoint.':3306';
echo $endpoint;

$conn = new mysqli($endpoint,$username, $password);

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
	username VARCHAR(50) NOT NULL,
	password VARCHAR(30) NOT NULL,
	feature INT(1) NULL
)';
$check_users = "SELECT * FROM users";
$chk_usr = $link->query($check_users);

if (mysqli_num_rows($chk_usr)==0) { 

$insert = 'INSERT INTO TABLE users (`id`,`username`,`password`) VALUES (1,"admin","admin"),(2,"jpatel74@hawk.iit.edu","123456"),(3,"controller","password")';

$insert_usr = $link->query($insert);

}

$link->close();
?>