<?php
require_once __DIR__ . "/../db.php"; 
// if file is inside backend/admin or backend/attendance
$conn = getConnection();

$id = $_GET["id"];
// stmt=statment
$stmt = $conn->prepare("DELETE FROM students WHERE id=?");
$stmt->execute([$id]);

// echo "✔ Student deleted!"; this couse a problem with header redirection
header("Location: list_students.php");
exit;
?>
