<!DOCTYPE html>
<html lang="en">
  <head>

    <title>Hashemite University</title>
    <link rel="icon" href="images/hu_logo.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="main.css?version=51">
	<style type="text/css">
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
		.button {
			opacity: 0.75;
			display: inline-block;
			border-radius: 4px;
			background-color: black;
			border: none;
			color: #FFFFFF;
			text-align: center;
			font-size: 100%;
			padding: 20px;
			width: 30%;
			height: 10%;
			transition: all 0.5s;
			cursor: pointer;
			margin: 5px;
		}
		form{
			padding-top: 3%;
			padding-bottom: 3%;
			text-align: center;
			width:  100%;
			height: 100%;
			margin: 0 auto;
		}
		.features{
			margin: auto;
			background-color: rgb(0,0,0);
			background-color: rgba(0,0,0, 0.4);
			color: white;
			font-weight: bold;
		}
		#rcorners2{
			width: 30%;
			height: 5%;
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
	include_once 'header.php'; 
	include_once 'connect.php';
	$valid = "TRUE";
	?>

	<div class="page-bg"></div>
	
	<div class="container features ">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<form action="signin.php" method="POST" id="form" autocomplete="off">
					<input style="background-color: black; opacity: 0.75;border-color: white; color:white; width:40%;" 	  type="text" id="rcorners2" placeholder="ID" name="id"
						value="<?php echo isset($_POST['id']) ? $_POST['id']= $_POST['id'] : '' ?>">
					</input>
					<div class="errors">
						<strong id="errorId"></strong>
					</div>

					<input style="background-color: black; opacity: 0.75;border-color: white; color:white; width:40%;" type="password" id="rcorners2" placeholder="Password" name="password"></input> 

					<div class="errors">
						<strong id="errorPass"></strong>
					</div>

					<div class="errors">
						<strong id="errorSignIn"></strong>
					</div>
					<hr>
					<button class="button" style="width: auto;" name="signIn"><span>Sign In</span></button>
				</form>
			</div>
	</div>

	<?php include_once 'footer.php';?>


	<?php
	if (isset($_POST['signIn'])){
		$id 		= trim(mysqli_real_escape_string($conn, $_POST['id']));
		$id 		= ltrim($id, "0");
		$idLength 	= strlen($id);
		$password 	= trim(mysqli_real_escape_string($conn, $_POST['password']));
		$password 	= md5($password);
		$idError 	= "";
		$passError	= "";


		switch ($idLength) {
			case 5:
				$user = "dean";
				break;
			case 6:
				$user = "insrtuctor";
				break;
			case 7:
				$user = "student";
				break;
			default:
				$user = "invalid user";
				break;
		}

		if (!empty($_POST['id']) && !empty($_POST['password'])){//not empty for both
			
			if ($user == "dean") { 			// Dean
				$deanQuery = "SELECT dean_id, password FROM dean WHERE dean_id = $id AND password = \"$password\"";
				$deanQuery = mysqli_query($conn, $deanQuery);
				$deanCount = mysqli_num_rows($deanQuery);
				if ($deanCount == 1){
					session_start();
					make_login_detail($conn, $id);
					$_SESSION['loggedIn'] = "TRUE";
					$_SESSION['user'] = $user;
					$_SESSION['type'] = 1;
					$_SESSION['id'] = $id;
					$_SESSION['login_details_id'] = last_insert_id($conn, $id);
					header('Location:dean.php');
				}
				else{ //User or pass is incorrect
					$deanQuery = "SELECT dean_id FROM dean WHERE dean_id = $id";
					$deanQuery = mysqli_query($conn, $deanQuery);
					$deanCount = mysqli_num_rows($deanQuery);
					if ($deanCount == 1) { // there is an id but pass is incorrect
						?>
						<script type="text/javascript">
							document.getElementById("errorPass").innerHTML = "<strong style=\"color:red\">&#9733 </strong> Your password is incorrect";
						</script>
						<?php
					}
					else{ // There is no Id 
						?>
						<script type="text/javascript">
							document.getElementById("errorId").innerHTML = "<strong style=\"color:red\">&#9733 </strong> Dean Id is incorrect";
						</script>
						<?php
					}
				}
			}
			else if ($user == "insrtuctor"){ 		// Instructor
				$instructorQuery = "SELECT instructor_id, password FROM instructor 
									WHERE instructor_id = $id AND password = \"$password\"";
				$instructorQuery = mysqli_query($conn, $instructorQuery);
				$instructorCount = mysqli_num_rows($instructorQuery);
				if ($instructorCount == 1){
					session_start();
					make_login_detail($conn, $id);
					$_SESSION['loggedIn'] = 'TRUE';
					$_SESSION['user'] = $user;
					$_SESSION['type'] = 2;
					$_SESSION['id'] = $id;
					$_SESSION['login_details_id'] = last_insert_id($conn, $id);
					header('Location:instructor.php');
				}
				else{ //User or pass is incorrect
					$instructorQuery = "SELECT instructor_id FROM instructor WHERE instructor_id = $id";
					$instructorQuery = mysqli_query($conn, $instructorQuery);
					$instructorCount = mysqli_num_rows($instructorQuery);
					if ($instructorCount == 1) { // there is an id but pass is incorrect
						?>
						<script type="text/javascript">
							document.getElementById("errorPass").innerHTML = "<strong style=\"color:red\">&#9733 </strong> Your password is incorrect";
						</script>
						<?php
					}
					else{ // There is no Id 
						?>
						<script type="text/javascript">
							document.getElementById("errorId").innerHTML = "<strong style=\"color:red\">&#9733 </strong> Instructor Id is incorrect";
						</script>
						<?php
					}
				}
			}

			else if ($user == "student"){ 		// Student
				$studentQuery = "SELECT student_id, password FROM student WHERE student_id = $id 
																			AND password = \"$password\"";
				$studentQuery = mysqli_query($conn, $studentQuery);
				// $studentCount = mysqli_num_rows($studentQuery);

				if (mysqli_num_rows($studentQuery) == 1){
					session_start();
					make_login_detail($conn, $id);
					$_SESSION['loggedIn'] = 'TRUE';
					$_SESSION['user'] = $user;
					$_SESSION['type'] = 3;
					$_SESSION['id'] = $id;
					$_SESSION['login_details_id'] = last_insert_id($conn, $id);
					header('Location:student.php');
				}
				else{
					$studentQuery  = "SELECT student_id FROM student WHERE student_id = $id";
					$studentQuery  = mysqli_query($conn, $studentQuery);
					// $studentCount = mysqli_num_rows($studentQuery);
					if (mysqli_num_rows($studentQuery) == 1) { // User or pass is incorrect
						?>
						<script type="text/javascript">
							document.getElementById("errorPass").innerHTML = "<strong style=\"color:red\">&#9733 </strong> Your password is incorrect";
						</script>
						<?php
					}
					else{ // There is no Id 
						?>
						<script type="text/javascript">
							document.getElementById("errorId").innerHTML = "<strong style=\"color:red\">&#9733 </strong> Student Id is incorrect";
						</script>
						<?php
					}
				}
			}
		}

		elseif(empty($_POST['id']) && empty($_POST['password'])){
			?>
			<script type="text/javascript">
				document.getElementById("errorSignIn").innerHTML = "<strong style=\"color:red\">&#9733 </strong> Id  and Password are required";
			</script>
			<?php
		}

		elseif(empty($_POST['id'])){
			?>
			<script type="text/javascript">
				document.getElementById("errorSignIn").innerHTML = "<strong style=\"color:red\">&#9733 </strong>Id is required";
			</script>
			<?php
		}

		else{ //password is empty
			if($user == "dean"){
				?>
				<script type="text/javascript">
					document.getElementById("errorPass").innerHTML = "<strong style=\"color:red\">&#9733 </strong> Dean password is required";
				</script>
				<?php
			}
			else if($user == "insrtuctor"){
				?>
				<script type="text/javascript">
					document.getElementById("errorPass").innerHTML = "<strong style=\"color:red\">&#9733 </strong> Instructor password is required";
				</script>
				<?php
			}
			else if($user == "student"){
				?>
				<script type="text/javascript">
					document.getElementById("errorPass").innerHTML = "<strong style=\"color:red\">&#9733 </strong> Student password is required";
				</script>
				<?php
			}
			else{
				?>
				<script type="text/javascript">
					document.getElementById("errorPass").innerHTML = "<strong style=\"color:red\">&#9733 </strong> Id is invalid";
				</script>
				<?php
			}
		}
	}
	?>

<script src="jquery-3.3.1.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="main.js"></script>
</body>
</html>