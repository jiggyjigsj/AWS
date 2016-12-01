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
$mysqli = new mysqli($_SESSION["hostname"],$username,$password,"app");

$keyname = $_POST["filename"];

$chk_adm = "SELECT * FROM records where filename = '$keyname'";
$result = $mysqli->query($chk_adm);
$row_cnt = $result->num_rows;

if ($row_cnt == '1'){
	$uploadOk = 0;
	echo "<h4>File name Already Exist!</h4>";
}


$target_dir = "/tmp/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "<h4>File is an image - " . $check["mime"] . ".</h4>";
        $uploadOk = 1;
    } else {
        echo "<h4>File is not an image.</h4>";
        $uploadOk = 0;
    }
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<h4>Sorry, your file was not uploaded.</h4>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<h4>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</h4>";
		$filename=$_POST['filename'];
	    function convertImage($originalImage, $outputImage, $quality)
		{
		    // jpg, png, gif or bmp?
		    $exploded = explode('.',$originalImage);
		    $ext = $exploded[count($exploded) - 1]; 

		    if (preg_match('/jpg|jpeg/i',$ext))
		        $imageTmp=imagecreatefromjpeg($originalImage);
		    else if (preg_match('/png/i',$ext))
		        $imageTmp=imagecreatefrompng($originalImage);
		    else if (preg_match('/gif/i',$ext))
		        $imageTmp=imagecreatefromgif($originalImage);
		    else if (preg_match('/bmp/i',$ext))
		        $imageTmp=imagecreatefrombmp($originalImage);
		    else
		        return 0;

		    // quality is a value from 0 (worst) to 100 (best)
		    imagejpeg($imageTmp, $outputImage, $quality);
		    imagedestroy($imageTmp);

		    return 1;
		}

			$file=basename( $_FILES["fileToUpload"]["name"]);
			$bucket = 'raw-jjp';
			$key = $keyname.".jpeg";
			$path = $target_file;
			$filenew="$path.jpeg";
			$quality ='100';
			convertImage($path,$filenew,$quality);

			$s3= S3Client::factory(array(
			'region'  => 'us-east-1',
			'version' => 'latest',
			'credentials' => false
		));
		    	$result = $s3->putObject(array(
		    	'Bucket'     => $bucket,
		    	'Key'        => $key,
		    	'SourceFile' => $filenew,
			'ACL' 	     => 'public-read'
		));
		$s3rawurl=$result['ObjectURL'];

		$user=$_POST['user'];
		$phone=$_POST['phone'];
		$status=0;
		$s3finishedurl=' ';
		$receipt=md5($s3rawurl);
		$issubscribed=0;

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

		$queueUrl = $sqsresult['QueueUrl'];
		$sqsresult = $sqsclient->sendMessage([
		    'MessageBody' => $receipt, // REQUIRED
		    'QueueUrl' => $queueUrl // REQUIRED
		]);

		echo "<h4>Unfinished URL for the image is: ". $s3rawurl. "</h4>";
		echo "<h4>SQS Job Message ID: <h4>".$sqsresult['MessageId']."</h4>";
		echo "<h4>View Your Images by navigating to Gallery Tab.</h4>";
    } else {
        echo "<h1>Sorry, there was an error uploading your file.</h1>";
    }
}
?>
</div>
</div>
</body>
</html>