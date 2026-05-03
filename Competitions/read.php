<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Competitions</h1>

<div style="text-align:center;">
    <a href="create.php" class="button-link">+ Add Competition</a>
</div>

<div style="text-align:center;">
    <a> $20 Student Discount available for all Competitions</a>
</div>
<div style="text-align:center;">
    <a>(As long as a student is in the partnership)</a>
</div>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Location</th>
    <th>Date</th>
    <th>Early</th>
    <th>Regular</th>
    <th>Late</th>
    <th>Actions</th>
</tr>

<?php
$sql = "SELECT * FROM Competitions";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    echo "<tr>
        <td>{$row['competition_id']}</td>
        <td>{$row['competition_name']}</td>
        <td>{$row['location']}</td>
        <td>{$row['competition_date']}</td>
        <td>{$row['early_deadline']} (\${$row['early_fee']})</td>
        <td>{$row['regular_deadline']} (\${$row['regular_fee']})</td>
        <td>{$row['late_deadline']}  (\${$row['late_fee']})</td></td>
        <td>
            <a href='update.php?id={$row['competition_id']}'>Edit</a> |
            <a href='delete.php?id={$row['competition_id']}' onclick='return confirm(\"Delete?\")'>Delete</a>
        </td>
    </tr>";
}
?>
</table>

<?php include("../includes/footer.php"); ?>