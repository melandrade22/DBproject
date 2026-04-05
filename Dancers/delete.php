<?php
require_once("../dbconn.php");

$id = $_GET['id'];

$sql = "DELETE FROM Dancers WHERE dancer_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>window.location='read.php'</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>