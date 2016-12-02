<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gallery</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link href="css/foundation.css" rel="stylesheet" type="text/css" />
  <link href="css/twentytwenty.css" rel="stylesheet" type="text/css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, scale-to-fit=no">
</head>

<body>
<?php
include 'nav.php';
include '../password.php';
$mysqli = new mysqli($_SESSION["hostname"],$username,$password,"app");
$user = $_SESSION['user'];
$sql = "SELECT * FROM records where email = '$user' AND status='1'";

$result = $mysqli->query($sql);
?> 

<div class="background">
<div class="transbox">
<h3> 
<?php	
while($row = $result->fetch_assoc())
{
   $title = $row['filename']." ";
   $raw = $row['s3rawurl']." ";
   $finish = $row['s3finishedurl']." ";
?>
    <div class="row" style="margin-top: 2em;">
      <div class="large-4 columns">
      </div>
      <div class="large-8 columns">
        <div class="twentytwenty-container">
          <img src=<?php echo $raw ?> border=3 height=700 width=700 />
          <img src=<?php echo $finish ?> border=3 height=700 width=700 />
        </div>
      </div>
    </div>
<?php
} ?>
</div>
</div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="js/jquery.event.move.js"></script>
    <script src="js/jquery.twentytwenty.js"></script>
    <script>
    $(window).load(function(){
      $(".twentytwenty-container[data-orientation!='vertical']").twentytwenty({default_offset_pct: 0.7});
      $(".twentytwenty-container[data-orientation='vertical']").twentytwenty({default_offset_pct: 0.3, orientation: 'vertical'});
    });
<?php
$mysqli->close();
?>
</body>
</html>