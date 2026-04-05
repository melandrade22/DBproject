<?php
require_once("../dbconn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $student = isset($_POST['student_status']) ? 1 : 0;

    $sql = "INSERT INTO Dancers (first_name, last_name, student_status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $first, $last, $student);

    if ($stmt->execute()) {
        echo "<script>window.location='read.php'</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<h2>Add Dancer</h2>
<form method="POST">
    First Name: <input type="text" name="first_name" required><br>
    Last Name: <input type="text" name="last_name" required><br>
    Student: <input type="checkbox" name="student_status"><br>
    <input type="submit" value="Add">
</form>