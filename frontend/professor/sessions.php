<?php
require_once __DIR__ . '/../../backend/db.php';
$conn = getConnection();
require_once __DIR__ . '/sidebar.php';

// Get course ID
$courseId = isset($_GET['course']) ? intval($_GET['course']) : 0;

// Fetch course info
$courseInfo = null;
if ($courseId > 0) {
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $courseId]);
    $courseInfo = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Count students
$studentCount = 0;
if ($courseId > 0) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM student_course WHERE course_id = :cid");
    $stmt->execute([':cid' => $courseId]);
    $studentCount = $stmt->fetchColumn();
}

// Fetch sessions
$stmt = $conn->prepare("
    SELECT *
    FROM sessions
    WHERE course_id = :cid
    ORDER BY session_date DESC, start_time DESC
");
$stmt->execute([':cid' => $courseId]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compute status
function computeStatus($date, $end_time) {
    $end = new DateTime("$date $end_time");
    $now = new DateTime();
    return ($now < $end) ? "upcoming" : "completed";
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Course Sessions</title>
<link rel="stylesheet" href="../assets/style.css">

<style>
.content {
    margin-left: 260px;
    padding: 40px 50px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-title { font-size: 26px; font-weight: 700; }

.top-buttons { display: flex; gap: 12px; }

.btn-grey {
    background: #f1f1f1;
    padding: 8px 14px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    color: #444;
    text-decoration: none;
}

.btn-blue {
    background: #1a73e8;
    color: #fff;
    padding: 8px 14px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    font-size: 14px;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.upcoming { background: #e8f0fe; color:#1a73e8; }
.completed { background: #d1fae5; color:#047857; }

.session-card {
    background: #fff;
    padding: 22px 26px;
    border-radius: 18px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.session-left { display: flex; flex-direction: column; gap: 6px; }

.session-name { font-size: 17px; font-weight: 600; }

.session-time {
    font-size: 14px;
    color: #666;
    display: flex;
    gap: 6px;
    align-items: center;
}

.session-right a {
    background: #e8f0fe;
    padding: 8px 14px;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    color: #1a73e8;
    font-size: 14px;
}

#modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.35);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-box {
    width: 380px;
    background: #fff;
    padding: 26px;
    border-radius: 14px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}
.modal-box input { width: 100%; padding: 10px; margin: 8px 0; font-size: 14px; }
.modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px; }

.error-text { color: red; margin-bottom: 10px; }
</style>
</head>

<body>

<div class="content">

<div class="page-header">
    <div class="page-title"><?= htmlspecialchars($courseInfo['course_name']) ?></div>

    <div class="top-buttons">
        <a href="courses.php" class="btn-grey">← Back to Courses</a>
        <button class="btn-blue" onclick="openModal()">+ Create Session</button>
    </div>
</div>

<div class="section-title">Course Sessions</div>
<div class="section-subtitle"><?= $studentCount ?> students enrolled</div>

<?php foreach ($sessions as $s): ?>
    <?php 
        $topic  = $s['topic'] ?: "Untitled Session";
        $date   = $s['session_date'];
        $start  = date("H:i", strtotime($s['start_time']));
        $status = computeStatus($s['session_date'], $s['end_time']);
    ?>
    <div class="session-card">
        <div class="session-left">
            <div class="session-name">
                <?= htmlspecialchars($topic) ?>
                <span class="status-badge <?= $status ?>"><?= ucfirst($status) ?></span>
            </div>
            <div class="session-time">📅 <?= $date ?> — <?= $start ?></div>
        </div>

        <div class="session-right">
            <a href="session.php?id=<?= $s['id'] ?>">View</a>
        </div>
    </div>
<?php endforeach; ?>

</div>

<!-- MODAL -->
<div id="modal">
    <div class="modal-box">
        <h3>Create New Session</h3>

        <?php if (isset($_GET['error'])): ?>
            <p class="error-text"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>

        <form action="../../backend/attendance/create_session.php" method="POST">

            <input type="hidden" name="course_id" value="<?= $courseId ?>">

            <label>Session Topic</label>
            <input type="text" name="topic" required>

            <label>Date</label>
            <input type="date" name="session_date" required>

            <label>Start Time</label>
            <input type="time" name="start_time" required>

            <label>End Time</label>
            <input type="time" name="end_time" required>

            <div class="modal-footer">
                <button type="button" class="btn-grey" onclick="closeModal()">Cancel</button>
                <button class="btn-blue" type="submit">Create</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(){ document.getElementById("modal").style.display = "flex"; }
function closeModal(){ document.getElementById("modal").style.display = "none"; }
</script>

</body>
</html>
