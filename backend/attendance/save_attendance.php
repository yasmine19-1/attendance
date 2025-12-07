<?php
require_once('../db.php');

$conn = getConnection();

$data = json_decode(file_get_contents("php://input"), true);

$session_id = $data['session_id'];
$student_id = $data['student_id'];
$status     = $data['status'];

// Insert or update attendance
$query = $conn->prepare("
    INSERT INTO attendance (session_id, student_id, status)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE status = VALUES(status)
");

$success = $query->execute([$session_id, $student_id, $status]);

echo json_encode(["success" => $success]);

