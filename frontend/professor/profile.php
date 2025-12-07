<?php
session_start();
require_once __DIR__ . '/../../backend/db.php';
require_once __DIR__ . '/sidebar.php';

if (!isset($_SESSION['professor_id'])) {
    header("Location: ../login.html");
    exit;
}

$conn = getConnection();

$profId = $_SESSION['professor_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $profId]);
$prof = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prof) {
    die("Professor not found.");
}

function getInitials($name) {
    $parts = explode(" ", $name);
    $initials = "";
    foreach ($parts as $p) {
        if (trim($p) !== "") $initials .= strtoupper($p[0]);
    }
    return substr($initials, 0, 2);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="../assets/style.css">

    <style>
        .content { margin-left: 260px; padding: 40px 50px; }

        .profile-card {
            background: #fff;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            width: 800px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 25px;
            margin-bottom: 25px;
        }

        .profile-avatar {
            width: 90px;
            height: 90px;
            background: #1a73e8;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 35px;
            font-weight: 700;
            border-radius: 50%;
        }

        .profile-fields {
            margin-top: 15px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .field-group label {
            font-size: 13px;
            color: #666;
        }

        .field-group input {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            pointer-events: none;
        }

        .edit-btn {
            background: #1a73e8;
            color: white;
            padding: 8px 15px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="content">

    <h2>Profile</h2>

    <div class="profile-card">

        <div class="profile-header">
            <div class="profile-avatar"><?= getInitials($prof['name']); ?></div>
            <div>
                <h3><?= htmlspecialchars($prof['name']) ?></h3>
                <p><?= htmlspecialchars($prof['email']) ?></p>
            </div>

            <a class="edit-btn" href="edit-profile.php">Edit Profile</a>
        </div>

        <div class="profile-fields">

            <div class="field-group">
                <label>Phone Number</label>
                <input value="<?= htmlspecialchars($prof['phone'] ?? '') ?>">
            </div>

            <div class="field-group">
                <label>Employee ID</label>
                <input value="<?= htmlspecialchars($prof['employee_id'] ?? '') ?>">
            </div>

            <div class="field-group">
                <label>Department</label>
                <input value="<?= htmlspecialchars($prof['department'] ?? '') ?>">
            </div>

            <div class="field-group">
                <label>Office Location</label>
                <input value="<?= htmlspecialchars($prof['office_location'] ?? '') ?>">
            </div>

            <div class="field-group" style="grid-column: span 2;">
                <label>Specialization</label>
                <input value="<?= htmlspecialchars($prof['specialization'] ?? '') ?>">
            </div>

        </div>
    </div>

</div>

</body>
</html>
