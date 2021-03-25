<!DOCTYPE html>
<html lang="en">
  <head>

    <title>Dean Homepage</title>
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
                    url: 'getAllSections.php',
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
		#sel_section, #sel_course{
			background-color: black; 
			border-color: white; 
			color: white; 
    		opacity: 0.75;
    		border-width: 2px; 
    		border-left-color: white;
    		border-radius: 25px; 
    		width: 200px; 
    		height: auto; 	
    		padding: 1%;
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
			height: auto;
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
			height: auto;
			border-left-color: white;
			border-color: white;
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
	$insValid = "TRUE";
	$stdValid = "TRUE";
	$id = $_SESSION['id'];

	if (isset($_POST['upload'])) {
		$imageName = mysqli_real_escape_string($conn, $_FILES["image"]["name"]);
		$imageData = mysqli_real_escape_string($conn, file_get_contents($_FILES["image"]["tmp_name"]));
		$imageType = mysqli_real_escape_string($conn, $_FILES["image"]["type"]);

		if ((strpos($imageType, 'jpg') !== false) || (strpos($imageType, 'jpeg') !== false) || 
			(strpos($imageType, 'png') !== false)) {
			make_login_detail($conn, $id);
			$query = "UPDATE dean SET photo = \"$imageData\" WHERE dean_id = \"$id\"";
			$query = mysqli_query($conn, $query);
		}
		else
			echo '<script>window.alert("You cannot upload this type of file");window.location.href="/hu/dean.php";</script>';
	}
	if (isset($_POST['delete'])) {
		$sql = "UPDATE dean SET photo = NULL WHERE dean_id = \"$id\"";
		$sql = mysqli_query($conn, $sql);
	}
	?>

	<div class="page-bg"></div>


	<div class="container features"> <!-- User Details -->
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-12"> <!-- Photo -->
				<div style="text-align: center;">
					<?php
					$photo = "SELECT photo FROM dean where dean_id = \"$id\"";
					$photo = mysqli_query($conn, $photo);
					$photo = mysqli_fetch_array($photo);

					if(is_null($photo['photo'])){
						?>
						<img src="images/default_pic.png" style="width: 50%; height: 70%;" style="border: 2px; border-color: white;">
						<?php
					}
					else{
						?>
						<img src="showDeanImage.php?id=<?php echo $id ?>" style="width: 50%; height: 70%;" >
						<?php
					}
					?>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12" style="padding-left:0px; "> <!-- Photo Editing -->
				<div style="text-align: center;">
					<form action="dean.php" method="POST" enctype="multipart/form-data">
						<input  style=" background-color: black; opacity: 0.75;border-color: white; 
										color: white; width: 70%; padding: 3%; height: auto;" 
							type="file" id="rcorners2" name="image"><br>
						<input  class="button" style="background-color: black; opacity: 0.75;border-color: white; color: white; width: 70%; padding: 3%; height: auto;"
								type="submit" id="rcorners2" name="upload" value="upload"><br>
						<input  class="button" style="background-color: black; opacity: 0.75;border-color: white; color: white; width: 70%; padding: 3%; height: auto;"
								type="submit" id="rcorners2" name="delete" value="Delete Photo"><br>
					</form>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12"  style="text-align: center;">  <!-- User -->
				<div><?php echo $id; ?></div><br>
				<div>Dr. <?php echo get_user_name($id, $conn); ?></div><br>
				<form action="dean.php" method="POST">	
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

	<div class="container features" style="z-index: 15;">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-12"> <!-- Course -->
				<h5>COURSE</h5>
				<hr>
				<form method="POST" action="dean.php" autocomplete="off">
					<input  style="background-color: black; opacity: 0.75;border-color: white; color: white;" 
							type="text" id="rcorners2" placeholder="Course Name" name="course_name"><br>
					<div class="errors">
						<strong id="errorOne"></strong>
					</div>

					<input  style="background-color: black; opacity: 0.75;border-color: white; color: white" 
							type="text" id="rcorners2" placeholder="Course Id" name="course_id"><br>
					<div class="errors">
						<strong id="errorTwo"></strong> 
					</div>

					<input  style="background-color: black; opacity: 0.75;border-color: white; color: white" 
							type="text" id="rcorners2" placeholder="Section Number" name="section_number"><br>
					<div class="errors">
						<strong id="errorThree"></strong>
					</div> 

					<input 	style="background-color: black; opacity: 0.75;border-color: white; color: white" 
							type="text" id="rcorners2" placeholder="Instructor Id" name="instructor_id"><br>
					<div class="errors">
						<strong id="errorFour"></strong>
					</div>

					<input 	style="background-color: black; opacity: 0.75;border-color: white; color: white" 
							type="text" id="rcorners2" placeholder="Course Time" name="course_time"><br>
					<div class="errors">
						<strong id="errorFive"></strong>
					</div>

					<button class="button" name="addCourse"><span>Add</span></button>
					<button class="button" name="removeCourse"><span>Remove</span></button>
				</form>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12"> <!-- Instructor -->
				<h5>INSTRUCTOR</h5>
				<hr>
				<form method="POST" action="dean.php" autocomplete="off">
					<input style="background-color: black; opacity: 0.75;border-color: white; color: white" type="text" id="rcorners2" placeholder="Name" name="instructor_name"><br>
					<?php
					if (isset($_POST['addInstructor'])){
						if (empty($_POST['instructor_name'])){
							$insValid = "FALSE";
							?>
							<div class="errors">
								<strong id="errorSix">
									<strong style="color: red">&#9733</strong>
									Instructor name is required.
								</strong>
							</div>
							<?php
						}
					}
					?>


					<input style="background-color: black; opacity: 0.75;border-color: white; color: white" type="text" id="rcorners2" placeholder="ID" name="instructor_id"><br>		
					<?php
					if (isset($_POST['addInstructor'])){
						if (!empty($_POST['instructor_id'])){
							$insLength = strlen($_POST['instructor_id']);
							$instructorId = trim(mysqli_real_escape_string($conn, $_POST['instructor_id']));
							$queryCheck3 = "SELECT instructor_id FROM instructor WHERE instructor_id = $instructorId";
							$queryCheck3 = mysqli_query($conn, $queryCheck3);
							$countCheck3 = mysqli_num_rows($queryCheck3);
							if ($countCheck3 == 1){ //if instructor_id is taken
								$insValid = "FALSE";
								?>
								<div class="errors">
									<strong id="errorSeven">
										<strong style="color: red">&#9733</strong>
										Instructor id is already associated with another instructor.
									</strong>
								</div>
								<?php
							}
							if($insLength != 6){
								$insValid = "FALSE";
								?>
								<div class="errors">
									<strong id="errorSeven">
										<strong style="color: red">&#9733</strong>
										Instructor id must be 6 digits.
									</strong>
								</div>
								<?php
							}
						}
						else{
							$insValid = "FALSE";
							?>
							<div class="errors">
								<strong id="errorSeven">
									<strong style="color: red">&#9733</strong>
									Instructor id is required.
								</strong>
							</div>
							<?php
						}
					}
					?>


					<input style="background-color: black; opacity: 0.75;border-color: white; color: white" 
					type="password" id="rcorners2" placeholder="Password" name="instructor_password"><br>
					<?php
					if (isset($_POST['addInstructor'])){
						if (empty($_POST['instructor_password'])){
							$insValid = "FALSE";
							?>
							<div class="errors">
								<strong id="errorEight">
									<strong style="color: red">&#9733</strong>
									Instructor Password is required.
								</strong>
							</div>
							<?php
						}
					}
					?>

					<select style=" background-color: black; opacity: 0.75;border-color: white; color: white; 
									padding: 0px; margin: 0px; padding-left: 5%;" 
							id="rcorners2" name="instructor_gender" >
						<option selected="" value="1">Male</option>
						<option value="0">Female</option>
					</select><br>
					<div class="errors">
						<strong id="errorNine"></strong>
					</div>

					<input style="background-color: black; opacity: 0.75;border-color: white; color: white" type="text" id="rcorners2" placeholder="Phone Number" name="instructor_phone"><br>
					<?php
					if (isset($_POST['addInstructor'])){
						if (!empty($_POST['instructor_phone'])){
							$instructorPhone = trim(mysqli_real_escape_string($conn, $_POST['instructor_phone']));
							$queryCheck4 = "SELECT phone FROM instructor WHERE phone = $instructorPhone";
							$queryCheck4 = mysqli_query($conn, $queryCheck4);
							$countCheck4 = mysqli_num_rows($queryCheck4);
							if ($countCheck4 == 1){ //if phone number is taken
								$insValid = "FALSE";
								?>
								<div class="errors">
									<strong id="errorTen">
										<strong style="color: red">&#9733</strong>
										Phone number is already associated with another instructor.
									</strong>
								</div>
								<?php
							}
						}
						else{
							$insValid = "FALSE";
							?>
							<div class="errors">
								<strong id="errorTen">
									<strong style="color: red">&#9733</strong>
									Phone number is required.
								</strong>
							</div>
							<?php
						}
					}
					?>

					<button class="button" name="addInstructor"><span>Add</span></button>
				</form>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12"> <!-- Student -->
				<h5>STUDENT</h5>
				<hr>
				<form method="POST" action="dean.php" autocomplete="off">
					<input style="background-color: black; opacity: 0.75; border-color: white; color: white" type="text" id="rcorners2" placeholder="Name" name="student_name">
					
					<?php
					if(isset($_POST['addStudent'])){
						if(empty($_POST['student_name'])){
							$stdValid = "FALSE";
							?>
							<div class="errors">
								<strong id="error12">
									<strong style="color: red">&#9733</strong>
									Student name is required.
								</strong>
							</div>
							<?php
						}
					}
					?>


					<input style="background-color: black; opacity: 0.75;border-color: white; color: white" type="text" id="rcorners2" placeholder="ID" name="student_id">
					<?php
					if(isset($_POST['addStudent'])){
						if(!empty($_POST['student_id'])){
							$studentId = trim(mysqli_real_escape_string($conn, $_POST['student_id']));

							$queryCheck5 = "SELECT student_id FROM student WHERE student_id = $studentId";
							$queryCheck5 = mysqli_query($conn, $queryCheck5);
							$countCheck5 = mysqli_num_rows($queryCheck5);
							if ($countCheck5 == 1){
								$stdValid = "FALSE";
								?>
								<div class="errors">
									<strong id="error12">
										<strong style="color: red">&#9733</strong>
										Id is already associated with another Student.
									</strong>
								</div>
								<?php
							}
							if(strlen($studentId) != 7){
								$stdValid = "FALSE";
								?>
								<div class="errors">
									<strong id="error12">
										<strong style="color: red">&#9733</strong>
										Student Id must be 7 digits.
									</strong>
								</div>
								<?php
							}
						}
						else{
							$stdValid = "FALSE";
							?>
							<div class="errors">
								<strong id="error12">
									<strong style="color: red">&#9733</strong>
									Student Id is required.
								</strong>
							</div>
							<?php
						}
					}
					?>
					


					<input style="background-color: black; opacity: 0.75;border-color: white; color: white" 
					type="password" id="rcorners2" placeholder="Password" name="student_password">

					<?php
					if (isset($_POST['addStudent'])) {
						if (empty($_POST['student_password'])) {
							$stdValid = "FALSE";
							?>
							<div class="errors">
								<strong id="error13">
									<strong style="color: red">&#9733</strong>
									Student password is required.
								</strong>
							</div>
							<?php
						}
					}
					?>

					<select style=" background-color: black; opacity: 0.75;border-color: white; color: white; 
									padding: 0px; margin: 0px; padding-left: 5%;" 
							id="rcorners2" name="student_gender">
						<option selected="" value="1">Male</option>
						<option value="0">Female</option>
					</select><br>

					<select style=" background-color: black; opacity: 0.75;border-color: white; color: white; 
									padding: 0px; margin: 0px; padding-left: 5%;" 
							id="rcorners2" name="major">
						<option selected="" value="BIT">BIT</option>
						<option value="CIS">CIS</option>
						<option value="CS">CS</option>
						<option value="SWE">SWE</option>
					</select><br>

					<input style="background-color: black; opacity: 0.75;border-color: white; color: white" type="text" id="rcorners2" placeholder="Phone Number" name="student_phone">
					<?php
					if (isset($_POST['addStudent'])){
						if (!empty($_POST['student_phone'])){

							$studentPhone = trim(mysqli_real_escape_string($conn, $_POST['student_phone']));
							$queryCheck6  = "SELECT phone FROM student WHERE phone = $studentPhone";
							$queryCheck6  = mysqli_query($conn, $queryCheck6);
							$countCheck6  = mysqli_num_rows($queryCheck6);
							if ($countCheck6 == 1) {
								$stdValid = "FALSE";
								?>
								<div class="errors">
									<strong id="error16">
										<strong style="color: red">&#9733</strong>
										Phone number is already used.
									</strong>
								</div>
								<?php
							}
						}
						else{
							?>
							<div class="errors">
								<strong id="error16">
									<strong style="color: red">&#9733</strong>
									Phone number is required.
								</strong>
							</div>
							<?php
						}
					}
					?>

					<br>
					<button class="button" name="addStudent"><span>Add</span></button>
				</form>
			</div>
		</div> 
	</div>
	
	<div class="container features" style="z-index: 15;">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12"><!--  Absence Form -->
				<h5>Absence Table</h5>
				<hr>
				<div style="text-align: center;">
					<form action="dean.php" method="POST">
						<select id="sel_course" class="rcorners2" name="course">
				            <option value="0">- Course Name -</option>
				            <?php 
				            $sql_course= "SELECT DISTINCT course_name, course_id FROM course ORDER BY course_name";
				            $sql_data = mysqli_query($conn,$sql_course);
				            while($row = mysqli_fetch_assoc($sql_data) ){
				                $courseId = $row['course_id'];
				                $courseName = $row['course_name'];
				              
				                echo "<option value='".$courseId."' >".$courseName."</option>";
				            }
				            ?>
				        </select>
				        <select id="sel_section" class="rcorners2" name="section">
				            <option value="0">- Section Number -</option>
				        </select>

				        <button class="button" name="viewAbsence" 
				        		style="background-color: black; height: auto; width: 15%;">
				        	<span>View Absence</span>
				        </button>
				        <div class="errors" style="width: 70%; text-align: center;">
							<strong id="error17"></strong>
						</div>
					</form>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12"><!--Absence Table -->
				<hr>
				<div style="text-align: center;">
					<?php
						if (isset($_POST['viewAbsence'])) {
							make_login_detail($conn, $id);
							?>
							<table id="rcorners2" style="width: 100%; border: none; background-color: black; font-size: 80%; margin: 0px;" class="table table-striped  table-responsive">
						 		<thead>
						 			<tr style="color: white"> 
										<th scope="col" style="width: 15%; vertical-align: top;">Student Id</th>
										<th scope="col" style="width: 25%; vertical-align: top;">Student Name</th>
										<th scope="col" style="width: 20%; vertical-align: top;">Course Name</th>
										<th scope="col" style="width: 15%; vertical-align: top;">Course Id</th>
										<th scope="col" style="width: 15%; vertical-align: top;">Section Number</th>
										<th scope="col" style="width: 20%;vertical-align: top;">Number of Absence</th>
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
							$sql = "SELECT  absence.student_id, student.student_name, 
											course.course_name, absence.course_id, 
											absence.section_number, absence.date, count(*)
									FROM    `absence` , `student`, `course`
									WHERE 	absence.course_id = course.course_id
									AND 	absence.section_number = course.section_number
									AND 	absence.student_id = student.student_id"
									. $where .
									" GROUP BY absence.student_id, absence.course_id
									ORDER BY absence.date DESC";
							$sql = mysqli_query($conn, $sql);
							?>
							<tbody>
							 	<?php
					 			if($sql){
						 			while ($row = mysqli_fetch_array($sql)) {
						 				if ($row['count(*)'] >= 7) {
						 				 	?>
						 				 	<tr style="background-color: red">
						 				 	<?php
						 				 }
						 				 else{
						 				 	?>
						 				 	<tr>
						 				 	<?php
						 				 } ?>
											<td><?php echo $row['student_id']; ?></td>
											<td><?php echo $row['student_name']; ?></td>
											<td><?php echo $row['course_name']; ?></td>
											<td><?php echo $row['course_id']; ?></td>
											<td><?php echo $row['section_number']; ?></td>
											<td><?php echo $row['count(*)']; ?></td>
										</tr>
									<?php }
					 			}
							 	?>
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

<script src="jquery-3.3.1.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="main.js"></script>


								<!-- FORMS VALIDATION -->

<?php								// FROM NUMBER 1
	if (isset($_POST['addCourse'])){
		//To make sure that all the data has been filled in the form.
		if (!empty($_POST['course_name'])   && !empty($_POST['course_id']) 
			&& !empty($_POST['section_number']) && !empty($_POST['instructor_id']) 
			&& !empty($_POST['course_time'])) {

			$courseName 	= trim(mysqli_real_escape_string($conn, $_POST['course_name']));
			$courseId 		= trim(mysqli_real_escape_string($conn, $_POST['course_id']));
			$sectionNumber 	= trim(mysqli_real_escape_string($conn, $_POST['section_number']));
			$instructorId 	= trim(mysqli_real_escape_string($conn, $_POST['instructor_id']));
			$courseTime 	= trim(mysqli_real_escape_string($conn, $_POST['course_time']));

			//To make sure that There is no Course with the same section number.
			$queryCheck1 = "SELECT * FROM `course` WHERE course_id = $courseId AND section_number = $sectionNumber ";
			$queryCheck1 = mysqli_query($conn, $queryCheck1);
			$countCheck1 = mysqli_num_rows($queryCheck1);
			if ($countCheck1 != 0) {
				?>
				<script>
					document.getElementById("errorFour").innerHTML = "<strong style=\"color:red\">&#9733 </strong>There Already Exist a course with the same section number.";
				</script>
				<?php
			}

			//To make sure that the instructor exists in 'instructor' table.
			$queryCheck2 = "SELECT * FROM `instructor` WHERE instructor_id = $instructorId ";
			$queryCheck2 = mysqli_query($conn, $queryCheck2);
			$countCheck2 = mysqli_num_rows($queryCheck2);
			if ($countCheck2 == 1) {
				make_login_detail($conn, $id);
				$queryInsert = "INSERT INTO `course` VALUES ($courseId, \"$courseName \", $sectionNumber, 
								$instructorId, \"$courseTime\"  )";
				$query = mysqli_query($conn, $queryInsert);
				header('Location: dean.php');
			}
			else{
				?>
				<script>
					document.getElementById("errorFive").innerHTML = "<strong style=\"color:red\">&#9733 </strong>There is no Instructor asscociated with the given Id.";
				</script>
				<?php
			}
		}


		if (empty($_POST['course_name'])){
			?>
			<script>
				document.getElementById("errorOne").innerHTML = "<strong style=\"color:red\">&#9733 </strong>Course Name is required.";
			</script>
			<?php
		}
		if (empty($_POST['course_id'])){
			?>
			<script>
				document.getElementById("errorTwo").innerHTML = "<strong style=\"color:red\">&#9733 </strong>Course Id is required.";
			</script>
			<?php
		}
		if (empty($_POST['section_number'])){
			?>
			<script>
				document.getElementById("errorThree").innerHTML = "<strong style=\"color:red\">&#9733 </strong>Section Number is required.";
			</script>
			<?php
		}
		if (empty($_POST['instructor_id'])){
			?>
			<script>
				document.getElementById("errorFour").innerHTML = "<strong style=\"color:red\">&#9733 </strong>Instructor Id is required.";
			</script>
			<?php
		}
		if (empty($_POST['course_time'])){
			?>
			<script>
				document.getElementById("errorFive").innerHTML = "<strong style=\"color:red\">&#9733 </strong>Course Time is required.";
			</script>
			<?php
		}
	}

	if (isset($_POST['removeCourse'])){
		if (!empty($_POST['course_id']) && !empty($_POST['section_number'])) {
			$courseId 		= trim(mysqli_real_escape_string($conn, $_POST['course_id']));
			$sectionNumber 	= trim(mysqli_real_escape_string($conn, $_POST['section_number']));
			make_login_detail($conn, $id);
			$query = "DELETE FROM absence WHERE course_id = \"$courseId\" AND section_number=\"$sectionNumber\"";
			$query = mysqli_query($conn, $query);
			
			$query = "DELETE FROM registration WHERE course_id = \"$courseId\" AND section_number=\"$sectionNumber\"";
			$query = mysqli_query($conn, $query);

			$queryDelete = "DELETE FROM course WHERE course_id = $courseId AND section_number = $sectionNumber";
			$query = mysqli_query($conn, $queryDelete);
		}
		if (empty($_POST['course_id'])) {
			?>
			<script>
				document.getElementById("errorTwo").innerHTML = "<strong style=\"color:red\">&#9733 </strong>Course Id is required.";
			</script>
			<?php
		}
		if (empty($_POST['section_number'])) {
			?>
			<script>
				document.getElementById("errorThree").innerHTML = "<strong style=\"color:red\">&#9733 </strong>Section Number is required.";
			</script>
			<?php
		}
	}								// FORM NUMBER 2

	if (isset($_POST['addInstructor'])){
		//To make sure that all fields are not empty
		if($insValid = "TRUE"){
			$instructorName 	= trim(mysqli_real_escape_string($conn, $_POST['instructor_name']));
			$instructorId 		= trim(mysqli_real_escape_string($conn, $_POST['instructor_id']));
			$instructorPassword = trim(mysqli_real_escape_string($conn, $_POST['instructor_password']));
			$instructorPassword = md5($instructorPassword);
			$instructorGender 	= trim(mysqli_real_escape_string($conn, $_POST['instructor_gender']));
			$instructorPhone 	= trim(mysqli_real_escape_string($conn, $_POST['instructor_phone']));
			make_login_detail($conn, $id);
			$queryInsert2 = "INSERT INTO users VALUES ($instructorId, 2)";
			$queryInsert2 = mysqli_query($conn, $queryInsert2);
			$queryInsert2 = "INSERT INTO instructor(instructor_id, password, instructor_name, phone, gender)
							 VALUES ($instructorId ,\"$instructorPassword\", \"$instructorName\", $instructorPhone, $instructorGender)";
			$queryInsert2 = mysqli_query($conn, $queryInsert2);
		}
	}								// FORM NUMBER 3
	?>
	<img src="images/default_pic.jpeg" hidden="">
	<?php
	if (isset($_POST['addStudent'])){
		if ($stdValid == "TRUE"){
			$studentId 			= trim(mysqli_real_escape_string($conn, $_POST['student_id']));
			$studentName 		= trim(mysqli_real_escape_string($conn, $_POST['student_name']));
			$studentPassword 	= trim(mysqli_real_escape_string($conn, $_POST['student_password']));
			$studentPassword 	= md5($studentPassword);
			$studentGender 		= trim(mysqli_real_escape_string($conn, $_POST['student_gender']));
			$studentMajor 		= trim(mysqli_real_escape_string($conn, $_POST['major']));
			$studentPhone 		= trim(mysqli_real_escape_string($conn, $_POST['student_phone']));
			make_login_detail($conn, $id);
			$queryInsert3 = "INSERT INTO users VALUES ($studentId, 3)";
			$queryInsert3 = mysqli_query($conn, $queryInsert3);
			$queryInsert3 = "INSERT INTO student (student_id, password, student_name, gender, major, phone)
							 VALUES($studentId, \"$studentPassword\", \"$studentName\", $studentGender, 
							 		\"$studentMajor\", $studentPhone) ";
			$queryInsert3 = mysqli_query($conn, $queryInsert3);
		}
	}
?>
</body>
</html>