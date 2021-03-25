<?php
include 'connect.php';

if (isset($_GET['id'])) {
	$id = mysqli_real_escape_string($conn, $_GET['id']);
	$query = "SELECT * FROM instructor WHERE instructor_id = '$id'";
	$query = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($query)) {
		$imageData = $row["photo"];
	}
	header("content-type: image/jpeg");
	echo $imageData;
}
?>