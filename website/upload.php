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
  $mysqli = new mysqli($_SESSION["hostname"],$username,$password,"app");

$chk_adm = "SELECT * FROM admin where feature = 'upload'";
$result = $mysqli->query($chk_adm);
$res = $result->fetch_assoc();
$error ='<span style="color: #f00;">Required</span>';
?>
<div class="background">
<div class="transbox">
<br><br><br><br>
    <div class="jumbotron text-center">
<h3><form class="form-inline" action="uploader.php" method="POST" enctype="multipart/form-data">
User: <input type="text" class="form-control" name="user" size "25" value=<?php echo $user ?> readonly><br>
Phone: <input type="text" class="form-control" name="phone" size "25" value="6304074614" ><br>
File Name:<?php echo $error ?> <input type="text" class="form-control"  size "25"  name="filename"><br>
Select File:<?php echo $error ?> <input type="file" class="form-control" size "25"  name="fileToUpload" id="fileToUpload">
</h3><?php
if ($res['status'] == 1) { ?><input type="submit" class="btn btn-danger" value="Submit"> <?php
} else {echo "<h4>Upload feature has been turned off</h4>"; }?>
</form>
</div>
</div>
</div>
</body>
</html>
