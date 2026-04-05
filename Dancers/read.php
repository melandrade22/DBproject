<?php
require_once('../dbconn.php');

echo "<h2>Dancers List</h2>";
echo "<table border='1'>
<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Student</th>
<th>Actions</th>
</tr>";

$sql = "SELECT dancer_id, first_name, last_name, student_status FROM Dancers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $student = $row['student_status'] ? 'Yes' : 'No';
        echo "<tr>
        <td>{$row['dancer_id']}</td>
        <td>{$row['first_name']}</td>
        <td>{$row['last_name']}</td>
        <td>{$student}</td>
        <td>
            <a href='update.php?id={$row['dancer_id']}'>Update</a> | 
            <a href='delete.php?id={$row['dancer_id']}'>Delete</a>
        </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No dancers found</td></tr>";
}

echo "</table>";
echo "<br><a href='create.php'>Add New Dancer</a>";

$conn->close();
?>