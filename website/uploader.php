<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Uploader</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, scale-to-fit=no">
</head>
<body>
<div class="background">
<div class="transbox">
<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
include 'nav.php';
include '../password.php';
echo '<br><br><br><br>';

$target_dir = "/tmp/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "<h4>File is an image - " . $check["mime"] . ".</h4><br>";
        $uploadOk = 1;
    } else {
        echo "<h4>File is not an image.</h4><br>";
        $uploadOk = 0;
    }
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<h4>Sorry, your file was not uploaded.</h4><br>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<h4>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</h4><br>";

			$file=basename( $_FILES["fileToUpload"]["name"]);
			$bucket = 'raw-jjp';
			$keyname = basename( $_FILES["fileToUpload"]["name"]);
			$path = $target_file;
			
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
		$s3rawurl=$result['ObjectURL'];

		$user=$_POST['user'];
		$phone=$_POST['phone'];
		$filename=$_POST['filename'];
		$status=0;
		$s3finishedurl=' ';
		$receipt=md5($s3rawurl);
		$issubscribed=0;

		$mysqli = new mysqli($_SESSION["hostname"],$username,$password,"app");

		/* Prepared statement, stage 1: prepare */
		if (!($stmt = $mysqli->prepare("INSERT INTO records (id, email, phone, filename, s3rawurl, s3finishedurl, status, issubscribed, receipt) VALUES (NULL,?,?,?,?,?,?,?,?)"))) {
		    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}

		$stmt->bind_param("sssssiis",$user,$phone,$filename,$s3rawurl,$s3finishedurl,$status,$issubscribed,$receipt);
		$stmt->execute();
		$mysqli->close();
		require 'vendor/autoload.php';

		$sqsclient = new Aws\Sqs\SqsClient([
	    'region'  => 'us-west-2',
	    'version' => 'latest',
		'credentials' => [
			'key'    => 'AKIAIKYMAUHZJ7CYJEJQ',
			'secret' => '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']
		]);

		// Code to retrieve the Queue URLs
		$sqsresult = $sqsclient->getQueueUrl([
		    'QueueName' => 'MyQueue', // REQUIRED
		]);

		echo "<h4>".$sqsresult['QueueUrl']."</h4><br>";
		$queueUrl = $sqsresult['QueueUrl'];
		$sqsresult = $sqsclient->sendMessage([
		    'MessageBody' => $receipt, // REQUIRED
		    'QueueUrl' => $queueUrl // REQUIRED
		]);
				$user=$_POST['user'];
		$phone=$_POST['phone'];
		$filename=$_POST['filename'];
		$status=0;
		$s3finishedurl=' ';
		$receipt=md5($s3rawurl);
		$issubscribed=0;
		echo "<h4>Unfinished URL for the image is: ". $s3rawurl. "</h4><br>";
		echo "<h4>SQS Job Message ID: </h4>";
		echo "<h4>".$sqsresult['MessageId']."</h4><br>";
		echo "<br>";
		echo "<h4>View Your Images by navigating to Gallery Tab.</h4>";
    } else {
        echo "<h1>Sorry, there was an error uploading your file.</h1><br>";
    }
}
?>
</div>
</div>
</body>
</html>