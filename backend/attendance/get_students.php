<?php
// backend/attendance/get_students.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../db.php";
$conn = getConnection();

$session_id = isset($_GET['session_id']) ? intval($_GET['session_id']) : null;
$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : null;

try {
    // If session_id provided, fetch the group_id from attendance_sessions
    if ($session_id) {
        $stmt = $conn->prepare("SELECT group_id FROM attendance_sessions WHERE id = :sid LIMIT 1");
        $stmt->execute([':sid' => $session_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['group_id'])) {
            $group_id = (int)$row['group_id'];
        }
    }

    // If group_id known, fetch students for that group
    if ($group_id) {
        $stmt = $conn->prepare("SELECT id, fullname, matricule, group_id FROM students WHERE group_id = :gid ORDER BY fullname ASC");
        $stmt->execute([':gid' => $group_id]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // fallback: return all students (or you can limit)
        $stmt = $conn->query("SELECT id, fullname, matricule, group_id FROM students ORDER BY fullname ASC LIMIT 500");
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode(['success' => true, 'students' => $students]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
