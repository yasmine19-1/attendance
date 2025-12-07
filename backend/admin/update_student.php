<?php
require_once __DIR__ . "/../db.php"; 
// if file is inside backend/admin or backend/attendance
$conn = getConnection();

$id = $_GET["id"];

// Load existing student
$stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
$stmt->execute([$id]);
$student = $stmt->fetch();
// die = terminate the script
if (!$student) die("Student not found");

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $matricule = $_POST["matricule"];
    $group_id = $_POST["group_id"];

    $update = $conn->prepare("UPDATE students SET fullname=?, matricule=?, group_id=? WHERE id=?");

    if ($update->execute([$fullname, $matricule, $group_id, $id])) {
        // same here echo "✔ Student updated!"; won't appear because of redirection
        header("Location: list_students.php");
        exit;
    } else {
        echo "❌ Update failed!";
    }
}
?>

<form method="POST">
    Full name: <input type="text" name="fullname" value="<?= $student['fullname'] ?>"><br>
    Matricule: <input type="text" name="matricule" value="<?= $student['matricule'] ?>"><br>
    Group: <input type="text" name="group_id" value="<?= $student['group_id'] ?>"><br>
    <button type="submit">Update</button>
</form>
