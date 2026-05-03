<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $type = $_POST['type'];

    #Duplicate check
    $check = $conn->prepare("SELECT * FROM Affiliations WHERE affiliation_name=? AND affiliation_type=?");
    $check->bind_param("ss", $name, $type);
    $check->execute();
    $exists = $check->get_result();

    if ($exists->num_rows > 0) {
        echo "<p class='error'>Affiliation already exists!</p>";
    } else {

        $stmt = $conn->prepare("INSERT INTO Affiliations (affiliation_name, affiliation_type) VALUES (?,?)");
        $stmt->bind_param("ss", $name, $type);

        if ($stmt->execute())  {
            header("Location: read.php");
            exit;
        } else {
            echo "<p class='error'>Error: {$stmt->error}</p>";
        }
    }
}
?>

<h1>Create New Affiliation</h1>

<div class="content">
    <form method="POST" class="card-form">

        <label>Affiliation Name</label>
        <input name="name" placeholder="Affiliation Name" required>

        <label>Type</label>
        <select name="type">
            <option>University</option>
            <option>Studio</option>
            <option>Independent</option>
        </select>

        <button type="submit">Create</button>

    </form>
</div>

<?php include("../includes/footer.php"); ?>