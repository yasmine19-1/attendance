<?php
require_once __DIR__ . '/../../backend/db.php';
$conn = getConnection();

$session_id = intval($_POST['session_id']);
$student_id = intval($_POST['student_id']);
$status = $_POST['status'] === "present" ? "present" : "absent";

// Check if row exists
$check = $conn->prepare("SELECT id FROM attendance WHERE session_id=? AND student_id=? LIMIT 1");
$check->execute([$session_id, $student_id]);
$row = $check->fetch();

if ($row) {
    $upd = $conn->prepare("UPDATE attendance SET status=? WHERE id=?");
    $upd->execute([$status, $row['id']]);
} else {
    $ins = $conn->prepare("INSERT INTO attendance (session_id, student_id, status) VALUES (?,?,?)");
    $ins->execute([$session_id, $student_id, $status]);
}

echo "success";
?>
