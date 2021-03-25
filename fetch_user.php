<?php

//fetch_user.php
/*
	this return all the users from the 'users' table without the user already signed in.
*/

include('connect.php');
session_start();
	$id = $_SESSION['id'];
	$type = $_SESSION['type'];
if ($type == 1 || $type == 2) {
	$query = "
	SELECT * FROM users 
	WHERE user_id NOT IN (\"$id\")
	";
}
else{
	$query = "
	SELECT * FROM users 
	WHERE user_id NOT IN (\"$id\") AND type in (1, 2)
	";
}

$statement = mysqli_query($conn, $query);

$output = '
<table class="table table-bordered table-striped" id="rcorners2">
	<tr>
		<th width="10%">ID</th>
		<th width="50%">Username</th>
		<th width="10%">New Messages</th>
		<th width="20%">Status</th>
		<th width="10%">Action</th>
	</tr>
';

while($row = mysqli_fetch_array($statement))
{
	if($row['type'] == 1){
		$user_details = "SELECT * FROM dean WHERE dean_id =" .$row['user_id'];
	}
	if($row['type'] == 2){
		$user_details = "SELECT * FROM instructor WHERE instructor_id =" .$row['user_id'];
	}
	if($row['type'] == 3){
		$user_details = "SELECT * FROM student WHERE student_id =" .$row['user_id'];
	}
	$user_details = mysqli_query($conn, $user_details);
	$user_details = mysqli_fetch_array($user_details);

	$status = '';
	$current_timestamp = strtotime(date("Y-m-d H:i:s") . '-900 minutes');
	$current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
	$user_last_activity = fetch_user_last_activity($row['user_id'], $conn);
	if($user_last_activity > $current_timestamp)
	{
		$status = '<span class="badge badge-pill bagde-success" style="background-color:green">Online</span>';
	}
	else
	{
		$status = '<span class="badge badge-pill bagde-danger" style="background-color:red">Offline</span>';
	}
	
	if($row['type'] == 1){
		$output .= '
	<tr style="background-color: #adad85; font-weight: bold">
		<td>'.$row['user_id'] .'</td>
		<td>'.$user_details['dean_name'].'</td>
		<td>'.count_unseen_message($row['user_id'], $id, $conn).'</td>
		<td>'.$status.'</td>
		<td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['user_id'].'">Start Chat</button></td>
	</tr>
	';
	}
	if($row['type'] == 2){
		$output .= '
	<tr style="background-color: #ffb3b3; font-weight: bold">
		<td>'.$row['user_id'] .'</td>
		<td>'.$user_details['instructor_name'].'</td>
		<td>'.count_unseen_message($row['user_id'], $id, $conn).'</td>
		<td>'.$status.'</td>
		<td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['user_id'].'">Start Chat</button></td>
	</tr>
	';
	}
	if($row['type'] == 3){
		$output .= '
	<tr style="background-color: #d1e0e0; font-weight: bold;">
		<td>'.$row['user_id'] .'</td>
		<td>'.$user_details['student_name'].'</td>
		<td>'.count_unseen_message($row['user_id'], $id, $conn).'</td>
		<td>'.$status.'</td>
		<td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['user_id'].'">Start Chat</button></td>
	</tr>
	';
	}
}

$output .= '</table>';

echo $output;

?>