<?php
include '/tmp/password.php';

if ( $hostname != '') {
echo "hostname is not empty";
$sql = "CREATE TABLE records 
(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(32),
phone VARCHAR(32),
s3-raw-url VARCHAR(32),
s3-finished-url VARCHAR(32),
status INT(1),
receipt VARCHAR(256)
)";

} else {
$url="aws rds describe-db-instances --query 'DBInstances[0].[Endpoint.Address]'";
$output = shell_exec($url);
echo $output;

if ($output == '') {
} else {
	$newout="$hostname=$output:3306";
	$sh="echo $newout > /tmp/password.php";
	$out = shell_exec($sh);
}
}
?>