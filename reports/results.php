<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Reports Dashboard</h1>

<h2>Dancer Activity Summary</h2>

<?php
$result = $conn->query("SELECT * FROM dancer_summary");

echo "<table>
<tr>
    <th>Dancer ID</th>
    <th>Name</th>
    <th>Student</th>
    <th>Total Entries</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['dancer_id']}</td>
        <td>{$row['first_name']} {$row['last_name']}</td>
        <td>" . ($row['student_status'] ? "Yes" : "No") . "</td>
        <td>{$row['total_entries']}</td>
    </tr>";
}

echo "</table>";
?>

<hr>


<h2>Competition Event Structure</h2>

<?php
$result = $conn->query("SELECT * FROM competition_event_summary");

echo "<table>
<tr>
    <th>Competition</th>
    <th>Event ID</th>
    <th>Dance</th>
    <th>Style</th>
    <th>Level</th>
    <th>Registrations</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['competition_name']}</td>
        <td>{$row['event_id']}</td>
        <td>{$row['dance_name']}</td>
        <td>{$row['style']}</td>
        <td>{$row['level']}</td>
        <td>{$row['total_partnerships_registered']}</td>
    </tr>";
}

echo "</table>";
?>

<hr>