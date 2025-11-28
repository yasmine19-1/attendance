<?php
// backend/professor/list_courses.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../db.php";
$conn = getConnection();

$prof_id = isset($_GET['professor_id']) ? intval($_GET['professor_id']) : 0;

if (!$prof_id) {
    echo json_encode(['success' => false, 'error' => 'professor_id required']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT id, course_name
        FROM courses
        WHERE professor_id = :pid
        ORDER BY course_name ASC
    ");
    $stmt->execute([':pid' => $prof_id]);

    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'courses' => $courses]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
