<?php
require_once("../dbconn.php");

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $student = isset($_POST['student_status']) ? 1 : 0;

    $sql = "UPDATE Dancers SET first_name=?, last_name=?, student_status=? WHERE dancer_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $first, $last, $student, $id);

    if ($stmt->execute()) {
        echo "<script>window.location='read.php'</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $sql = "SELECT first_name, last_name, student_status FROM Dancers WHERE dancer_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($first, $last, $student);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
}
?>

<h2>Edit Dancer</h2>
<form method="POST">
    First Name: <input type="text" name="first_name" value="<?= $first ?>" required><br>
    Last Name: <input type="text" name="last_name" value="<?= $last ?>" required><br>
    Student: <input type="checkbox" name="student_status" <?= $student ? "checked" : "" ?>><br>
    <input type="submit" value="Update">
</form>