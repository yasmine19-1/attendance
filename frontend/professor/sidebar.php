<?php
if (!isset($_SESSION)) session_start();
$profName = $_SESSION['professor_name'] ?? 'Professor';
?>

<aside class="sidebar" id="sidebar">

<!-- Close button (mobile only) -->
<button class="close-btn" id="closeSidebar">✕</button>

<div class="sidebar-header">
    <div class="portal-title">Professor Portal</div>
    <div class="portal-subtitle">Welcome, <?= htmlspecialchars($profName) ?></div>
</div>

<nav class="nav-menu">

    <a href="index.php"
       class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
        Dashboard
    </a>

    <a href="courses.php"
       class="<?= basename($_SERVER['PHP_SELF']) === 'courses.php' ? 'active' : '' ?>">
        My Courses
    </a>

    <a href="profile.php"
       class="<?= basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : '' ?>">
        Profile
    </a>

    <a href="../../backend/logout.php" class="logout">Logout</a>
</nav>

</aside>
