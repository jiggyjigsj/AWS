<?php 
include 'nav.php';
include '../password.php';

if ($user != "admin"){
  header("Location: gallery.php");
} else {
//Insert into table:
  $mysqli = new mysqli($_SESSION["hostname"],$username,$password,"app");

  
$admin = 'CREATE TABLE IF NOT EXISTS admin
(
  id INT NOT NULL AUTO_INCREMENT,
  feature VARCHAR(50) NOT NULL UNIQUE,
  status INT(1) NULL,
  PRIMARY KEY(id)
)';
$result = $mysqli->query($admin);

$chk_adm = "SELECT * FROM admin";
$check_admin = $mysqli->query($chk_adm);

$row_cnt = $check_admin->num_rows;

if ($row_cnt == 0) { 

$insert = 'INSERT INTO admin (`id`,`feature`,`status`) VALUES (1,"upload",1)';

$insert_usr = $mysqli->query($insert);
}
$up = "SELECT * FROM admin WHERE feature='upload'";

$result = $mysqli->query($up);
$res = $result->fetch_assoc();
$upload = $res['status'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>

</style>
  </head>
  <body>
    <br><br><br>
<div class="background">
<div class="transbox">
    <div class="jumbotron text-center">
    <h1>Admin Feature</h1> 
<center>
<form class="form-inline" action="#" method="post">
<h4>Upload Feature:
<input type="radio" name="upload"
<?php if ($upload == '1') echo "checked ";?>
value="1">On
<input type="radio" name="upload"
<?php if ($upload == '0') echo "checked ";?>
value="0">Off</h4>
<input class="btn btn-danger" type="submit" value="Submit">
</form></center>
<?php

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
  $fea = $_POST['upload'];
  echo $fea;
  //UPDATE table_name SET column1=value1,column2=value2,... WHERE some_column=some_value;
$up = "UPDATE admin SET status=$fea WHERE feature='upload'";

$result = $mysqli->query($up);
}
$mysqli->close();
}
?>
 </div></div></div>
</body>
</html>
