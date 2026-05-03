<?php
require_once("../dbconn.php");

$id = $_GET['id'];

try {
    $stmt = $conn->prepare("DELETE FROM Events WHERE event_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
} catch (Exception $e) {
    echo "Cannot delete: record is in use.";
}

header("Location: read.php");
exit;
?>