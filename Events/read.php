<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Events</h1>

<div style="text-align:center;">
    <a href="create.php" class="button-link">+ Add Event</a>
</div>

<table>
<tr>
    <th>ID</th>
    <th>Competition</th>
    <th>Dance</th>
    <th>Level</th>
    <th>Style</th>
    <th>Actions</th>
</tr>

<?php
$sql = "
SELECT e.*, c.competition_name
FROM Events e
JOIN Competitions c ON e.competition_id = c.competition_id
";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    echo "<tr>
        <td>{$row['event_id']}</td>
        <td>{$row['competition_name']}</td>
        <td>{$row['dance_name']}</td>
        <td>{$row['level']}</td>
        <td>{$row['style']}</td>
        <td>
            <a href='update.php?id={$row['event_id']}'>Edit</a> |
            <a href='delete.php?id={$row['event_id']}' onclick='return confirm(\"Delete?\")'>Delete</a>
        </td>
    </tr>";
}
?>
</table>

<?php include("../includes/footer.php"); ?>