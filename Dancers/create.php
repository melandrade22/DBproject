<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Add Dancer</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $student = isset($_POST['student_status']) ? 1 : 0;
    $affiliation = $_POST['affiliation_id'] ?: null;

    $stmt = $conn->prepare(
        "INSERT INTO Dancers (first_name, last_name, student_status, affiliation_id)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssii", $first, $last, $student, $affiliation);

    if ($stmt->execute()) {
        header("Location: read.php");
        exit;
    } else {
        echo "<p class='error'>Error: {$stmt->error}</p>";
    }
}
?>

<div class="content">
    <form method="POST" class="card-form">

        <label>First Name</label>
        <input name="first_name" placeholder="First Name" required>

        <label>Last Name</label>
        <input name="last_name" placeholder="Last Name" required>

        <label>Affiliation</label>
        <select name="affiliation_id">
            <option value="">-- Unaffiliated --</option>
            <?php
            $res = $conn->query("SELECT affiliation_id, affiliation_name FROM Affiliations");
            while ($a = $res->fetch_assoc()) {
                echo "<option value='{$a['affiliation_id']}'>{$a['affiliation_name']}</option>";
            }
            ?>
        </select>

        <label class="checkbox-row">
            Current Student?
            <input type="checkbox" name="student_status">
        </label>

        <button type="submit">Add Dancer</button>

    </form>
</div>

<?php include("../includes/footer.php"); ?>