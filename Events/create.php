<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<h1>Add Event</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $competition = $_POST['competition_id'];
    $dance = $_POST['dance_name'];
    $level = $_POST['level'];
    $style = $_POST['style'];

    $stmt = $conn->prepare(
        "INSERT INTO Events (competition_id, dance_name, level, style)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("isss", $competition, $dance, $level, $style);

    if ($stmt->execute()) {
        header("Location: read.php");
        exit;
    }
}
?>

<div class="content">
<form method="POST" class="card-form">

    <label>Competition</label>
    <select name="competition_id">
        <?php
        $res = $conn->query("SELECT * FROM Competitions");
        while ($c = $res->fetch_assoc()) {
            echo "<option value='{$c['competition_id']}'>{$c['competition_name']}</option>";
        }
        ?>
    </select>

    <label>Dance Name</label>
    <select name="dance_name" required>
        <?php
        $dance_names = ['Waltz','Tango','Foxtrot','Viennese Waltz',
        'Quickstep','Cha Cha','Rumba','Swing',
        'Bolero','Mambo','Samba','Paso Doble','Jive'];
        foreach ($dance_names as $l) {
            $sel = ($l == $dance) ? "selected" : "";
            echo "<option $sel>$l</option>";
        }
        ?>
    </select>

    <label>Level</label>
    <select name="level">
        <?php
        $levels = ["Newcomer","Bronze","Silver","Gold","Novice","Pre-Champ","Champ", "Syllabus"];
        foreach ($levels as $l) {
            $sel = ($l == $level) ? "selected" : "";
            echo "<option $sel>$l</option>";
        }
        ?>
    </select>

    <label>Style</label>
    <select name="style">
        <?php
        $styles = ["Smooth","Standard","Rhythm","Latin"];
        foreach ($styles as $s) {
            $sel = ($s == $style) ? "selected" : "";
            echo "<option $sel>$s</option>";
        }
        ?>
    </select>

    <button type="submit">Add Event</button>

</form>
</div>

<?php include("../includes/footer.php"); ?>