<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<?php
$id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT event_id, partnership_id, registration_type
    FROM Registrations WHERE registration_id=?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($event_id, $partnership_id, $type);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("
        UPDATE Registrations 
        SET event_id=?, partnership_id=?, registration_type=?
        WHERE registration_id=?
    ");

    $stmt->bind_param(
        "iisi",
        $_POST['event_id'],
        $_POST['partnership_id'],
        $_POST['registration_type'],
        $id
    );

    $stmt->execute();

    header("Location: read.php");
    exit;
}
?>

<h1>Edit Registration</h1>

<div class="content">
<form method="POST" class="card-form">

    <label>Event</label>
    <select name="event_id">
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
    <select name="partnership_id">
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
            $sel = ($p['partnership_id'] == $partnership_id) ? "selected" : "";

            echo "<option value='{$p['partnership_id']}' $sel>
                {$p['leader_first']} {$p['leader_last']} & {$p['follower_first']} {$p['follower_last']}
            </option>";
        }
        ?>
    </select>

    <label>Type</label>
    <select name="registration_type">
        <?php
        foreach (["Early","Regular","Late"] as $t) {
            $sel = ($t == $type) ? "selected" : "";
            echo "<option $sel>$t</option>";
        }
        ?>
    </select>

    <button type="submit">Update</button>

</form>
</div>

<?php include("../includes/footer.php"); ?>