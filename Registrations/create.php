<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Add Registration</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $event = $_POST['event_id'];
    $partnership = $_POST['partnership_id'];
    $type = $_POST['registration_type'];

    try {

        $stmt = $conn->prepare("CALL RegisterPartnershipSafe(?, ?, ?)");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("iis", $event, $partnership, $type);
        $stmt->execute();

        header("Location: read.php");
        exit;

    } catch (mysqli_sql_exception $e) {
        echo "<p class='error'>{$e->getMessage()}</p>";
    }
}
?>

<div class="content">
<form method="POST" class="card-form">

    <label>Event</label>
    <select name="event_id" required>
        <?php
        $sql = "
        SELECT 
            e.event_id,
            e.dance_name,
            e.style,
            e.level,
            c.competition_name
        FROM Events e
        JOIN Competitions c ON e.competition_id = c.competition_id
        ";
        
        $res = $conn->query($sql);
        while ($e = $res->fetch_assoc()) {
            echo "<option value='{$e['event_id']}'>" .
                $e['competition_name'] . " - ".
                $e['dance_name'] . " (" .
                $e['style'] . " " .
                $e['level'] . ") [" .
                $e['event_id'] . "]" .
                
            "</option>";
        }
        ?>
    </select>

    <label>Partnership</label>
    <select name="partnership_id" required>
        <?php
        $sql = "
        SELECT 
            p.partnership_id,
            d1.first_name AS leader_first,
            d1.last_name AS leader_last,
            d2.first_name AS follower_first,
            d2.last_name AS follower_last
        FROM Partnerships p
        JOIN Dancers d1 ON p.leader_id = d1.dancer_id
        JOIN Dancers d2 ON p.follower_id = d2.dancer_id
        ";

        $res = $conn->query($sql);

        while ($p = $res->fetch_assoc()) {
            echo "<option value='{$p['partnership_id']}'>
                {$p['leader_first']} {$p['leader_last']} & {$p['follower_first']} {$p['follower_last']}
            </option>";
        }
        ?>
    </select>

    <label>Type</label>
    <select name="registration_type">
        <option>Early</option>
        <option>Regular</option>
        <option>Late</option>
    </select>

    <button type="submit">Register</button>

</form>
</div>

<?php include("../includes/footer.php"); ?>