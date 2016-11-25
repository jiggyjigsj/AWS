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
include 'password.php';
  $mysqli = new mysqli($_SESSION["hostname"],$username,$password,"app");

$chk_adm = "SELECT * FROM admin where feature = 'upload'";
$result = $mysqli->query($chk_adm);
$res = $result->fetch_assoc();

$error ='<span style="color: #f00;">Required</span>';
?>
<br><br><br><br>
<form action="uploader.php" method="POST" enctype="multipart/form-data">
User: <input type="text" name="user" value=<?php echo $user ?> readonly><br>
Phone: <input type="text" name="phone" value="6304074614" ><br>
File Name:<?php echo $error ?> <input type="text" name="filename"><br>
Select File:<?php echo $error ?> <input type="file" name="fileToUpload" id="fileToUpload">
<?php
if ($res['status'] == 1) { ?><input type="submit" value="Submit"> <?php
} else {echo "<h4>Upload feature has been turned off</h4>"; }?>
</form>

</body>
</html>
