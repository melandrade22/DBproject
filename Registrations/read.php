<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Registrations</h1>

<div style="text-align:center;">
    <a href="create.php" class="button-link">+ Add Registration</a>
</div>

<table>
<tr>
    <th>Registration ID</th>
    <th>Event</th>
    <th>Dancers</th>
    <th>Type</th>
    <th>Fee</th>
    <th>Actions</th>
</tr>

<?php
$sql = "SELECT * FROM registration_view";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['registration_id']}</td>
        <td>
            {$row['competition_name']} - 
            {$row['dance_name']} ({$row['style']} {$row['level']})
            [{$row['event_id']}]
        </td>

        <td>{$row['leader_first']} {$row['leader_last']} & {$row['follower_first']} {$row['follower_last']}</td>
        <td>{$row['registration_type']}</td>
        <td>$" . number_format($row['fee_pay'] ?? 0) . "</td>
        <td>
            <a href='update.php?id={$row['registration_id']}'>Edit</a> |
            <a href='delete.php?id={$row['registration_id']}' onclick='return confirm(\"Delete?\")'>Delete</a>
        </td>
    </tr>";
}
?>
</table>

<?php include("../includes/footer.php"); ?>