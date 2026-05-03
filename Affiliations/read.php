<?php include("../includes/header.php"); ?>
<?php require_once('../dbconn.php'); ?>

<h1>Affiliations</h1>

<div style="text-align:center;">
    <a href="create.php" class="button-link">+ Add Affiliation</a>
</div>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Type</th>
    <th>Actions</th>
</tr>

<?php
$sql = "SELECT * FROM Affiliations";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['affiliation_id']}</td>
        <td>{$row['affiliation_name']}</td>
        <td>{$row['affiliation_type']}</td>
        <td>
            <a href='update.php?id={$row['affiliation_id']}'>Edit</a> |
            <a href='delete.php?id={$row['affiliation_id']}' onclick='return confirm(\"Delete?\")'>Delete</a>
        </td>
    </tr>";
}
?>

</table>

<?php 
$conn->close(); 
include("../includes/footer.php"); 
?>