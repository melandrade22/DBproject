<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<?php
$id = $_GET['id'];

#getting current data
$stmt = $conn->prepare("
    SELECT leader_id, follower_id 
    FROM Partnerships 
    WHERE partnership_id=?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($leader_id, $follower_id);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $leader = $_POST['leader_id'];
    $follower = $_POST['follower_id'];

    if ($leader == $follower) {
        echo "<p class='error'>Leader and follower must be different dancers.</p>";
    } else {

        $check = $conn->prepare("
            SELECT 1 
            FROM Partnerships 
            WHERE leader_id=? AND follower_id=? 
            AND partnership_id != ?
        ");
        $check->bind_param("iii", $leader, $follower, $id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<p class='error'>This partnership already exists.</p>";
        } else {

            $stmt = $conn->prepare("
                UPDATE Partnerships 
                SET leader_id=?, follower_id=? 
                WHERE partnership_id=?
            ");
            $stmt->bind_param("iii", $leader, $follower, $id);

            if ($stmt->execute()) {
                header("Location: read.php");
                exit;
            } else {
                echo "<p class='error'>Error: {$stmt->error}</p>";
            }
        }
    }
}
?>


<h1>Edit Partnership</h1>

<div class="content">
<form method="POST" class="card-form">

    <label>Leader</label>
    <select name="leader_id">
        <?php
        $res = $conn->query("SELECT dancer_id, first_name, last_name FROM Dancers");
        while ($d = $res->fetch_assoc()) {
            $sel = ($d['dancer_id'] == $leader_id) ? "selected" : "";
            echo "<option value='{$d['dancer_id']}' $sel>
                {$d['first_name']} {$d['last_name']}
            </option>";
        }
        ?>
    </select>

    <label>Follower</label>
    <select name="follower_id">
        <?php
        $res = $conn->query("SELECT dancer_id, first_name, last_name FROM Dancers");
        while ($d = $res->fetch_assoc()) {
            $sel = ($d['dancer_id'] == $follower_id) ? "selected" : "";
            echo "<option value='{$d['dancer_id']}' $sel>
                {$d['first_name']} {$d['last_name']}
            </option>";
        }
        ?>
    </select>

    <button type="submit">Update</button>

</form>
</div>

<?php include("../includes/footer.php"); ?>