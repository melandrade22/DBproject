<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<?php
$id = $_GET['id'];

/* GET CURRENT DATA */
$stmt = $conn->prepare("SELECT affiliation_name, affiliation_type FROM Affiliations WHERE affiliation_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $type);
$stmt->fetch();
$stmt->close();

/* UPDATE LOGIC */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newName = $_POST['name'];
    $newType = $_POST['type'];

    // 🔒 CHECK FOR DUPLICATE (excluding current record)
    $check = $conn->prepare("
        SELECT 1 
        FROM Affiliations 
        WHERE affiliation_name=? 
        AND affiliation_type=? 
        AND affiliation_id != ?
    ");
    $check->bind_param("ssi", $newName, $newType, $id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<p class='error'>This affiliation already exists!</p>";
    } else {
        $update = $conn->prepare("
            UPDATE Affiliations 
            SET affiliation_name=?, affiliation_type=? 
            WHERE affiliation_id=?
        ");
        $update->bind_param("ssi", $newName, $newType, $id);
        $update->execute();

        header("Location: read.php");
        exit;
    }
}
?>

<h1>Edit Affiliation</h1>

<div class="content">
    <form method="POST" class="card-form">

        <label>Name</label>
        <input name="name" value="<?= htmlspecialchars($name) ?>" required>

        <label>Type</label>
        <select name="type">
            <option <?= $type=="University"?"selected":"" ?>>University</option>
            <option <?= $type=="Studio"?"selected":"" ?>>Studio</option>
            <option <?= $type=="Independent"?"selected":"" ?>>Independent</option>
        </select>

        <button type="submit">Update</button>

    </form>
</div>

<?php include("../includes/footer.php"); ?>