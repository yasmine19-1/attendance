<?php
require_once __DIR__ . "/../db.php";
$conn = getConnection();

if (!isset($_GET['id'])) {
    die("Session ID missing.");
}

$session_id = (int) $_GET['id'];

$sql = "UPDATE attendance_sessions SET status = 'closed' WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt->execute([$session_id])) {
    header("Location: list_sessions.php");
    exit;
} else {
    die("Failed to close session.");
}
