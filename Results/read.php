<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Results</h1>

<div style="text-align:center;">
    <a href="create.php" class="button-link">+ Add Result</a>
</div>

<table>
<tr>
    <th>ID</th>
    <th>Event</th>
    <th>Partnership</th>
    <th>Placement</th>
    <th>Actions</th>
</tr>

<?php
$sql = "SELECT * FROM results_view";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>" . htmlspecialchars($row['result_id']) . "</td>

        <td>
            " . htmlspecialchars($row['competition_name']) . " - 
            " . htmlspecialchars($row['dance_name']) . " 
            (" . htmlspecialchars($row['style']) . " " . htmlspecialchars($row['level']) . ")
            [" . htmlspecialchars($row['event_id']) . "]
        </td>

        <td>
            " . htmlspecialchars($row['leader_first']) . " " . htmlspecialchars($row['leader_last']) . " &
            " . htmlspecialchars($row['follower_first']) . " " . htmlspecialchars($row['follower_last']) . "
            [" . htmlspecialchars($row['partnership_id']) . "]
        </td>

        <td>" . htmlspecialchars($row['placement_display']) . "</td>

        <td>
            <a href='update.php?id=" . $row['result_id'] . "'>Edit</a> |
            <a href='delete.php?id=" . $row['result_id'] . "' onclick='return confirm(\"Delete?\")'>Delete</a>
        </td>
    </tr>";
}
?>
</table>

<?php include("../includes/footer.php"); ?>