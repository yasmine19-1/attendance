<?php
require_once __DIR__ . "/../db.php"; 
// if file is inside backend/admin or backend/attendance


$conn = getConnection();

if ($conn) {
    echo "Connection successful!";
}
?>
