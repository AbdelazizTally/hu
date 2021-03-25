<!DOCTYPE html>
<html lang="en">
<head>
	<?php ob_start(); session_start(); ?>
    <title>Instructor Homepage</title>
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
                    url: 'getSections.php',
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
                $.ajax({
	                    url: 'getstudent.php',
	                    type: 'post',
	                    data: {course:course_Id, section:1},
	                    dataType: 'json',
	                    success:function(response){

	                        var len = response.length;
	                        $("#sel_student").empty();
	                        for( var i = 0; i<len; i++){
	                            var id  = response[i]['id'];
	                            var val = response[i]['value'];

	                            $("#sel_student").append("<option value='"+val+"'>"+id+"</option>");

	                        }
	                    }
	                });
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
	        $("#sel_section").change(function(){
	            	var course_Id = $("#sel_course").val();
	                var section = $(this).val();	                
	                
	                $.ajax({
	                    url: 'getstudent.php',
	                    type: 'post',
	                    data: {course:course_Id, section:section},
	                    dataType: 'json',
	                    success:function(response){

	                        var len = response.length;
	                        $("#sel_student").empty();
	                        for( var i = 0; i<len; i++){
	                            var id  = response[i]['id'];
	                            var val = response[i]['value'];

	                            $("#sel_student").append("<option value='"+val+"'>"+id+"</option>");

	                        }
	                    }
	                });
	            });
	        });
    </script>
	<style type="text/css">
		#sel_section, #sel_student, #sel_course{
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
		.courses{
			opacity: 0.75;
			padding: 0px; 
			margin: 0px; 
			padding-left: 5%;
		}
		.table {
			width: 100% !important; 
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
			background-color: #cc0000;
			border: none;
			color: #FFFFFF;
			text-align: center;
			font-size: 100%;
			width: 32.99999%;
			height: 10%;
			transition: all 0.5s;
			cursor: pointer;
			margin: 5px;
		}
		.view{
			opacity: 0.75;
			display: inline-block;
			border-radius: 4px;
			background-color: black;
			border: none;
			color: white;
			text-align: center;
			font-size: 100%;
			width: auto;
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
		.rcorners2{
			width: 30%;
			height: 5%;
			border-left-color: none;
			border-right-color: none;
			border-top-color: none;
			border-bottom-color: none;
			border-color: none;
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
		#rcorners1 {
			border-radius: 25px;
			background: #73AD21;
			padding: 20px; 
			width: 200px;
			height: 150px;  
		}
	</style>
</head>

<body>
	<?php
	if (!isset($_SESSION['loggedIn'])) {
		echo '<script>window.alert("You\'re not yet signed in");window.location.href="/hu/signin.php";</script>';
	}
	include_once 'header.php'; 
	include_once 'connect.php';
	$id = $_SESSION['id'];
	if (isset($_POST['upload'])) {
		$imageType = mysqli_real_escape_string($conn, $_FILES["image"]["type"]);
		if ((strpos($imageType, 'jpg') !== false) || (strpos($imageType, 'jpeg') !== false) || 
			(strpos($imageType, 'png') !== false)) {
			
		$imageName = mysqli_real_escape_string($conn, $_FILES["image"]["name"]);
		$imageData = mysqli_real_escape_string($conn, file_get_contents($_FILES["image"]["tmp_name"]));

			make_login_detail($conn, $id);
			$query = "UPDATE instructor SET photo = \"$imageData\" WHERE instructor_id = \"$id\"";
			$query = mysqli_query($conn, $query);
		}
		else
			echo '<script>window.alert("You cannot upload this type of file");window.location.href="/hu/instructor.php";</script>';
	}

	if (isset($_POST['delete'])) {
		$sql = "UPDATE instructor SET photo = NULL WHERE instructor_id = \"$id\"";
		$sql = mysqli_query($conn, $sql);
	}
	?>

	<div class="page-bg"></div>

	<div class="container features"> <!-- User Details -->
		<div class="row">
			
			<div class="col-lg-4 col-md-4 col-sm-12"> <!-- Photo -->
				<div style="text-align: center;">
					<?php
					$photo = "SELECT photo FROM instructor where instructor_id = \"$id\"";
					$photo = mysqli_query($conn, $photo);
					$photo = mysqli_fetch_array($photo);

					if($photo['photo'] == ""){
						?>
						<img src="images/default_pic.png" style="width: 50%; height: 70%;" style="border: 2px; border-color: white;">
						<?php
					}
					else{
						?>
						<img src="showInstructorImage.php?id=<?php echo $id ?>" style="width: 50%; height: 70%;" >
						<?php
					}
					?>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12"> <!-- Photo Editing -->
				<div style="text-align: center;">
					<form action="instructor.php" method="POST" enctype="multipart/form-data">
						<input  style=" background-color: black; opacity: 0.75;border-color: white; 
										color: white; width: 70%; padding: 3%;" 
							type="file" id="rcorners2" name="image"><br>
						<input  class="button" type="submit" id="rcorners2" name="upload" value="upload"
								style="background-color: black; opacity: 0.75;border-color: white; color: white; width: 70%; padding: 3%;" >
						<br>
						<input  class="button" type="submit" id="rcorners2" name="delete" 
						value="Delete Photo" style="background-color: black; opacity: 0.75; color: white;
								border-color: white; width: 70%; padding: 3%;">
						<br>
					</form>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12" style="text-align: center;"> <!-- User -->
				<div><?php echo $id; ?></div><br>
				<div>Dr. <?php echo get_user_name($id, $conn); ?></div><br>
				<form action="instructor.php" method="POST">	
					<button class="button"  style="width: 40%; height: auto;background-color: #cc0000;" 
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
			<div class="col-lg-1 col-md-1 col-sm-12">
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12">
				<?php
					$course_pass = get_course_password($conn, $id);
				?><br>
				<span>Lecture Password: <?php echo $course_pass; ?></span>
			</div>

			<div class="col-lg-5 col-md-5 col-sm-12">	
				<form action="instructor.php" method="POST" autocomplete="OFF">
					<input 	style="background-color:black; opacity:0.75 ;border-color:white; color:white; width:50%; margin-top: 13px;" 
							type="text" id="rcorners2" name="course_password">
					<button class="button" name="changePass" style="height: auto; width: 40%"><span>Change</span></button>
				</form>
				<?php
					if (isset($_POST['changePass'])) {
						if (!empty($_POST['course_password'])) {
							make_login_detail($conn, $id);
							$pass = $_POST['course_password'];
							update_course_password($conn, $id, $pass);
							header('Location:instructor.php');
						}
					}
				?>
			</div>
		</div>
	</div>

	<div class="container features" style="z-index: 15;">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12"> <!--Instructor's Registration Table -->
				<h5>Registration Table</h5>
				<hr>
				<div style="text-align: center;">
					<?php 
					$query = "  SELECT  course_id, course_name, section_number, 
										DATE_FORMAT(course.time, \"%H:%i\")time
								FROM course
								WHERE instructor_id = \"$id\"
								ORDER BY time ASC";
					$result = mysqli_query($conn, $query);

				 	?>
				 	<table id="rcorners2" style="border: none; background-color: black; font-size: 80%; margin: 0px;" class="table table-striped  table-responsive">
				 		<thead>
				 			<tr style="color: white"> 
								<th scope="col" style="width: 15%;">
									Course Id
								</th>
								<th scope="col" style="width: 15%;">
									Course Name
								</th>
								<th scope="col" style="width: 7%;">
									Section Number
								</th>
								<th scope="col" style="width: 5%;">
									Time
								</th>
								<th scope="col" style="width: 10%;">
									Take Attendance
								</th>
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
										<td><?php echo $row['time']; ?></td>
										<td><a href="takeAttendance.php?courseId=<?php echo $row['course_id'] ?>&section=<?php echo $row['section_number'] ?>&date=<?php echo $date ?>" style="color: #005eab;"> Take Attendance</a></td>
									</tr>
								<?php }
				 			}?>
				 		</tbody>
					</table>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-12"></div>
				
			</div>
		</div> 
	</div>

	<div class="container features" style="z-index: 15;">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12"><!--  Absence Form -->
				<h5>Absence Table</h5>
				<hr>
				<div style="text-align: center;">
					<form action="instructor.php" method="POST">
						<select id="sel_course" class="rcorners2" name="course">
				            <option value="0">- Course Name -</option>
				            <?php 
				            if(isset($_POST['course']))
				            	echo $_POST['course']. "HERE";
				            $sql_course= "SELECT DISTINCT course_name, course_id FROM course WHERE instructor_id=\"$id\"";
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

				        <select id="sel_student" class="rcorners2" name="student">
				            <option value="0">- Student Id -</option>
				        </select>

				        <button class="button" name="viewAbsence" 
				        		style="background-color: black; height: 45px; width: 15%;">
				        	<span>View Absence</span>
				        </button>
				        <div class="errors" style="width: 70%; text-align: center;">
							<strong id="errorOne"></strong>
						</div>
					</form>
				</div>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12"> <!--Absence Table -->
				<hr>
				<div style="text-align: center;">
					<?php
						if (isset($_POST['viewAbsence'])) {
							make_login_detail($conn, $id);
							?>
							<table id="rcorners2" style="border: none; background-color: black; font-size: 80%; margin: 0px;" class="table table-striped">
							 		<thead>
							 			<tr style="color: white"> 
											<th scope="col" style="width: 10%;">Student Id</th>
											<th scope="col" style="width: 20%;">Student Name</th>
											<th scope="col" style="width: 10%;">Course Name</th>
											<th scope="col" style="width: 10%;">Course Id</th>
											<th scope="col" style="width: 10%;">Section Number</th>
											<th scope="col" style="width: 10%;">Date</th>
											<th scope="col" style="width: 10%;">Delete Absence</th>
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
							if(!empty($_POST['student'])){
								$student = $_POST['student'];
								$where .= " AND absence.student_id = \"$student\"";
							}
							if( empty($_POST['course']) && empty($_POST['section']) && 
								empty($_POST['student']))
								$where = " AND course.instructor_id = \"$id\"";
							$sql = "SELECT  absence.student_id, student.student_name, 
											course.course_name, absence.course_id, 
											absence.section_number, absence.date
									FROM    `absence` , `student`, `course`
									WHERE 	absence.course_id = course.course_id
									AND 	absence.section_number = course.section_number
									AND 	absence.student_id = student.student_id" 
									. $where . "ORDER BY absence.date DESC";
							
							$sql = mysqli_query($conn, $sql);
							$row_count = mysqli_num_rows($sql);
							?>
							<tbody>
					 			<?php
					 			if($sql){
						 			while ($row = mysqli_fetch_array($sql)) { ?>
										<tr style="color: white;">
											<td><?php echo $row['student_id']; ?></td>
											<td><?php echo $row['student_name']; ?></td>
											<td><?php echo $row['course_name']; ?></td>
											<td><?php echo $row['course_id']; ?></td>
											<td><?php echo $row['section_number']; ?></td>
											<td><?php echo $row['date']; ?></td>
											<td><a href="deleteAbsence.php?courseId=<?php echo $row['course_id'] ?>&section=<?php echo $row['section_number'] ?>&date=<?php echo $row['date'] ?>&student=<?php echo $row['student_id'] ?>" style="color: red;"> X</a></td>
										</tr>
									<?php }
					 			}
					 			if(!empty($_POST['student'])){
						 			?>
						 			<tr>
						 				<td colspan="7" class="text-danger">
						 					Number of Absences: <?php echo $row_count; ?>
						 				</td>
						 			</tr>
					 				<?php
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



</body>
</html>