<?php 
session_start();
include '../password.php';

if (isset($_SESSION["username"]) && !empty($_SESSION["username"]))
{
header("Location: gallery.php");
} else {
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, scale-to-fit=no">
  </head>
  <body>
<form action="#" method="post">
Username: <input type="text" name="user"><br>
Password: <input type="password" name="password"><br>
<input type="submit" value="Submit">
</form>
<?php
if(isset($_POST['user']) && $_POST['user'] != '' && isset($_POST['password']) && $_POST['password'] != '')
{
  $mysqli = new mysqli($_SESSION["hostname"],$username,$password,"app");

  $usr = $_POST['user'];
  $pass = $_POST['password'];
  $chk_usr = "SELECT * FROM users WHERE username='$usr' AND password='$pass'";
  $result = $mysqli->query($chk_usr);
  $num = $result->num_rows;
  if($num == '1') {
    $_SESSION["username"] = $usr;
    header("Location: gallery.php");

  } else {
  echo '<span style="color: #f00;">Wrong Username or Password Try Again</span>';
  }


$mysqli->close();
}
}
?>
</body>
</html>
