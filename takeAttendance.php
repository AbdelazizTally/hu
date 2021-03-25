<?php
	include 'connect.php';
	session_start();
	$id = $_SESSION['id'];
	$courseId = $_GET['courseId'];
	$section  = $_GET['section'];
	$date 	  = $_GET['date'];

	$startTime = get_start_time($conn, $courseId, $section);
	$endTime   = $startTime + 2700; // 45 Minutes after the start of the lecture.
	$date = date("Y-m-d");
	$currentTime   = date('H:i', strtotime('+1 hour'));
	$currentTime   = strtotime($currentTime);



	if ($currentTime < $endTime && $currentTime > $startTime) {

		//check if attendance alraedy taken:
		$sql = "SELECT * 
				FROM absence 
				WHERE course_id = \"$courseId\" AND section_number = \"$section\" AND date = \"$date\"";
		$sql = mysqli_query($conn, $sql);
		if (mysqli_num_rows($sql) == 0) {
			make_login_detail($conn, $id);

			$count = get_student_count($conn, $courseId, $section);
			take_attendance($conn, $courseId, $section);
			echo '<script>window.alert("Attendance taken for '.$count.' Students");window.location.href="/hu/instructor.php";</script>';
		}
		else
			echo '<script>window.alert("Attendance already taken for today");window.location.href="/hu/instructor.php";</script>';

	}
	else{
		echo '<script>window.alert("You can\'t take attendance at this moment");window.location.href="/hu/instructor.php";</script>';
	}
?>