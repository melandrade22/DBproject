<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Add Competition</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['competition_name'];
    $location = $_POST['location'];
    $date = $_POST['competition_date'];

    $early_deadline = $_POST['early_deadline'];
    $regular_deadline = $_POST['regular_deadline'];
    $late_deadline = $_POST['late_deadline'];

    $early_fee = $_POST['early_fee'];
    $regular_fee = $_POST['regular_fee'];
    $late_fee = $_POST['late_fee'];

    $stmt = $conn->prepare("
        INSERT INTO Competitions 
        (competition_name, location, competition_date,
         early_deadline, regular_deadline, late_deadline,
         early_fee, regular_fee, late_fee)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssssiii",
        $name,
        $location,
        $date,
        $early_deadline,
        $regular_deadline,
        $late_deadline,
        $early_fee,
        $regular_fee,
        $late_fee
    );

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

    <label>Competition Name</label>
    <input name="competition_name" required>

    <label>Location</label>
    <input name="location" required>

    <label>Date</label>
    <input type="date" name="competition_date" required>

    <hr>

    <label>Early Deadline</label>
    <input type="date" name="early_deadline" required>

    <label>Regular Deadline</label>
    <input type="date" name="regular_deadline" required>

    <label>Late Deadline</label>
    <input type="date" name="late_deadline" required>

    <hr>

    <label>Early Fee</label>
    <input type="number" placeholder="00" name="early_fee" required>

    <label>Regular Fee</label>
    <input type="number" placeholder="00" name="regular_fee" required>

    <label>Late Fee</label>
    <input type="number" placeholder="00" name="late_fee" required>

    <button type="submit">Add Competition</button>

</form>
</div>

<?php include("../includes/footer.php"); ?>