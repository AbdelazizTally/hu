<?php
include "connect.php";
ob_start();
session_start();
$id = $_SESSION['id'];
$courseId = $_POST['course'];

$sql = "SELECT section_number, course_id FROM course WHERE course_id=\"$courseId\"";

$result = mysqli_query($conn,$sql);

$users_arr = array();

while( $row = mysqli_fetch_array($result) ){
    $userid = $row['course_id'];
    $name = $row['section_number'];

    $users_arr[] = array("id" => $userid, "name" => $name);
}

// encoding array to json format
echo json_encode($users_arr);