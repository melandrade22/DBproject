<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<?php
$id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT competition_name, location, competition_date,
           early_deadline, regular_deadline, late_deadline,
           early_fee, regular_fee, late_fee
    FROM Competitions
    WHERE competition_id=?
");

$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result(
    $name, $location, $date,
    $early_deadline, $regular_deadline, $late_deadline,
    $early_fee, $regular_fee, $late_fee
);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("
        UPDATE Competitions 
        SET competition_name=?, location=?, competition_date=?,
            early_deadline=?, regular_deadline=?, late_deadline=?,
            early_fee=?, regular_fee=?, late_fee=?
        WHERE competition_id=?
    ");

    $stmt->bind_param(
        "ssssssdddi",
        $_POST['competition_name'],
        $_POST['location'],
        $_POST['competition_date'],
        $_POST['early_deadline'],
        $_POST['regular_deadline'],
        $_POST['late_deadline'],
        $_POST['early_fee'],
        $_POST['regular_fee'],
        $_POST['late_fee'],
        $id
    );

    $stmt->execute();

    header("Location: read.php");
    exit;
}
?>

<h1>Edit Competition</h1>

<div class="content">
<form method="POST" class="card-form">

    <label>Name</label>
    <input name="competition_name" value="<?= $name ?>" required>

    <label>Location</label>
    <input name="location" value="<?= $location ?>" required>

    <label>Date</label>
    <input type="date" name="competition_date" value="<?= $date ?>" required>

    <hr>

    <label>Early Deadline</label>
    <input type="date" name="early_deadline" value="<?= $early_deadline ?>">

    <label>Regular Deadline</label>
    <input type="date" name="regular_deadline" value="<?= $regular_deadline ?>">

    <label>Late Deadline</label>
    <input type="date" name="late_deadline" value="<?= $late_deadline ?>">

    <hr>

    <label>Early Fee</label>
    <input type="number" name="early_fee" value="<?= $early_fee ?>" required>

    <label>Regular Fee</label>
    <input type="number" name="regular_fee" value="<?= $regular_fee ?>" required>

    <label>Late Fee</label>
    <input type="number" name="late_fee" value="<?= $late_fee ?>" required>
    <button type="submit">Update</button>

</form>
</div>

<?php include("../includes/footer.php"); ?>