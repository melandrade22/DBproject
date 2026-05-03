<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Add Result</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $registration_id = $_POST['registration_id'];

    $placement = ($_POST['placement'] === '' || $_POST['placement'] == 0)
        ? null
        : $_POST['placement'];

    $stmt = $conn->prepare("
        INSERT INTO Results (registration_id, placement)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE placement = VALUES(placement)
    ");

    $stmt->bind_param("ii", $registration_id, $placement);

    if ($stmt->execute()) {
        header("Location: read.php");
        exit;
    } else {
        echo "<p class='error'>" . htmlspecialchars($stmt->error) . "</p>";
    }
}
?>

<div class="content">
<form method="POST" class="card-form">

    <label>Registration</label>
    <select name="registration_id" required>
        <?php
        $sql = "
        SELECT 
            r.registration_id,
            c.competition_name,
            e.dance_name,
            e.style,
            e.level,
            d1.first_name AS leader_first,
            d1.last_name AS leader_last,
            d2.first_name AS follower_first,
            d2.last_name AS follower_last
        FROM Registrations r
        JOIN Events e ON r.event_id = e.event_id
        JOIN Competitions c ON e.competition_id = c.competition_id
        JOIN Partnerships p ON r.partnership_id = p.partnership_id
        JOIN Dancers d1 ON p.leader_id = d1.dancer_id
        JOIN Dancers d2 ON p.follower_id = d2.dancer_id
        ";

        $res = $conn->query($sql);

        while ($row = $res->fetch_assoc()) {
            echo "<option value='{$row['registration_id']}'>
                {$row['competition_name']} - 
                {$row['dance_name']} ({$row['style']} {$row['level']})
                | {$row['leader_first']} {$row['leader_last']} & 
                  {$row['follower_first']} {$row['follower_last']}
                [Reg {$row['registration_id']}]
            </option>";
        }
        ?>
    </select>

    <label>Placement</label>
    <input type="number" name="placement" min="0" placeholder="0 = Non-final">

    <button type="submit">Add Result</button>

</form>
</div>

<?php include("../includes/footer.php"); ?>