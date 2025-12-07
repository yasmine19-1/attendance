<?php
session_start();
require_once('../../backend/db.php');

// Protect the page
if (!isset($_SESSION['professor_id'])) {
    header("Location: /AttendanceSystem/frontend/login.html");
    exit;
}

$conn = getConnection();

// Total courses
$courses = $conn->query("SELECT COUNT(*) AS total FROM courses")->fetch();

// Total students
$students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch();

// Sessions count this week
$weekSessions = $conn->query("
    SELECT COUNT(*) AS total
    FROM sessions
    WHERE YEARWEEK(session_date) = YEARWEEK(NOW())
")->fetch();

// Weekly sessions list
$sessions = $conn->query("
    SELECT s.*, c.course_name
    FROM sessions s
    JOIN courses c ON c.id = s.course_id
    WHERE YEARWEEK(s.session_date) = YEARWEEK(NOW())
    ORDER BY s.session_date ASC, s.start_time ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professor Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">

    <!-- jQuery (ADDED SAFELY) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<!-- MOBILE TOP BAR -->
<div class="mobile-topbar">
    <button class="hamburger" id="openSidebar">☰</button>
    <span class="mobile-title">Professor Portal</span>
</div>

<div class="layout">

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="content">

        <h1>Welcome Back, <?= htmlspecialchars($_SESSION['professor_name']) ?>!</h1>
        <p class="subtitle">Here’s your teaching overview</p>

        <!-- STAT CARDS -->
        <div class="stats-row">

            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total Courses</h3>
                    <p><?= $courses['total'] ?></p>
                </div>
                <div class="stat-icon book-icon">📘</div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total Students</h3>
                    <p><?= $students['total'] ?></p>
                </div>
                <div class="stat-icon students-icon">👥</div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h3>Sessions This Week</h3>
                    <p><?= $weekSessions['total'] ?></p>
                </div>
                <div class="stat-icon calendar-icon">📅</div>
            </div>

        </div>

        <h2>Upcoming Sessions</h2>
        <p class="section-subtitle">Your scheduled classes</p>

        <div class="session-list">
        <?php while ($row = $sessions->fetch()) { 
            $date = date("Y-m-d", strtotime($row['session_date']));
            $start = date("h:i A", strtotime($row['start_time']));
            $end   = date("h:i A", strtotime($row['end_time']));
        ?>
            <div class="session-card">

                <div class="session-left">
                    <div class="session-title"><?= htmlspecialchars($row['course_name']) ?></div>
                    <div class="session-time"><?= $date ?> — <?= $start ?> to <?= $end ?></div>
                </div>

                <div class="session-right">
                    <a class="session-btn" href="session.php?id=<?= $row['id'] ?>">View</a>
                </div>

            </div>
        <?php } ?>
        </div>

    </main>

</div>

<!-- jQuery Sidebar Script -->
<script>
$(document).ready(function(){

    
    $("#openSidebar").on("click", function () {
        $("#sidebar").addClass("open");
    });

    
    $("#closeSidebar").on("click", function () {
        $("#sidebar").removeClass("open");
    });

});
</script>

</body>
</html>
