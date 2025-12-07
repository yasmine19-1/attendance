<?php
// frontend/professor/session.php
require_once __DIR__ . '/../../backend/db.php';
$conn = getConnection();

// include sidebar
require_once __DIR__ . '/sidebar.php';

// Get session ID
$session_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($session_id <= 0) die("Session ID missing.");

// Fetch session info
$stmt = $conn->prepare("
  SELECT s.*, c.course_name
  FROM sessions s
  LEFT JOIN courses c ON c.id = s.course_id
  WHERE s.id = :id
");
$stmt->execute([':id' => $session_id]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$session) die("Session not found.");

// Fetch students enrolled in the course
$stmt = $conn->prepare("
  SELECT st.id, st.fullname, st.matricule
  FROM student_course sc
  JOIN students st ON st.id = sc.student_id
  WHERE sc.course_id = :cid
  ORDER BY st.fullname ASC
");
$stmt->execute([':cid' => $session['course_id']]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch attendance map
$att = $conn->prepare("SELECT student_id, status FROM attendance WHERE session_id = :sid");
$att->execute([':sid' => $session_id]);
$attendanceRows = $att->fetchAll(PDO::FETCH_ASSOC);

$attendanceMap = [];
foreach ($attendanceRows as $r) {
    $attendanceMap[$r['student_id']] = $r['status'];
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Take Attendance - <?= htmlspecialchars($session['course_name']) ?></title>

  <link rel="stylesheet" href="../assets/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    .content { margin-left: 260px; padding: 28px; }
    .card { background:#fff; padding:18px; border-radius:10px; box-shadow:0 1px 0 #eee; margin-bottom:18px; }

    table.att { width:100%; border-collapse:collapse; }
    table.att th, table.att td { padding:12px 10px; border-bottom:1px solid #f0f0f0; text-align:left; }

    .btn-present { background:#12b76a; color:#fff; padding:6px 10px; border-radius:8px; cursor:pointer; }
    .btn-absent { background:#ef4444; color:#fff; padding:6px 10px; border-radius:8px; cursor:pointer; }

    .status-pill { padding:5px 8px; border-radius:12px; color:#fff; font-size:13px; }
    .status-present { background:#16a34a; }
    .status-absent { background:#ef4444; }
  </style>
</head>
<body>

<div class="content">

  <!-- HEADER -->
  <div class="card">
    <h2>
        <?= htmlspecialchars($session['course_name']) ?>
        <?php if (!empty($session['topic'])): ?>
            – <?= htmlspecialchars($session['topic']) ?>
        <?php endif; ?>
    </h2>

    <div style="color:#666; margin-bottom:10px;">
      <?= htmlspecialchars($session['session_date']) ?>
      — <?= htmlspecialchars($session['start_time']) ?>
      to <?= htmlspecialchars($session['end_time']) ?>
    </div>
  </div>

  <!-- ATTENDANCE TABLE -->
  <div class="card">
    <h3>Attendance</h3>

    <?php if (empty($students)): ?>
      <p>No students enrolled for this course.</p>

    <?php else: ?>
      <table class="att">
        <thead>
          <tr>
            <th>Student</th>
            <th>Matricule</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody id="attendance-body">

        <?php foreach ($students as $st):
             $curStatus = $attendanceMap[$st['id']] ?? null;
        ?>
          <tr data-student="<?= $st['id'] ?>">
            <td><?= htmlspecialchars($st['fullname']) ?></td>
            <td><?= htmlspecialchars($st['matricule']) ?></td>

            <td class="status-cell">
              <?php if ($curStatus === 'present'): ?>
                <span class="status-pill status-present">Present</span>
              <?php elseif ($curStatus === 'absent'): ?>
                <span class="status-pill status-absent">Absent</span>
              <?php else: ?>
                <span style="color:#777">Not marked</span>
              <?php endif; ?>
            </td>

            <td>
              <button class="btn-present mark-btn" data-id="<?= $st['id'] ?>" data-status="present">
                  Mark Present
              </button>

              <button class="btn-absent mark-btn" data-id="<?= $st['id'] ?>" data-status="absent">
                  Mark Absent
              </button>
            </td>
          </tr>
        <?php endforeach; ?>

        </tbody>

      </table>
    <?php endif; ?>
  </div>

</div>

<script>
$(document).ready(function() {
    $(".mark-btn").click(function() {
        let studentId = $(this).data("id");
        let newStatus = $(this).data("status");

        $.ajax({
            url: "session_ajax.php",
            method: "POST",
            data: {
                session_id: <?= $session_id ?>,
                student_id: studentId,
                status: newStatus
            },
            success: function(response) {
                let row = $("tr[data-student='" + studentId + "'] .status-cell");

                if (newStatus === "present") {
                    row.html('<span class="status-pill status-present">Present</span>');
                } else {
                    row.html('<span class="status-pill status-absent">Absent</span>');
                }
            },
            error: function() {
                alert("Error saving attendance.");
            }
        });
    });
});
</script>

</body>
</html>
