<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Dancers</h1>

<div style="text-align:center;">
    <a href="create.php" class="button-link">+ Add Dancer</a>
</div>

<table>
<tr>
    <th>ID</th>
    <th>First</th>
    <th>Last</th>
    <th>Current Student</th>
    <th>Affiliation</th>
    <th>Actions</th>
</tr>

<?php
$sql = "
SELECT d.*, a.affiliation_name 
FROM Dancers d
LEFT JOIN Affiliations a 
ON d.affiliation_id = a.affiliation_id
";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $student = $row['student_status'] ? "Yes" : "No";
    $aff = $row['affiliation_name'] ?? "Unaffiliated";

    echo "<tr>
        <td>{$row['dancer_id']}</td>
        <td>{$row['first_name']}</td>
        <td>{$row['last_name']}</td>
        <td>{$student}</td>
        <td>{$aff}</td>
        <td>
            <a href='update.php?id={$row['dancer_id']}'>Edit</a>
            <a href='delete.php?id={$row['dancer_id']}' onclick='return confirm(\"Delete?\")'>Delete</a>
        </td>
    </tr>";
}
?>
</table>

<?php
$conn->close(); 
include("../includes/footer.php"); 
?>