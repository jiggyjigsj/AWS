<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Database Table</title>
</head>
<body>
<?php

include '../password.php';

$conn = new mysqli($hostname,$username, $password, "school");

$sql = "SELECT * FROM student";
$result = $conn->query($sql);

	echo '<table style="text-align:center;">';
	echo '<tr>';
	echo '<th> ID </th>';
	echo '<th> Name </th>';
	echo '<th> Age </th>';
	echo '</tr>';
foreach($result as $row)
{
	echo '<tr>';
	echo '<td>'. $row['id'] . '</td>';
	echo '<td>'. $row['name'] . '</td>';
	echo '<td>'. $row['age'] . '</td>';
	echo '</tr>';
}
	echo '</table>';

$conn->close();
?>
</body>
</html>
