<!--
//index.php
!-->

<?php

include('connect.php');
include_once 'header.php'; 

	if(session_id() == '') {
	    session_start();
	}
	if (!isset($_SESSION['loggedIn'])) {
		echo '<script>window.alert("You\'re not yet signed in");window.location.href="/hu/signin.php";</script>';
	}
	$id = $_SESSION['id'];
	$type = $_SESSION['type'];
?>

<html lang="en">
  <head>

    <title>HU Chat System</title>

		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="icon" href="images/hu_logo.png">
    	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="main.css?version=51">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.css">
		<script src="jquery-1.12.0.min.js" type="text/javascript"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  		<script src="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.js"></script>
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
  		<link rel="stylesheet" type="text/css" href="">
  		<style type="text/css">
  			.label-success{
  				background-color: green;
  				text-align: center;
  				padding:5%;
  			}
			.features{
				background-color: rgb(0, 0, 0);
				background-color: rgba(0, 0, 0, 0.4);
				color: white;
				font-weight: bold;
				width: 80%;
			}
			.table {
				width: 99% !important; 
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
			.button {
				opacity: 0.75;
				display: inline-block;
				border-radius: 4px;
				background-color: black;
				border: none;
				color: #FFFFFF;
				text-align: center;
				font-size: 100%;
				width: 100%;
				height: 100%;
				transition: all 0.5s;
				cursor: pointer;
				margin: 5px;
			}
			#rcorners2{
				width: 98%;
				height: 5%;
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
    	<div class="page-bg"></div>

    	<div class="container features">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12"> <!-- Photo -->
					<div style="text-align: center;">
						<?php
						if ($type == 1) {
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
						}
						if ($type == 2) {
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
						}
						if ($type == 3) {
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
						}

						?>
					</div>
				</div>

				<div class="col-lg-4 col-md-4 col-sm-12" style="text-align:center"> <!-- Name, ID -->
					<div><?php echo $id; ?></div><br>
					<?php 
					if ($type == 1 || $type == 2) {
						echo '<div>Dr. '.  get_user_name($id, $conn) .'</div><br>';
					}
					else{
						echo '<div>' . get_user_name($id, $conn)  .'</div><br>';
					} 

					?>
					<form action="index.php" method="POST">	
						<button class="button" style="width: 40%; height: auto;background-color: #cc0000;" name="signOut">
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

        <div class="container">
			<div class="table-responsive">
				
				<div id="user_details"></div>
				<div id="user_model_details"></div>
			</div>
			<br />
			<br />
			
		</div>
		
    </body>  
</html>

<style>

.chat_message_area
{
	position: relative;
	width: 100%;
	height: auto;
	background-color: #FFF;
    border: 1px solid #CCC;
    border-radius: 3px;
}

#group_chat_message
{
	width: 100%;
	height: auto;
	min-height: 80px;
	overflow: auto;
	padding:6px 24px 6px 12px;
}

</style>  

<script>  
$(document).ready(function(){

	fetch_user();

	setInterval(function(){
		update_last_activity();
		fetch_user();
		update_chat_history_data();
		fetch_group_chat_history();
	}, 5000);

	function fetch_user()
	{
		$.ajax({
			url:"fetch_user.php",
			method:"POST",
			success:function(data){
				$('#user_details').html(data);
			}
		})
	}

	function update_last_activity()
	{
		$.ajax({
			url:"update_last_activity.php",
			success:function()
			{

			}
		})
	}

	function make_chat_dialog_box(to_user_id, to_user_name)
	{

		var modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="You have chat with '+to_user_id+'">';
		modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
		modal_content += fetch_user_chat_history(to_user_id);
		modal_content += '</div>';
		modal_content += '<div class="form-group">';
		modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control chat_message"></textarea>';
		modal_content += '</div><div class="form-group" align="right">';
		modal_content+= '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Send</button></div></div>';
		$('#user_model_details').html(modal_content);
	}

	$(document).on('click', '.start_chat', function(){
		var to_user_id = $(this).data('touserid');
		var to_user_name = $(this).data('tousername');
		make_chat_dialog_box(to_user_id, to_user_name);
		$("#user_dialog_"+to_user_id).dialog({
			autoOpen:false,
			width:400
		});
		$('#user_dialog_'+to_user_id).dialog('open');

	});

	$(document).on('click', '.send_chat', function(){
		var to_user_id = $(this).attr('id');
		var chat_message = $.trim($('#chat_message_'+to_user_id).val());
		if(chat_message != '')
		{
			$.ajax({
				url:"insert_chat.php",
				method:"POST",
				data:{to_user_id:to_user_id, chat_message:chat_message},
				success:function(data)
				{
					//$('#chat_message_'+to_user_id).val('');
					var element = $('#chat_message_'+to_user_id).emojioneArea();
					element[0].emojioneArea.setText('');
					$('#chat_history_'+to_user_id).html(data);
				}
			})
		}
		else
		{
			alert('Type something');
		}
	});

	function fetch_user_chat_history(to_user_id)
	{
		$.ajax({
			url:"fetch_user_chat_history.php",
			method:"POST",
			data:{to_user_id:to_user_id},
			success:function(data){
				$('#chat_history_'+to_user_id).html(data);
			}
		})
	}

	function update_chat_history_data()
	{
		$('.chat_history').each(function(){
			var to_user_id = $(this).data('touserid');
			fetch_user_chat_history(to_user_id);
		});
	}

	$(document).on('click', '.ui-button-icon', function(){
		$('.user_dialog').dialog('destroy').remove();
		$('#is_active_group_chat_window').val('no');
	});

	$(document).on('focus', '.chat_message', function(){
		var is_type = 'yes';
		$.ajax({
			url:"update_is_type_status.php",
			method:"POST",
			data:{is_type:is_type},
			success:function()
			{

			}
		})
	});

	$(document).on('blur', '.chat_message', function(){
		var is_type = 'no';
		$.ajax({
			url:"update_is_type_status.php",
			method:"POST",
			data:{is_type:is_type},
			success:function()
			{
				
			}
		})
	});

	$('#group_chat_dialog').dialog({
		autoOpen:false,
		width:400
	});

	$('#group_chat').click(function(){
		$('#group_chat_dialog').dialog('open');
		$('#is_active_group_chat_window').val('yes');
		fetch_group_chat_history();
	});

	$('#send_group_chat').click(function(){
		var chat_message = $.trim($('#group_chat_message').html());
		var action = 'insert_data';
		if(chat_message != '')
		{
			$.ajax({
				url:"group_chat.php",
				method:"POST",
				data:{chat_message:chat_message, action:action},
				success:function(data){
					$('#group_chat_message').html('');
					$('#group_chat_history').html(data);
				}
			})
		}
		else
		{
			alert('Type something');
		}
	});

	function fetch_group_chat_history()
	{
		var group_chat_dialog_active = $('#is_active_group_chat_window').val();
		var action = "fetch_data";
		if(group_chat_dialog_active == 'yes')
		{
			$.ajax({
				url:"group_chat.php",
				method:"POST",
				data:{action:action},
				success:function(data)
				{
					$('#group_chat_history').html(data);
				}
			})
		}
	}

	$(document).on('click', '.remove_chat', function(){
		var chat_message_id = $(this).attr('id');
		if(confirm("Are you sure you want to remove this chat?"))
		{
			$.ajax({
				url:"remove_chat.php",
				method:"POST",
				data:{chat_message_id:chat_message_id},
				success:function(data)
				{
					update_chat_history_data();
				}
			})
		}
	});
	
});  
</script>