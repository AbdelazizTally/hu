<?php

//insert_chat.php

include('connect.php');

session_start();

$to_user_id = $_POST['to_user_id'];
$from_user_id =$_SESSION['id'];
$chat_message = $_POST['chat_message'];
$status = '1';

make_login_detail($conn, $from_user_id);
$query = "
INSERT INTO chat_message 
(to_user_id, from_user_id, message, status) 
VALUES (\"$to_user_id\", \"$from_user_id\", \"$chat_message\", \"$status\")
";

$statement = mysqli_query($conn, $query);
echo fetch_user_chat_history($_SESSION['id'], $_POST['to_user_id'], $conn);
?>