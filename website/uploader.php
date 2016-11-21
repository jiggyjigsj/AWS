<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, scale-to-fit=no">
</head>
<body>

<?php
include 'nav.php';
include '../password.php';
echo '<br><br><br><br>';

$target_dir = "/tmp/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
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
$reciept=md5($s3rawurl);
$issubscribed=0;

$mysqli = new mysqli($_SESSION["hostname"],$username,$password,"app");

/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("INSERT INTO records (id, email, phone, filename, s3rawurl, s3finishedurl, status, issubscribed, reciept) VALUES (NULL,?,?,?,?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$stmt->bind_param("sssssiis",$user,$phone,$filename,$s3rawurl,$s3finishedurl,$status,$issubscribed,$reciept);
$stmt->execute();
$mysqli->close();
?>