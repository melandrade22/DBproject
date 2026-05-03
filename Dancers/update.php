<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<?php
$id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT first_name, last_name, student_status, affiliation_id 
    FROM Dancers WHERE dancer_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->bind_result($first, $last, $student, $affiliation_id);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $student = isset($_POST['student_status']) ? 1 : 0;
    $affiliation = $_POST['affiliation_id'] ?: NULL;

    $stmt = $conn->prepare("
    UPDATE Dancers 
    SET first_name=?, last_name=?, student_status=?, affiliation_id=? 
    WHERE dancer_id=?");
    $stmt->bind_param("ssiii", $first, $last, $student, $affiliation, $id);
    $stmt->execute();

    header("Location: read.php");
    exit;
}
?>

<h1>Edit Dancer</h1>

<div class="content">
    <form method="POST" class="card-form">
        <label>First Name</label>
        <input name="first_name" value="<?= $first ?>" required>
        
        <label>Last Name</label>
        <input name="last_name" value="<?= $last ?>" required>

        <label>Affiliation</label>
        <select name="affiliation_id">

            <option value="">-- Unaffiliated --</option>

            <?php
            $res = $conn->query("SELECT affiliation_id, affiliation_name FROM Affiliations");
            while ($a = $res->fetch_assoc()) {
                $selected = ($a['affiliation_id'] == $affiliation_id) ? "selected" : "";
                echo "<option value='{$a['affiliation_id']}' $selected>{$a['affiliation_name']}</option>";
            }
            ?>
        </select>

        <label class="checkbox-row">
            Current Student?
            <input type="checkbox" name="student_status" <?= $student ? "checked" : "" ?>>
        </label>

        <button type="submit">Update</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>