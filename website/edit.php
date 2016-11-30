<?php
require 'vendor/autoload.php';

$sqsclient = new Aws\Sqs\SqsClient([
'region'  => 'us-west-2',
'version' => 'latest',
'credentials' => [
	'key'    => 'AKIAIKYMAUHZJ7CYJEJQ',
	'secret' => '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']
]);

// Code to retrieve the Queue URLs
$result = $sqsclient->getQueueUrl([
    'QueueName' => 'MyQueue', // REQUIRED
]);
$queueUrl = $result['QueueUrl'];
echo $queueUrl;
// 'VisibilityTimeout' => 300,

    $result = $sqsclient->receiveMessage(array(
        'QueueUrl' => $queueUrl
    ));
    echo "<br>";
    echo $result;
    if ($result['Messages'] == null) {
        // No message to process
        echo "Exiting";
        exit;
    }

    // Get the message information
    $Queueresult = array_pop($result['Messages']);
    $ReceiptHandle = $Queueresult['ReceiptHandle'];
    $message_json = $Queueresult['Body'];

    echo $message_json;

   /* $result = $client->deleteMessage(array(
    // QueueUrl is required
    'QueueUrl' => $queueUrl,
    // ReceiptHandle is required
    'ReceiptHandle' => $ReceiptHandle,
	));*/
?>