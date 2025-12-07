<?php
// backend/login.php
session_start();
require_once __DIR__ . "/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    exit("Method not allowed");
}

$conn = getConnection();

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Basic validation
if ($email === '' || $password === '') {
    // Just reload login page with NO ?error
    header("Location: /AttendanceSystem/frontend/login.html");
    exit;
}

$stmt = $conn->prepare("SELECT id, name AS fullname, email, password, role 
                        FROM users WHERE email = :email LIMIT 1");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: /AttendanceSystem/frontend/login.html");
    exit;
}

if ($password === $user['password'] && $user['role'] === 'professor') {
    $_SESSION['professor_id'] = $user['id'];
    $_SESSION['professor_name'] = $user['fullname'];

    header("Location: /AttendanceSystem/frontend/professor/index.php");
    exit;
} else {
    header("Location: /AttendanceSystem/frontend/login.html");
    exit;
}
