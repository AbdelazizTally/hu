<?php
ob_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "university";


$conn = mysqli_connect($servername, $username, $password, $database);

function get_course_time($conn, $course_id, $section){
	$time = "	SELECT DATE_FORMAT(`time`, \"%H:%i\") 
				FROM course 
				WHERE course_id = \"$course_id\" AND section_number = \"$section\"";
	$time = mysqli_query($conn, $time);
	$time = mysqli_fetch_array($time);
	$time = $time[0];

	return $time;
}

function make_login_detail($conn, $id){

	$login_details = "INSERT INTO login_details(user_id) VALUES (\"$id\")";
	$login_details = mysqli_query($conn, $login_details);

	$_SESSION['login_details_id'] = last_insert_id($conn, $id);
}

function take_attendance($conn, $courseId, $section){
	$select   = "SELECT student_id, course_id, section_number
				 FROM registration
				 WHERE course_id = $courseId AND section_number = $section";

	$result = mysqli_query($conn,$select);
	while($r = mysqli_fetch_array($result))
	{
		$stdId = $r['student_id'];
		$cId   = $r['course_id'];
		$sec   = $r['section_number'];
		$sql  = "INSERT INTO absence(student_id, course_id, section_number) 
				 VALUES ( $stdId, $cId, $sec)";
		$sql  = mysqli_query($conn,$sql);
	}

}

function get_student_count($conn, $courseId, $section){
	$count ="SELECT COUNT(*)'count' FROM registration WHERE course_id=$courseId AND section_number=$section";
	$count = mysqli_query($conn, $count);
	$count = mysqli_fetch_array($count);
	$count = $count['count'];
	return $count;
}

function get_course_password($conn, $instructor){
	$sql = "SELECT course_password 
			FROM instructor 
			WHERE instructor_id = \"$instructor\"";
	$sql = mysqli_query($conn, $sql);
	$sql = mysqli_fetch_array($sql);
	$sql = $sql['course_password'];

	return $sql;
}

function update_course_password($conn, $id, $pass){
	$sql = "UPDATE instructor SET course_password = \"$pass\" WHERE instructor_id=\"$id\"";
	$sql = mysqli_query($conn, $sql);
	$sql = mysqli_fetch_array($sql);
	$sql = $sql['course_password'];

	return $sql;
}

function get_instructor($conn, $courseId, $section){
	$instructor = " SELECT instructor_id 
					FROM course 
					WHERE course_id = \"$courseId\" 
					AND   section_number = \"$section\"";
	$instructor = mysqli_query($conn, $instructor);
	$instructor = mysqli_fetch_array($instructor);
	$instructor = $instructor['instructor_id'];

	return $instructor;
}

function get_student_mac($conn, $id){
	$STUDENT_MAC = "SELECT mac_address FROM student WHERE student_id = \"$id\"";
	$STUDENT_MAC = mysqli_query($conn, $STUDENT_MAC);
	$STUDENT_MAC = mysqli_fetch_array($STUDENT_MAC);
	$STUDENT_MAC = $STUDENT_MAC['mac_address'];
	return $STUDENT_MAC;
}

function get_device_mac($conn){
	$DEVICE_MAC = exec('getmac'); 
	$DEVICE_MAC = strtok($DEVICE_MAC, ' ');
	return $DEVICE_MAC;
}

function get_start_time($conn, $courseId, $section){
	$startTime = "  SELECT DATE_FORMAT(time, \"%H:%i\")time 
					FROM course
					WHERE course_id = $courseId 
					AND   section_number = $section";
	$startTime = mysqli_query($conn, $startTime);
	$startTime = mysqli_fetch_array($startTime);
	$startTime = strtotime($startTime['time']);
	return $startTime;
}

function last_insert_id($conn, $id){
	$sql = "SELECT login_details_id
			FROM login_details
			WHERE user_id = \"$id\"
			order by last_activity DESC
			limit 1";
	$sql = mysqli_query($conn, $sql);
	$sql = mysqli_fetch_array($sql);
	$sql = $sql['login_details_id'];
	return $sql;
}

function fetch_user_last_activity($user_id, $conn){
	$query = "
	SELECT * FROM login_details 
	WHERE user_id = '$user_id'
	ORDER BY last_activity DESC 
	LIMIT 1	";
	$query = mysqli_query($conn, $query);
	$result = mysqli_fetch_array($query);
	while($row = $result){
		return $row['last_activity'];
	}
}

