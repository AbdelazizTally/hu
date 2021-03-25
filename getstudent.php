<?php
include "connect.php";
ob_start();
session_start();
$id = $_SESSION['id'];
$courseId = $_POST['course'];
$section  = $_POST['section'];

$sql = "SELECT student_id FROM registration WHERE course_id=\"$courseId\" AND section_number=\"$section\"";

$result = mysqli_query($conn,$sql);

$users_arr = array();
$users_arr[] = array("value" => "", "id" => "All Students");

while( $row = mysqli_fetch_array($result) ){
    $student_id = $row['student_id'];

    $users_arr[] = array("value" => $student_id, "id" => $student_id);
}

// encoding array to json format
echo json_encode($users_arr);