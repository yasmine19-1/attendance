<?php
require_once __DIR__ . "/../db.php";

$conn = getConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname  = $_POST["fullname"] ?? "";
    $matricule = $_POST["matricule"] ?? "";
    $group_id  = $_POST["group_id"] ?? "";

    $sql = "INSERT INTO students (fullname, matricule, group_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$fullname, $matricule, $group_id])) {
        header("Location: list_students.php");
        exit;
    } else {
        echo "Error adding student.";
    }
}
?>

<h2>Add New Student</h2>

<form method="POST">
    Full name: <input type="text" name="fullname" required><br><br>
    Matricule: <input type="text" name="matricule" required><br><br>
    Group: <input type="text" name="group_id" required><br><br>

    <button type="submit">Add Student</button>
</form>
