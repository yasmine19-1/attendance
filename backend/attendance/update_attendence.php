<?php
require_once("db.php");
$conn = getConnection();

$session_id = $_POST['session_id'] ?? 0;
$student_id = $_POST['student_id'] ?? 0;
$status     = $_POST['status'] ?? '';

if (!$session_id || !$student_id || !$status) {
    echo json_encode(["success" => false, "message" => "Missing data"]);
    exit;
}

// Check if attendance exists
$stmt = $conn->prepare("
    SELECT id FROM attendance
    WHERE session_id = ? AND student_id = ?
");
$stmt->execute([$session_id, $student_id]);
$existing = $stmt->fetch();

if ($existing) {
    // Update it
    $stmt = $conn->prepare("
        UPDATE attendance
        SET status = ?
        WHERE session_id = ? AND student_id = ?
    ");
    $stmt->execute([$status, $session_id, $student_id]);
} else {
    // Insert new row
    $stmt = $conn->prepare("
        INSERT INTO attendance (session_id, student_id, status)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$session_id, $student_id, $status]);
}

echo json_encode(["success" => true]);
exit;
?>
