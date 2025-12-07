<?php
require_once __DIR__ . '/../db.php';
$conn = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $course_id    = intval($_POST['course_id']);
    $professor_id = intval($_POST['professor_id'] ?? 1);

    $topic        = trim($_POST['topic'] ?? "");
    $date         = $_POST['session_date'] ?? null;
    $start_time   = $_POST['start_time'] ?? null;
    $end_time     = $_POST['end_time'] ?? null;

    if (!$topic || !$date || !$start_time || !$end_time) {
        die("Missing required fields: topic, date, start_time, end_time");
    }

    $stmt = $conn->prepare("
        INSERT INTO sessions (course_id, professor_id, topic, session_date, start_time, end_time)
        VALUES (:cid, :pid, :topic, :date, :start_time, :end_time)
    ");

    try {
        $stmt->execute([
            ':cid'        => $course_id,
            ':pid'        => $professor_id,
            ':topic'      => $topic,
            ':date'       => $date,
            ':start_time' => $start_time,
            ':end_time'   => $end_time
        ]);

        header("Location: ../../frontend/professor/sessions.php?course=" . $course_id);
        exit;

    } catch (PDOException $e) {
        echo "Error creating session: " . $e->getMessage();
    }
}
?>



