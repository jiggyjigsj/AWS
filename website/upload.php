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
$error ='<span style="color: #f00;">Required</span>';
?>
<br><br><br><br>
<form action="uploader.php" method="POST" enctype="multipart/form-data">
User: <input type="text" name="user" value=<?php echo $username ?> readonly><br>
Phone: <input type="text" name="phone" value="6304074614" ><br>
File Name:<?php echo $error ?> <input type="text" name="filename"><br>
Select File:<?php echo $error ?> <input type="file" name="fileToUpload" id="fileToUpload">
<input type="submit" value="Submit">
</form>
</body>
</html>