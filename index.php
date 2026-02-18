<?php
// Initialize variables
$incidentType = $location = $description = $severity = "";
$errors = [];

// Check if form is submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Incident Type validation
    if (empty($_POST["incidentType"])) {
        $errors['incidentType'] = "Incident Type is required.";
    } else {
        $incidentType = htmlspecialchars(trim($_POST["incidentType"]));
    }

    // Location validation
    if (empty($_POST["location"])) {
        $errors['location'] = "Location is required.";
    } else {
        $location = htmlspecialchars(trim($_POST["location"]));
    }

    // Description validation
    if (empty($_POST["description"])) {
        $errors['description'] = "Description is required.";
    } elseif (strlen($_POST["description"]) < 10) {
        $errors['description'] = "Description must be at least 10 characters.";
    } else {
        $description = htmlspecialchars(trim($_POST["description"]));
    }

    // Severity validation
    if (empty($_POST["severity"])) {
        $errors['severity'] = "Severity Level is required.";
    } else {
        $severity = htmlspecialchars(trim($_POST["severity"]));
    }

    // If no errors, display success message
    if (empty($errors)) {
        echo "<h3>Incident Report Submitted Successfully!</h3>";
        echo "Incident Type: $incidentType <br>";
        echo "Location: $location <br>";
        echo "Description: $description <br>";
        echo "Severity Level: $severity <br>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Incident Report Form</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>

<h2>Incident Report Form (POST Method)</h2>

<form method="POST" action="">
    <label>Incident Type:</label><br>
    <input type="text" name="incidentType" value="<?php echo $incidentType; ?>">
    <span class="error"><?php echo $errors['incidentType'] ?? ''; ?></span>
    <br><br>

    <label>Location:</label><br>
    <input type="text" name="location" value="<?php echo $location; ?>">
    <span class="error"><?php echo $errors['location'] ?? ''; ?></span>
    <br><br>

    <label>Description:</label><br>
    <textarea name="description"><?php echo $description; ?></textarea>
    <span class="error"><?php echo $errors['description'] ?? ''; ?></span>
    <br><br>

    <label>Severity Level:</label><br>
    <select name="severity">
        <option value="">-- Select Severity --</option>
        <option value="Low" <?php if($severity=="Low") echo "selected"; ?>>Low</option>
        <option value="Medium" <?php if($severity=="Medium") echo "selected"; ?>>Medium</option>
        <option value="High" <?php if($severity=="High") echo "selected"; ?>>High</option>
    </select>
    <span class="error"><?php echo $errors['severity'] ?? ''; ?></span>
    <br><br>

    <input type="submit" value="Submit">
</form>

<hr>

<h2>Example Using GET Method</h2>

<form method="GET" action="">
    <input type="text" name="testIncident" placeholder="Test Incident">
    <input type="submit" value="Submit via GET">
</form>

<?php
// GET Example
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['testIncident'])) {
    echo "<p>You entered (GET): " . htmlspecialchars($_GET['testIncident']) . "</p>";
}
?>

</body>
</html>
