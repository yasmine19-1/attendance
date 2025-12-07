<?php
// frontend/professor/courses.php
require_once __DIR__ . '/../../backend/db.php';
$conn = getConnection();
require_once __DIR__ . '/sidebar.php';

// fetch courses + real student count per course
$stmt = $conn->query("
    SELECT c.id, c.course_name,
        (SELECT COUNT(*) FROM student_course sc WHERE sc.course_id = c.id) AS students_count
    FROM courses c
    ORDER BY c.course_name ASC
");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Courses</title>
  <link rel="stylesheet" href="../assets/style.css">
  
  <style>
    .content { margin-left: 260px; padding: 40px 50px; }

    h1 { font-size: 28px; margin-bottom: 5px; }
    .subtitle { color: #666; margin-bottom: 30px; }

    /* Course Grid */
    .course-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
      gap: 25px;
    }

    /* Card */
    .course-card {
      background: #fff;
      padding: 24px;
      border-radius: 18px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .course-header {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .course-icon {
      background: #e8f0fe;
      padding: 12px;
      font-size: 22px;
      border-radius: 14px;
    }

    .course-title {
      font-size: 18px;
      font-weight: 600;
    }

    /* Info lines */
    .course-info {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #444;
      font-size: 14px;
    }

    .course-info span {
      font-size: 15px;
    }

    /* Button */
    .btn-session {
      margin-top: 8px;
      background: #e8f0fe;
      color: #1a73e8;
      padding: 10px 16px;
      text-decoration: none;
      border-radius: 12px;
      font-weight: 600;
      width: fit-content;
    }
  </style>
</head>

<body>

<div class="content">

  <h1>My Courses</h1>
  <p class="subtitle">You are currently teaching <?= count($courses) ?> courses</p>

  <div class="course-grid">

    <?php foreach ($courses as $c): ?>

      <div class="course-card">

        <div class="course-header">
          <div class="course-icon">📘</div>
          <div class="course-title"><?= htmlspecialchars($c['course_name']) ?></div>
        </div>

        <div class="course-info">
          👥 <span><?= $c['students_count'] ?> students</span>
        </div>

        <!-- IF you add schedule later, place it here -->

        <a href="sessions.php?course=<?= $c['id'] ?>" class="btn-session">View Sessions</a>

      </div>

    <?php endforeach; ?>

  </div>

</div>

</body>
</html>
