<!DOCTYPE html>
<html lang="en">
  <head>

    <title>Student Homepage</title>
    <link rel="icon" href="images/hu_logo.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="main.css?version=51">
	<script src="jquery-1.12.0.min.js" type="text/javascript"></script>
	<script type="text/javascript">
        $(document).ready(function(){

            $("#sel_course").change(function(){
                var course_Id = $(this).val();

                $.ajax({
                    url: 'getStudentSections.php',
                    type: 'post',
                    data: {course:course_Id},
                    dataType: 'json',
                    success:function(response){

                        var len = response.length;
                        $("#sel_section").empty();
                        for( var i = 0; i<len; i++){
                            var id = response[i]['id'];
                            var section = response[i]['name'];

                            $("#sel_section").append("<option value='"+section+"'>"+section+"</option>");

                        }
                    }
                });
            });

        });
    </script>
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
			color: white;
			background-color: black;
			border-color: white;
			opacity:0.75;
			width: 30%;
			height: 5%;
			border-left-color: none;
			border-right-color: none;
			border-top-color: none;
			border-bottom-color: none;
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
	session_start();
	if (!isset($_SESSION['loggedIn'])) {
		echo '<script>window.alert("You\'re not yet signed in");window.location.href="/hu/signin.php";</script>';
	}
	include_once 'header.php'; 
	include_once 'connect.php';
	$id = $_SESSION['id'];
	$mac = get_student_mac($conn, $id);

	if (is_null($mac)) {
		
		$MAC = get_device_mac($conn);

		$MAC = "UPDATE student SET mac_address = \"$MAC\" WHERE student_id = \"$id\"";
		$MAC = mysqli_query($conn, $MAC);
		header('Location:student.php');
	}

	if (isset($_POST['upload'])) {
		$imageName = mysqli_real_escape_string($conn, $_FILES["image"]["name"]);
		$imageData = mysqli_real_escape_string($conn, file_get_contents($_FILES["image"]["tmp_name"]));
		$imageType = mysqli_real_escape_string($conn, $_FILES["image"]["type"]);

		if ((strpos($imageType, 'jpg') !== false) || (strpos($imageType, 'jpeg') !== false) || 
			(strpos($imageType, 'png') !== false)) {
			make_login_detail($conn, $id);
			$query = "UPDATE student SET photo = \"$imageData\" WHERE student_id = \"$id\"";
			$query = mysqli_query($conn, $query);
		}
		else
			echo '<script>window.alert("You cannot upload this type of file");window.location.href="/hu/student.php";</script>';
	}
	if (isset($_POST['delete'])) {
		$sql = "UPDATE student SET photo = NULL WHERE student_id = \"$id\"";
		$sql = mysqli_query($conn, $sql);
	}
	?>

	<div class="page-bg"></div>

	<div class="container features"> <!-- User Details -->
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-12"> <!-- Photo -->
				<div style="text-align: center;">
					<?php
					$photo = "SELECT photo FROM student where student_id = \"$id\"";
					$photo = mysqli_query($conn, $photo);
					$photo = mysqli_fetch_array($photo);
					if(is_null($photo['photo'])){
						?>
						<img src="images/default_pic.png" style="width: 50%; height: 70%;" style="border: 2px; border-color: white;">
						<?php
					}
					else{
						?>
						<img src="showStudentImage.php?id=<?php echo $id ?>" style="width: 50%; height: 70%;" >
						<?php
					}
					?>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12" style="padding-left:0px;"><!-- Photo Editing -->
				<div style="text-align: center;">
					<form action="student.php" method="POST" enctype="multipart/form-data">
						<input  style=" background-color: black; opacity: 0.75;border-color: white; 
										color: white; width: 70%; padding: 3%; height: 25%;" 
							type="file" id="rcorners2" name="image"><br>
						<input  class="button" style="background-color: black; opacity: 0.75;border-color: white; color: white; width: 70%; padding: 3%; height: 25%;" 
								type="submit" id="rcorners2" name="upload" value="upload"><br>
						<input  class="button" style="background-color: black; opacity: 0.75;border-color: white; color: white; width: 70%; padding: 3%; height: 25%;" 
								type="submit" id="rcorners2" name="delete" value="Delete Photo"><br>
					</form>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12" style="text-align: center;"><!-- User -->
				<?php
				
				$name  = "SELECT major FROM student WHERE student_id = $id";
				$name  = mysqli_query($conn, $name);
				$name  = mysqli_fetch_array($name);
				?>
				<div><?php echo $id; ?></div><br>
				<div><?php echo get_user_name($id, $conn);?></div><br>
				<div><?php echo $name['major'] ?></div>
				<br>
				<form action="student.php" method="POST">	
					<button class="button" style="width: 40%; height: auto;background-color: #cc0000;" 
							name="signOut">
						<span>Sign out</span>
					</button>
				</form>
				<?php
				if (isset($_POST['signOut'])) {
					session_destroy();
					header('Location: signin.php');
				}
				?>
			</div>

		</div>
	</div>

	<div class="container features">
		<div class="row">
			<div class="col-lg-4 col-md-12 col-sm-12">
				<h5>Add Courses</h5>
				<hr>
				<form method="POST" action="student.php" autocomplete="off">
					<input  style="background-color: black; border-color: white;"
							type="text" id="rcorners2" placeholder="Course Id" name="course_id"><br>
					<div class="errors">
						<strong id="errorOne"></strong>
					</div>

					<input  style="background-color: black; border-color: white;"
							type="text" id="rcorners2" placeholder="Section Number" name="section_number">
					<br>
					<div class="errors">
						<strong id="errorTwo"></strong> 
					</div>

					<button class="button" name="addCourse" style="height: auto;">
						<span>Add</span>
					</button>
					
					<div class="errors">
						<strong id="errorThree"></strong> 
					</div>
				</form>
			</div>

			<div class="col-lg-8 col-md-12 col-sm-12" > <!--Student's Registration Table -->
				<h5>Registration Table</h5>
				<hr>
				<div style="text-align: center;">
					<?php 
					$query = "  SELECT  course.course_id, course.course_name, 
										course.section_number, DATE_FORMAT(course.time, \"%H:%i\")
								FROM course, registration
								WHERE course.course_id = registration.course_id
								AND   course.section_number = registration.section_number
								AND   registration.student_id = \"$id\" 
								ORDER BY course.time";
					$result = mysqli_query($conn, $query);

				 	?>
				 	<table id="rcorners2" style="border: none; background-color: black; font-size: 80%;" class="table table-striped table-responsive">
				 		<thead>
				 			<tr style="color: white"> 
								<th scope="col" style="width: 20%; vertical-align: top;">Course Id</th>
								<th scope="col" style="width: 30%; vertical-align: top;">Course Name</th>
								<th scope="col" style="width: 10%; vertical-align: top;">Section Number</th>
								<th scope="col" style="width: 10%; vertical-align: top;">Time</th>
								<th scope="col" style="width: 30%; vertical-align: top;">Mark Attendance</th>
							</tr>
				 		</thead>
							
				 		<tbody>
				 			<?php
				 			$date = date("Y-m-d");
				 			if($result){
					 			while ($row = mysqli_fetch_array($result)) { ?>
									<tr style="color: white;">
										<td scope="row"><?php echo $row['course_id']; ?></td>
										<td><?php echo $row['course_name']; ?></td>
										<td><?php echo $row['section_number']; ?></td>
										<td><?php echo $row[3]; ?></td>
										<td><a href="markAttendance.php?courseId=<?php echo $row[0] ?>&section=<?php echo $row[2] ?>&date=<?php echo $date ?>" style="color: #005eab;"> Mark Attendance</a></td>
									</tr>
								<?php } 
				 			}?>
				 		</tbody>
					</table>
				</div>				
			</div>

		</div> 
	</div>
	
	<div class="container features" style="z-index: 15;">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12"><!--  Absence Form -->
				<h5>Absence Table</h5>
				<hr>
				<div style="text-align: center;">
					<form action="student.php" method="POST">
						<select id="sel_course" class="rcorners2" name="course" 
								style=" background-color: black; border-color: white; color: white; opacity: 0.75; border-width: 2px; border-left-color: white; border-radius: 25px; width: 200px; height: 45px; padding: 1%;" >
				            <option value="0">- Course Name -</option>
				            <?php 
				            $sql_course= "  SELECT DISTINCT course.course_name, registration.course_id 
				            				FROM registration, course
				            				WHERE student_id=\"$id\"
				            				AND   registration.course_id = course.course_id";
				            $sql_data = mysqli_query($conn,$sql_course);

				            while($row = mysqli_fetch_assoc($sql_data) ){
				            	$absences = "SELECT count(*) FROM absence 
				            				WHERE student_id = \"$id\" AND course_id =".$row['course_id'];
				            	$absences = mysqli_query($conn, $absences);
				            	$absences = mysqli_fetch_array($absences);
				                $courseId = $row['course_id'];
				                $courseName = $row['course_name'] ." (" . $absences['count(*)'] .")";
				              
				                echo "<option value='".$courseId."' >".$courseName."</option>";
				            }
				            ?>
				        </select>
				        <select id="sel_section" class="rcorners2" name="section" 
				        		style=" background-color: black; border-color: white; color: white; opacity: 0.75;
										border-width: 2px; border-left-color: white; border-radius: 25px; width: 200px; 
										height: 45px; padding: 1%;" >
				            <option value="0">- Section Number -</option>
				        </select><br>
				        <button class="button" name="viewAbsence" 
				        		style="background-color: black; height: auto; width: 150px;">
				        	<span>View Absence</span>
				        </button>
				        <div class="errors" style="width: 70%; text-align: center;">
							<strong id="errorFour"></strong>
						</div>
					</form>
				</div>
			</div>
			<div class="col-lg-2  col-md-2 col-sm-12"></div>
			<div class="col-lg-10 col-md-10 col-sm-12"> <!--Absence Table -->
				<hr>
				<div style="text-align: center;">
					<?php
						if (isset($_POST['viewAbsence'])) {
							make_login_detail($conn, $id);
							?>
							<table id="rcorners2" style="width: 100%; border: none; background-color: black; font-size: 80%; margin: 0px;" class="table table-striped">
						 		<thead>
						 			<tr style="color: white"> 
										<th scope="col" style="width: 25%;vertical-align:top;">Course Id</th>
										<th scope="col" style="width: 25%;vertical-align:top;">Course Name</th>
										<th scope="col" style="width: 25%;vertical-align:top;">Section Number</th>
										<th scope="col" style="width: 25%;vertical-align:top;">Date</th>
									</tr>
						 		</thead>
							<?php
							$where = "";
							if(!empty($_POST['course'])){
								$course = $_POST['course'];
								$where .= " AND absence.course_id = \"$course\"";
							}
							if(!empty($_POST['section'])){
								$section = $_POST['section'];
								$where .= " AND absence.section_number = \"$section\"";
							}
								$sql = "SELECT  course.course_name, absence.course_id, 
												absence.section_number, absence.date
										FROM    `absence`, `course`
										WHERE 	absence.course_id = course.course_id
										AND 	absence.section_number = course.section_number
										AND 	absence.student_id = \"$id\""
										.$where .
										" ORDER BY absence.date DESC";
							$sql = mysqli_query($conn, $sql);
							$row_count = mysqli_num_rows($sql);
							?>
								<tbody>
									<?php
									if($sql){
										while ($row = mysqli_fetch_array($sql)) { ?>
										<tr style="color: white;">
											<td><?php echo $row['course_id']; ?></td>
											<td><?php echo $row['course_name']; ?></td>
											<td><?php echo $row['section_number']; ?></td>
											<td><?php echo $row['date']; ?></td>
										</tr>
									<?php }
									}
									if(!empty($_POST['course'])){
						 				?>
										<tr>
							 				<td colspan="4" class="text-danger">
							 					Number of Absences: <?php echo $row_count; ?>
							 				</td>
							 			</tr>
							 		<?php } ?>
								</tbody>
							</table>
							<?php
						}
					?>
				</div>
			</div>
		</div> 
	</div>

	<?php include_once 'footer.php';?>
	<?php
	$valid = "TRUE";
/*
1. there exist a course with the given number.
2. there exist a section with the given number.
3. there is no another course in the same time.
4. He didn't register the same course.
*/
	if (isset($_POST['addCourse'])) {
		if(!empty($_POST['course_id']) && !empty($_POST['section_number'])){
			$course_id = trim(mysqli_real_escape_string($conn, $_POST['course_id']));
			$section   = trim(mysqli_real_escape_string($conn, $_POST['section_number']));

			//To check if there is a valid course
			$courseCheck = "SELECT course_id FROM course WHERE course_id = \"$course_id\"";
			$courseCheck = mysqli_query($conn, $courseCheck);
			$courseCheckCount = mysqli_num_rows($courseCheck);
			if($courseCheckCount == 0){
				$valid = "FALSE";
				?>
				<script>
					document.getElementById("errorOne").innerHTML = "<strong style=\"color:red\">&#9733 </strong>Course Id doesn't exist.";
				</script>
				<?php
			}

			if($valid == "TRUE"){//To check if there is a valid section
				$sectionCheck = "SELECT section_number FROM course  WHERE course_id = \"$course_id\" 
																	AND section_number = \"$section\"";
				$sectionCheck = mysqli_query($conn, $sectionCheck);
				$sectionCheckCount = mysqli_num_rows($sectionCheck);
				if($sectionCheckCount == 0){
					$valid = "FALSE";
					?>
					<script>
						document.getElementById("errorTwo").innerHTML = "<strong style=\"color:red\">&#9733 </strong>This Section doesn't exist.";
					</script>
					<?php
				}
			}


			if ($valid == "TRUE"){// If there isn't the same course twice for the same student
				$courseCheck2 = "SELECT course_id FROM registration WHERE course_id = \"$course_id\" AND student_id=$id";
				$courseCheck2 = mysqli_query($conn, $courseCheck2);
				$courseCheckCount2 = mysqli_num_rows($courseCheck2);
				if($courseCheckCount2 == 1){
					$valid = "FALSE";
					?>
					<script>
						document.getElementById("errorThree").innerHTML = "<strong style=\"color:red\">&#9733</strong>You already registered the course.";
					</script>
					<?php
				}
			}

			if ($valid == "TRUE"){// If there isn't another course in the same time.
				$timeOne = get_course_time($conn, $course_id, $section);

				$timeCheck = "  SELECT DATE_FORMAT(course.time, \"%H:%i\")
								FROM course, registration
								WHERE registration.student_id = \"$id\"
								AND   registration.course_id = course.course_id
								AND   registration.section_number = course.section_number";
				$timeCheck = mysqli_query($conn, $timeCheck);
				while ($row = mysqli_fetch_array($timeCheck)){
					if ($timeOne == $row[0])
						$valid = "FALSE";
						?>
						<script>
							document.getElementById("errorThree").innerHTML = "<strong style=\"color:red\">&#9733</strong>You already have a course at <?php echo $timeOne ?>.";
						</script>
						<?php
				}
			}

			if ($valid == "TRUE"){
				make_login_detail($conn, $id);
				$insert = "INSERT INTO registration VALUES (\"$id\", \"$course_id\", \"$section\")";
				$insert = mysqli_query($conn, $insert);
				header('Location: student.php');
			}
		}
	}
	?>

<script src="jquery-3.3.1.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="main.js"></script>



</body>
</html>