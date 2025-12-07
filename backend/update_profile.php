<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login.html");
    exit;
}

$conn = getConnection();
$profId = $_SESSION['professor_id'];

$stmt = $conn->prepare("
    UPDATE users SET
        name = :name,
        email = :email,
        phone = :phone,
        employee_id = :employee_id,
        department = :department,
        office_location = :office_location,
        specialization = :specialization
    WHERE id = :id
");

$stmt->execute([
    ':name' => $_POST['name'],
    ':email' => $_POST['email'],
    ':phone' => $_POST['phone'],
    ':employee_id' => $_POST['employee_id'],
    ':department' => $_POST['department'],
    ':office_location' => $_POST['office_location'],
    ':specialization' => $_POST['specialization'],
    ':id' => $profId
]);

// Update session name
$_SESSION['professor_name'] = $_POST['name'];

header("Location: /AttendanceSystem/frontend/professor/profile.php");
exit;
