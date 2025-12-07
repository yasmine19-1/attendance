<?php
require_once __DIR__ . "/../db.php";   // adjust path if your db.php is elsewhere
$conn = getConnection();

// Fetch sessions
$sql = "SELECT s.id, s.course_id, s.group_id, s.date, s.opened_by, s.status,
               c.name AS course_name, g.name AS group_name, u.fullname AS opener
        FROM attendance_sessions s
        LEFT JOIN courses c ON c.id = s.course_id
        LEFT JOIN `groups` g ON g.id = s.group_id
        LEFT JOIN users u ON u.id = s.opened_by
        ORDER BY s.date DESC, s.id DESC";
$stmt = $conn->query($sql);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Sessions List</title>
  <style>
    table { border-collapse: collapse; width: 95%; margin: 12px auto; }
    th,td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    .open { background:#e8ffe8; }
    .closed { background:#ffe8e8; }
    .btn { padding:6px 10px; text-decoration:none; border-radius:4px; }
    .close { background:#d9534f; color:white; }
    .mark { background:#0275d8; color:white; }
  </style>
</head>
<body>
  <h2 style="text-align:center">Attendance Sessions</h2>
  <div style="width:95%; margin:0 auto 12px;">
    <a href="../professor/create_session.html" class="btn">Create session (UI)</a>
    <a href="create_session.php" class="btn">Create (quick)</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Course</th>
        <th>Group</th>
        <th>Opened by</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($sessions)): ?>
        <tr><td colspan="7">No sessions found.</td></tr>
      <?php else: ?>
        <?php foreach ($sessions as $s): ?>
          <?php $rowClass = ($s['status'] === 'open') ? 'open' : 'closed'; ?>
          <tr class="<?= htmlspecialchars($rowClass) ?>">
            <td><?= htmlspecialchars($s['id']) ?></td>
            <td><?= htmlspecialchars($s['date']) ?></td>
            <td><?= htmlspecialchars($s['course_name'] ?: $s['course_id']) ?></td>
            <td><?= htmlspecialchars($s['group_name'] ?: $s['group_id']) ?></td>
            <td><?= htmlspecialchars($s['opener'] ?: $s['opened_by']) ?></td>
            <td><?= htmlspecialchars($s['status']) ?></td>
            <td>
              <?php if ($s['status'] === 'open'): ?>
                <a class="btn close" href="close_session.php?id=<?= urlencode($s['id']) ?>"
                   onclick="return confirm('Close session <?= $s['id'] ?>?');">Close</a>
                <!-- Link to page that marks attendance for this session -->
                <a class="btn mark" href="../../frontend/professor/attendance.html?session_id=<?= urlencode($s['id']) ?>">Mark</a>
              <?php else: ?>
                <span style="color:#666">Closed</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
