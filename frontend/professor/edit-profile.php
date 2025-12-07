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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../assets/style.css">

    <style>
        .content {
            margin-left: 260px;
            padding: 40px;
        }

        .profile-container {
            max-width: 900px;
            margin: auto;
        }

        .profile-card {
            background: #fff;
            border-radius: 18px;
            padding: 35px 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .profile-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 18px;
        }

        /* GRID WRAPPER */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px 28px;
        }

        .field-group {
            display: flex;
            flex-direction: column;
        }

        .field-group label {
            font-size: 14px;
            color: #555;
            margin-bottom: 6px;
        }

        .field-group input {
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .field-group input:focus {
            outline: none;
            border-color: #1a73e8;
        }

        /* SPECIALIZATION - full width */
        .full-width {
            grid-column: span 2;
        }

        /* BUTTONS */
        .action-buttons {
            margin-top: 10px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .cancel-btn {
            background: #f1f3f4;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            color: #555;
            cursor: pointer;
        }

        .save-btn {
            background: #1a73e8;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
        }

        .save-btn:hover {
            background: #166ad7;
        }
    </style>
</head>

<body>

<div class="content">

    <div class="profile-container">

        <h2 class="profile-title">Edit Profile</h2>

        <form action="../../backend/update_profile.php" 
              method="post" 
              class="profile-card">

            <div class="form-grid">

                <div class="field-group">
                    <label>Full Name</label>
                    <input name="name" value="<?= htmlspecialchars($prof['name']) ?>" required>
                </div>

                <div class="field-group">
                    <label>Email Address</label>
                    <input name="email" value="<?= htmlspecialchars($prof['email']) ?>" required>
                </div>

                <div class="field-group">
                    <label>Phone Number</label>
                    <input name="phone" value="<?= htmlspecialchars($prof['phone']) ?>">
                </div>

                <div class="field-group">
                    <label>Employee ID</label>
                    <input name="employee_id" value="<?= htmlspecialchars($prof['employee_id']) ?>">
                </div>

                <div class="field-group">
                    <label>Department</label>
                    <input name="department" value="<?= htmlspecialchars($prof['department']) ?>">
                </div>

                <div class="field-group">
                    <label>Office Location</label>
                    <input name="office_location" value="<?= htmlspecialchars($prof['office_location']) ?>">
                </div>

                <div class="field-group full-width">
                    <label>Specialization</label>
                    <input name="specialization" value="<?= htmlspecialchars($prof['specialization']) ?>">
                </div>

            </div>

            <div class="action-buttons">
                <a href="profile.php" class="cancel-btn">Cancel</a>
                <button type="submit" class="save-btn">Save Changes</button>
            </div>

        </form>
    </div>

</div>

</body>
</html>