function fetch_user_chat_history($from_user_id, $to_user_id, $conn){
	$query = "
	SELECT * FROM chat_message 
	WHERE (from_user_id = '".$from_user_id."' AND to_user_id = '".$to_user_id."') 
	OR (from_user_id = '".$to_user_id."' AND to_user_id = '".$from_user_id."') 
	ORDER BY time DESC";
	$statement = mysqli_query($conn, $query);
	$output = '<ul class="list-unstyled">';
	
	while($row = mysqli_fetch_array($statement)){

		$user_name = '';
		$dynamic_background = '';
		$chat_message = '';
		if($row["from_user_id"] == $from_user_id){

			if($row["status"] == '2'){
				$chat_message = '<em>This message has been removed</em>';
				$user_name = '<b class="text-success">You</b>';
			}
			else{
				$chat_message = $row['message'];
				$user_name = '<button type="button" class="btn btn-danger btn-xs remove_chat" id="'.$row['from_user_id'].'">x</button>&nbsp;<b class="text-success">You</b>';
			}
			

			$dynamic_background = 'background-color:#ffe6e6;';
		}
		else
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
			}
			else
			{
				$chat_message = $row["message"];
			}
			$user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $conn).'</b>';
			$dynamic_background = 'background-color:#ffffe6;';
		}
		$output .= '
		<li style="border-bottom:1px dotted #ccc;padding-top:8px; padding-left:8px; padding-right:8px;'.$dynamic_background.'">
			<p>'.$user_name.' - '.$chat_message.'
				<div align="right">
					- <small><em>'.$row['time'].'</em></small>
				</div>
			</p>
		</li>
		';
	}
	$output .= '</ul>';
	$query = "
	UPDATE chat_message 
	SET status = '0' 
	WHERE from_user_id = '".$to_user_id."' 
	AND to_user_id = '".$from_user_id."' 
	AND status = '1'
	";
	$statement = mysqli_query($conn, $query);
	return $output;
}

function get_user_name($user_id, $conn){
	$length = strlen($user_id);
	if($length == 5)
		$query = "SELECT dean_id, dean_name FROM dean WHERE dean_id = '$user_id'";
	if($length == 6)
		$query = "SELECT instructor_id, instructor_name FROM instructor WHERE instructor_id = '$user_id'";
	if($length == 7)
		$query = "SELECT student_id, student_name FROM student WHERE student_id = '$user_id'";
	$statement = mysqli_query($conn, $query);
	$result = mysqli_fetch_array($statement);
	foreach($result as $row)
	{
		return $result[1];
	}
}

function count_unseen_message($from_user_id, $to_user_id, $conn){
	$query = "
	SELECT * FROM chat_message 
	WHERE from_user_id = '$from_user_id' 
	AND to_user_id = '$to_user_id' 
	AND status = '1'
	";
	$statement = mysqli_query($conn, $query);
	$count = mysqli_num_rows($statement);
	$output = '';
	if($count > 0)
	{
		$output = '<span class="badge badge-pill badge-success">'.$count.'</span>';
	}
	return $output;
}

function fetch_is_type_status($user_id, $conn){
	$query = "
	SELECT is_type FROM login_details 
	WHERE user_id = '".$user_id."' 
	ORDER BY last_activity DESC 
	LIMIT 1
	";
	$statement = mysqli_query($conn, $query);
	$result = mysqli_fetch_array($statement);
	$output = '';
	while($row = $result){
		if($row["is_type"] == 'yes')
		{
			$output = ' - <small><em><span class="text-muted">Typing...</span></em></small>';
		}
	}
	return $output;
}

function fetch_group_chat_history($connect){
	$query = "
	SELECT * FROM chat_message 
	WHERE to_user_id = '0'  
	ORDER BY time DESC
	";

	$statement = mysqli_query($conn, $query);
	$result = mysqli_fetch_array($statement);

	$output = '<ul class="list-unstyled">';
	foreach($result as $row)
	{
		$user_name = '';
		$dynamic_background = '';
		$chat_message = '';
		if($row["from_user_id"] == $_SESSION["user_id"])
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
				$user_name = '<b class="text-success">You</b>';
			}
			else
			{
				$chat_message = $row["chat_message"];
				$user_name = '<button type="button" class="btn btn-danger btn-xs remove_chat" id="'.$row['chat_message_id'].'">x</button>&nbsp;<b class="text-success">You</b>';
			}
			
			$dynamic_background = 'background-color:#ffe6e6;';
		}
		else
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
			}
			else
			{
				$chat_message = $row["chat_message"];
			}
			$user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $conn).'</b>';
			$dynamic_background = 'background-color:#ffffe6;';
		}

		$output .= '

		<li style="border-bottom:1px dotted #ccc;padding-top:8px; padding-left:8px; padding-right:8px;'.$dynamic_background.'">
			<p>'.$user_name.' - '.$chat_message.' 
				<div align="right">
					- <small><em>'.$row['timestamp'].'</em></small>
				</div>
			</p>
		</li>
		';
	}
	$output .= '</ul>';
	return $output;
}
?>