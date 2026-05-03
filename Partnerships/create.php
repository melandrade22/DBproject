<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Add Partnership</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $leader_id = $_POST['leader_id'];
    $follower_id = $_POST['follower_id'];

    #SAME PERSON CHECK
    if ($leader_id == $follower_id) {
        echo "<p class='error'>Leader and follower must be different dancers.</p>";
    } else {

        #DUPLICATE CHECK
        $check = $conn->prepare("
            SELECT 1 FROM Partnerships 
            WHERE leader_id=? AND follower_id=?
        ");
        $check->bind_param("ii", $leader_id, $follower_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<p class='error'>This partnership already exists.</p>";
        } else {

            $stmt = $conn->prepare("
                INSERT INTO Partnerships (leader_id, follower_id)
                VALUES (?, ?)
            ");
            $stmt->bind_param("ii", $leader_id, $follower_id);

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

<div class="content">
<form method="POST" class="card-form">

    <label>Leader</label>
    <select name="leader_id" required>
        <?php
        $res = $conn->query("SELECT dancer_id, first_name, last_name FROM Dancers");
        while ($d = $res->fetch_assoc()) {
            echo "<option value='{$d['dancer_id']}'>{$d['first_name']} {$d['last_name']}</option>";
        }
        ?>
    </select>

    <label>Follower</label>
    <select name="follower_id" required>
        <?php
        $res = $conn->query("SELECT dancer_id, first_name, last_name FROM Dancers");
        while ($d = $res->fetch_assoc()) {
            echo "<option value='{$d['dancer_id']}'>{$d['first_name']} {$d['last_name']}</option>";
        }
        ?>
    </select>

    <button type="submit">Add Partnership</button>

</form>
</div>

<?php include("../includes/footer.php"); ?>