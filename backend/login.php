<?php
// backend/login.php
session_start();
require_once __DIR__ . "/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Only accept POST from the form
    header("HTTP/1.1 405 Method Not Allowed");
    exit("Method not allowed");
}

$conn = getConnection();

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Basic validation
if ($email === '' || $password === '') {
    header("Location: /AttendanceSystem/frontend/login.html?error=1");
    exit;
}

// Query: note your DB column is `name` (we alias to fullname below)
$stmt = $conn->prepare("SELECT id, name AS fullname, email, password, role FROM users WHERE email = :email LIMIT 1");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: /AttendanceSystem/frontend/login.html?error=1");
    exit;
}

// For now we compare plain text (you inserted 123456). Later use password_hash.
if ($password === $user['password'] && $user['role'] === 'professor') {
    // Auth success
    $_SESSION['professor_id'] = $user['id'];
    $_SESSION['professor_name'] = $user['fullname'];

    // Redirect to professor dashboard page (use .php, not static html, so we can use sessions)
    header("Location: /AttendanceSystem/frontend/professor/index.php");
    exit;
} else {
    header("Location: /AttendanceSystem/frontend/login.html?error=1");
    exit;
}
