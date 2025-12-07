<?php
// backend/attendance/get_sessions.php
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/../db.php";
$conn = getConnection();

$course_id = isset($_GET["course_id"]) ? intval($_GET["course_id"]) : null;

if (!$course_id) {
    echo json_encode(["success" => false, "error" => "Missing course_id"]);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT 
            id,
            session_date,
            start_time,
            end_time,
            group_id
        FROM attendance_sessions
        WHERE course_id = :cid
        ORDER BY session_date DESC, start_time ASC
    ");
    $stmt->execute([':cid' => $course_id]);

    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "sessions" => $sessions]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
