<?php include("../includes/header.php"); ?>
<?php require_once("../dbconn.php"); ?>

<?php
$id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT competition_id, dance_name, level, style
    FROM Events WHERE event_id=?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($competition_id, $dance, $level, $style);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("
        UPDATE Events 
        SET competition_id=?, dance_name=?, level=?, style=?
        WHERE event_id=?
    ");

    $stmt->bind_param(
        "isssi",
        $_POST['competition_id'],
        $_POST['dance_name'],
        $_POST['level'],
        $_POST['style'],
        $id
    );

    $stmt->execute();
    header("Location: read.php");
    exit;
}
?>

<h1>Edit Event</h1>

<div class="content">
<form method="POST" class="card-form">

    <label>Competition</label>
    <select name="competition_id">
        <?php
        $res = $conn->query("SELECT * FROM Competitions");
        while ($c = $res->fetch_assoc()) {
            $sel = ($c['competition_id'] == $competition_id) ? "selected" : "";
            echo "<option value='{$c['competition_id']}' $sel>
                {$c['competition_name']}
            </option>";
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

    <button type="submit">Update</button>

</form>
</div>

<?php include("../includes/footer.php"); ?>