<!DOCTYPE html>
<html lang="en">
	<head>
	    <title>Student Homepage</title>
	    <link rel="icon" href="images/hu_logo.png">
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="main.css?version=51">
		<style type="text/css">
			.table {
				width: 80% !important; 
			}
			table {
				border-collapse: collapse;
			}
			table td, table th {
				border: 2px solid white;
			}
			table tr:first-child th {
				border-top: 0;
			}
			table tr:last-child td {
				border-bottom: 0;
			}
			table tr td:first-child,
			table tr th:first-child {
				border-left: 0;
			}
			table tr td:last-child,
			table tr th:last-child {
				border-right: 0;
			}
			.errors{
				text-align: left;
				margin-left: 17%;
			}
			h5{
				text-align: center;
				font-weight: bold;
			}
			.button {
				opacity: 0.75;
				display: inline-block;
				border-radius: 4px;
				background-color: black;
				border: none;
				color: #FFFFFF;
				text-align: center;
				font-size: 100%;
				width: 32.99999%;
				height: 15%;
				transition: all 0.5s;
				cursor: pointer;
				margin: 5px;
			}
			form{
				text-align: center;
				width: 100%;
				height: 100%;
			}
			.features{
				background-color: rgb(0, 0, 0);
				background-color: rgba(0, 0, 0, 0.4);
				color: white;
				font-weight: bold;
				width: 80%;
			}
			#rcorners2{
				width: 30%;
				height: 5%;
				border-left-color: none;
				border-right-color: none;
				border-top-color: none;
				border-bottom-color: none;
				border-color: none;
			}
			.page-bg {
				background: url('images/hu5.jpg');
				-webkit-filter: blur(10px);
				-moz-filter: blur(5px);
				-o-filter: blur(5px);
				-ms-filter: blur(5px);
				filter: blur(5px);
				position: fixed;
				background-attachment: scroll;
				background-position: center;
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				z-index: -1;
				background-size: cover;
			}
		</style>
	</head>
  <body>
	<?php
		include 'connect.php';
		include 'header.php';
		session_start();
		$courseId = $_GET['courseId'];
		$section  = $_GET['section'];
		$id 	  = $_SESSION['id'];

		$startTime = get_start_time($conn, $courseId, $section);
		$endTime   = $startTime + 2700;
		$currentTime   = date('H:i', strtotime('+1 hour'));
		$currentTime   = strtotime($currentTime);
	?>
	<div class="page-bg"></div>
	<div class="container features">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<form method="POST" autocomplete="OFF">
					<input 	style="background-color:black;border-color:white; color:white; width:30%;" 
							type="text" id="rcorners2" name="course_password" placeholder="Lecture Password"> <br>
					<div class="errors" style="margin-left: 35%;">
						<strong id="errorOne"></strong>
					</div> 
					<button class="button" name="mark" style="height: 25%; width: 30%">
						<span>Mark Attendance</span>
					</button>
				</form>
			</div>
		</div>
	</div>

	<?php
	if (isset($_POST['mark'])){
		$date = date('Y-m-d');
		$sql = "SELECT * 
				FROM absence 
				WHERE course_id = \"$courseId\" AND section_number = \"$section\" AND date = \"$date\"";
		$sql = mysqli_query($conn, $sql);
		if (mysqli_num_rows($sql) > 0) {
			if ($currentTime < $endTime && $currentTime > $startTime) {
				if (!empty($_POST['course_password'])) {

					$pass = $_POST['course_password'];

					$DEVICE_MAC  = get_device_mac($conn);
					$STUDENT_MAC = get_student_mac($conn, $id);
					$instructor  = get_instructor($conn, $courseId, $section);
					$course_pass = get_course_password($conn, $instructor);

					if ($course_pass == $pass) {
						if($DEVICE_MAC == $STUDENT_MAC){
							make_login_detail($conn, $id);
							$delete = " DELETE FROM absence
										WHERE student_id = \"$id\"
										AND   course_id  = \"$courseId\"
										AND   section_number = \"$section\"";
							$delete = mysqli_query($conn, $delete);
							echo '<script>window.alert("Attendance Taken");window.location.href="/hu/student.php";</script>';
						}
						else{
							echo '<script>window.alert("You can\'t take attendance from this device (different MAC Address)");window.location.href="/hu/student.php";</script>';
						}
					}
					else{
							echo '<script>window.alert("The password you entered is wrong");window.location.href="/hu/student.php";</script>';
					}
				}
				else{
					echo '<script>window.alert("Password is required");window.location.href="/hu/student.php";</script>';
				}
			}
			else{
				echo '<script>window.alert("You can\'t mark your attendance at this moment");window.location.href="/hu/student.php";</script>';
			}
		}
		else{
			echo '<script>window.alert("Instructor hasn\'t marked attendance yet.");window.location.href="/hu/student.php";</script>';
		}

	}
	include_once 'footer.php';
	?>
  </body>