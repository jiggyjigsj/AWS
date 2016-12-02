<?php
$username="jpatel74";
$password="Pat3l120133";

require '/var/www/html/vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

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

$chk_pic = "SELECT * FROM records";
$check_pic = $link->query($chk_pic);

$row_cnt = $check_pic->num_rows;

if ($row_cnt == 0) { 

$insert = "'INSERT INTO `records` VALUES (1,'admin','6304074614','knuth','https://s3.amazonaws.com/raw-jjp/knuth.jpeg','https://s3.amazonaws.com/finish-jjp/knuth.png',1,0,'47983de493c654fcdc8c289f120b7e80'),(2,'admin','6304074614','mountain','https://s3.amazonaws.com/raw-jjp/mountain.jpeg','https://s3.amazonaws.com/finish-jjp/mountain.png',1,0,'8f2c61f4f107955ac53bfea8779d038c'),(3,'admin','6304074614','sound','https://s3.amazonaws.com/raw-jjp/sound.jpeg','https://s3.amazonaws.com/finish-jjp/sound.png',1,0,'d6b232aa0cb539ff59bd652058dba2a2')";

$insert_usr = $link->query($insert);
$s3= S3Client::factory(array(
  'region'  => 'us-east-1',
  'version' => 'latest',
  'credentials' => false
));
$raw = array("pics/knuth.jpeg", "pics/mountain.jpeg", "pics/sound.jpeg");
$finish = array("pics/knuth.png", "pics/mountain.png", "pics/sound.png");

foreach ($raw as &$value) {
  $bucket="raw-jjp";

  if ($value == "pics/knuth.jpeg"){
    $key="knuth";
  } elseif ($value == "pics/mountain.jpeg") {
    $key = "mountain";
  } else {
    $key = "sound";
  }

  $result = $s3->putObject(array(
  'Bucket'     => $bucket,
  'Key'        => $key,
  'SourceFile' => $value,
  'ACL'        => 'public-read'
));
}
foreach ($arr as &$value) {
    $bucket="finish-jjp";
  
  if ($value == "pics/knuth.png"){
    $key="knuth";
  } elseif ($value == "pics/mountain.png") {
    $key = "mountain";
  } else {
    $key = "sound";
  }

}
$result = $s3->putObject(array(
  'Bucket'     => $bucket,
  'Key'        => $key,
  'SourceFile' => $value,
  'ACL'        => 'public-read'
));
}

$link->close();
?>