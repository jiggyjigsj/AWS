<?php
require 'vendor/autoload.php';
include '../password.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$client = new Aws\Sqs\SqsClient([
'region'  => 'us-west-2',
'version' => 'latest',
'credentials' => [
	'key'    => 'AKIAIKYMAUHZJ7CYJEJQ',
	'secret' => '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']
]);


// Code to retrieve the Queue URLs
$result = $client->getQueueUrl([
    'QueueName' => 'MyQueue', // REQUIRED
]);
$queueUrl = $result['QueueUrl'];
echo $queueUrl;
// 'VisibilityTimeout' => 300,

    $result = $client->receiveMessage(array(
        'QueueUrl' => $queueUrl
    ));
    echo "<br>";
    if ($result['Messages'] == null) {
        // No message to process
        echo "Exiting";
        exit;
    }

    // Get the message information
    $Queueresult = array_pop($result['Messages']);
    $ReceiptHandle = $Queueresult['ReceiptHandle'];
    $body = $Queueresult['Body'];
    echo $body;
 	
	$db="app";

	$conn = new mysqli($host,$username, $password,$db);
	$sql = "SELECT * FROM records WHERE receipt = '$body'";
	$result = $conn->query($sql);
	$res = $result->fetch_assoc();
	
	$rawurl=$res['s3rawurl'];
	copy($rawurl, '/tmp/file.jpeg');
	$raw = '/tmp/file.jpeg';
	$finish = '/tmp/rendered.png';

	// load the "stamp" and photo to apply the water mark to
	$stamp = imagecreatefrompng('IIT-logo.png');  // grab this locally or from an S3 bucket probably easier from an S3 bucket...
	$im = imagecreatefromjpeg($raw);  // replace this path with $rawurl

	//Set the margins for the stamp and get the height and width of the stamp image
	$marge_right=0.5;
	$marge_bottom=0.5;
	$sx = imagesx($stamp);
	$sy = imagesy($stamp);

	imagecopy($im,$stamp,imagesx($im) - $sx -$marge_right, imagesy($im) - $sy -$marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

	//output and free memory
	//header('Content-type: image/png');
	imagepng($im,$finish);

	imagedestroy($im);

	$bucket = 'raw-jjp';
	$keyname = 'Rendered.jpeg';
	
	$s3= S3Client::factory(array(
		'region'  => 'us-east-1',
		'version' => 'latest',
		'credentials' => false
	));
    $result = $s3->putObject(array(
    	'Bucket'     => $bucket,
    	'Key'        => $keyname,
    	'SourceFile' => $finish,
		'ACL' 	     => 'public-read'
	));
    $s3finished=$result['ObjectURL'];
    $status = '1';

   	$sql = "UPDATE records SET s3finishedurl='$s3finished', status='$status' WHERE receipt = '$body'";
	$result = $conn->query($sql);



   $result = $client->deleteMessage(array(
    // QueueUrl is required
    'QueueUrl' => $queueUrl,
    // ReceiptHandle is required
    'ReceiptHandle' => $ReceiptHandle,
	));
   	shell_exec('rm $finish');
   	$conn->close();
?>