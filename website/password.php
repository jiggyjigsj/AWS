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

$end = $result['DBInstances'][0]['Endpoint']['Address'];
$endpoint=$end.':3306';
// Set session variables
$_SESSION["hostname"] = $endpoint;
$host = $_SESSION["hostname"];

$conn = new mysqli($_SESSION["hostname"],$username, $password);

$sql = "CREATE DATABASE IF NOT EXISTS  app";
if ($conn->query($sql) === TRUE) {
    
} else {
    echo "Error creating database: " . $conn->error;
}
$conn->close();

$link = new mysqli($_SESSION["hostname"],$username,$password,"app");

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
    receipt VARCHAR(255) NULL,
    PRIMARY KEY(id)
)';

$create_tbl = $link->query($create_table);

$create_admin = 'CREATE TABLE IF NOT EXISTS users
(
	id INT NOT NULL AUTO_INCREMENT,
	username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(50) NULL,
	password VARCHAR(50) NOT NULL,
	PRIMARY KEY(id)
)';
$result = $link->query($create_admin);


$check_users = "SELECT * FROM users";
$chk_usr = $link->query($check_users);

$row_cnt = $chk_usr->num_rows;

if ($row_cnt == 0) { 

$insert = 'INSERT INTO users (`id`,`username`,`email`,`password`) VALUES (1,"controller","","password"),(2,"jpatel74@hawk.iit.edu","jpatel74@hawk.iit.edu","123456"),(3,"admin","admin@localhost","admin")';

$insert_usr = $link->query($insert);
}
  
$admin = 'CREATE TABLE IF NOT EXISTS admin
(
  id INT NOT NULL AUTO_INCREMENT,
  feature VARCHAR(50) NOT NULL UNIQUE,
  status INT(1) NULL,
  PRIMARY KEY(id)
)';
$result = $link->query($admin);

$chk_adm = "SELECT * FROM admin";
$check_admin = $link->query($chk_adm);

$row_cnt = $check_admin->num_rows;

if ($row_cnt == 0) { 

$insert = 'INSERT INTO admin (`id`,`feature`,`status`) VALUES (1,"upload",1)';

$insert_usr = $link->query($insert);}
$link->close();
?>