<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Partnerships</h1>

<div style="text-align:center;">
    <a href="create.php" class="button-link">+ Add Partnership</a>
</div>

<table>
<tr>
    <th>ID</th>
    <th>Leader</th>
    <th>Follower</th>
    <th>Actions</th>
</tr>

<?php
$sql = "
SELECT p.partnership_id,
       d1.first_name AS leader_first,
       d1.last_name AS leader_last,
       d2.first_name AS follower_first,
       d2.last_name AS follower_last
FROM Partnerships p
JOIN Dancers d1 ON p.leader_id = d1.dancer_id
JOIN Dancers d2 ON p.follower_id = d2.dancer_id
";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    echo "<tr>
        <td>{$row['partnership_id']}</td>
        <td>{$row['leader_first']} {$row['leader_last']}</td>
        <td>{$row['follower_first']} {$row['follower_last']}</td>
        <td>
            <a href='update.php?id={$row['partnership_id']}'>Edit</a> |
            <a href='delete.php?id={$row['partnership_id']}' onclick='return confirm(\"Delete?\")'>Delete</a>
        </td>
    </tr>";
}
?>
</table>

<?php include("../includes/footer.php"); ?>