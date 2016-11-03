<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>S3 Test</title>
  </head>
  <body>
  	<form action="#" method="POST">
	  <input type="submit" value="Sent Picture">
	</form>
  </body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$bucket = 'raw-jjp';
	$keyname = 'switchonarex.png';
	$path = '/var/www/html/switchonarex.png';
	
	$s3= S3Client::factory(array(
	'region'  => 'us-east-1',
	'version' => 'latest',
	'credentials' => false
));
    	$result = $s3->putObject(array(
    	'Bucket'     => $bucket,
    	'Key'        => $keyname,
    	'SourceFile' => $path,
	'ACL' 	     => 'public-read'
));

echo $result['ObjectURL'] . "\n";

}
?>
