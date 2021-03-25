<?php
	ob_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>

    <link rel="icon" href="images/hu_logo.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="main.css?version=51">
	<style type="text/css">
		#favlogo{
			margin-left: -4%;
			height: 80%;
			width: 80%;
		}
	</style>
  </head>
<body>
	<?php
	if (isset($_SESSION['loggedIn'])) {
		$id = $_SESSION['id'];
		$type = $_SESSION['type'];
	}

	?>
	<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>
	<script type="text/javascript">
		mybutton = document.getElementById("myBtn");
		window.onscroll = function() {scrollFunction()};

		function scrollFunction() {
			if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
				mybutton.style.display = "block";
			} 
			else {
				mybutton.style.display = "none";
			}
		}
		function topFunction() {
			$("html, body").animate({scrollTop: 0}, 1000);
		}
	</script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
		    $(window).scroll(function(){
		        if($(this).scrollTop() > 100){
		            $('#scroll').fadeIn();
		        }else{
		            $('#scroll').fadeOut();
		        }
		    });
		    $('#scroll').click(function(){
		        $("html, body").animate({ scrollTop: 0 }, 600);
		        return false;
		    });
		});
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<nav class="navbar navbar-expand-md sticky-top">
		<a class="navbar-brand" href="#"> <img src="images/2.png" id="favlogo" class="img-fluid"></a>
		<button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="main-navigation">
			<ul class="navbar-nav">
				<li class="nav-item">
					<?php
					if (isset($_SESSION['loggedIn'])) {
						if($type == 1)
							echo '<a class="nav-link" href="dean.php">Home</a>';
						if($type == 2)
							echo '<a class="nav-link" href="instructor.php">Home</a>';
						if($type == 3)
							echo '<a class="nav-link" href="student.php">Home</a>';
					}
					else
						echo '<a class="nav-link" href="signin.php">Home</a>';
					?>
			</li>
				<li class="nav-item">
					<a class="nav-link" href="Grad_Doc.pdf" target="_blank">About</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="https://hu.edu.jo/main/summon.aspx">Contact Us</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php">Chat</a>
				</li>
			</ul>
		</div>
	</nav>

</body>
</html>