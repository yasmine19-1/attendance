<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../db.php";
$conn = getConnection();

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

if (!$course_id) {
    echo json_encode(['success' => false, 'error' => 'course_id required']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT 
            s.id AS session_id,
            s.date,
            s.group_id,
            g.name AS group_name,
            s.status
        FROM attendance_sessions s
        LEFT JOIN groups g ON g.id = s.group_id
        WHERE s.course_id = :cid
        ORDER BY s.date DESC
    ");
    
    $stmt->execute([':cid' => $course_id]);
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'sessions' => $sessions]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
