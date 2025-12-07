<?php
// backend/attendance/get_attendance.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../db.php";
$conn = getConnection();

$session_id = isset($_GET['session_id']) ? intval($_GET['session_id']) : 0;

if (!$session_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'session_id required']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT student_id, status, participation FROM attendance_records WHERE session_id = :sid");
    $stmt->execute([':sid' => $session_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert to map for quick lookup on frontend
    $map = [];
    foreach ($rows as $r) {
        $map[$r['student_id']] = [
            'status' => $r['status'],
            'participation' => isset($r['participation']) ? $r['participation'] : 0
        ];
    }

    echo json_encode(['success' => true, 'attendance' => $map]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
