<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<?php
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Missing result ID");
}

/* LOAD CURRENT DATA */
$stmt = $conn->prepare("
    SELECT registration_id, placement
    FROM Results
    WHERE result_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($registration_id, $placement);
$stmt->fetch();
$stmt->close();

/* UPDATE HANDLER */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $placement = ($_POST['placement'] === '' || $_POST['placement'] == 0)
        ? null
        : $_POST['placement'];

    $stmt = $conn->prepare("
        UPDATE Results 
        SET registration_id = ?, placement = ?
        WHERE result_id = ?
    ");

    $stmt->bind_param(
        "iii",
        $_POST['registration_id'],
        $placement,
        $id
    );

    $stmt->execute();

    header("Location: read.php");
    exit;
}
?>

<h1>Edit Result</h1>

<div class="content">
<form method="POST" class="card-form">

    <label>Registration</label>
    <select name="registration_id" required>
        <?php
        $res = $conn->query("
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
        ");

        while ($row = $res->fetch_assoc()) {

            $sel = ($row['registration_id'] == $registration_id) ? "selected" : "";

            echo "<option value='{$row['registration_id']}' $sel>
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
    <input type="number" name="placement" value="<?= htmlspecialchars($placement) ?>" min="0">

    <button type="submit">Update Result</button>

</form>
</div>

<?php include("../includes/footer.php"); ?>