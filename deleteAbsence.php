<?php
include 'connect.php';
include 'instructor.php';
	session_start();

	$courseId = $_GET['courseId'];
	$section  = $_GET['section'];
	$date 	  = $_GET['date'];
	$student  = $_GET['student'];

	make_login_detail($conn, $id);
	$query    = "DELETE FROM absence
				 WHERE student_id= \" $student\"
				 AND   course_id = \" $courseId\"
				 AND   date      = \"$date\"";
	$query    = mysqli_query($conn, $query);
	header('Location:instructor.php');
?>