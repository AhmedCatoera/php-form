<?php
// Start session and initialize storage for incidents
session_start();
if (!isset($_SESSION['incidents'])) {
    $_SESSION['incidents'] = [];
}

// Handle clear action (clears session and cookies)
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    $_SESSION = [];
    session_unset();
    session_destroy();
    setcookie('last_incident', '', time() - 3600, '/');
    setcookie('last_location', '', time() - 3600, '/');
    header('Location: index.php');
    exit;
}

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
        // store incident in session
        $incident = [
            'type' => $incidentType,
            'location' => $location,
            'description' => $description,
            'severity' => $severity,
            'submitted_at' => date('Y-m-d H:i:s')
        ];
        $_SESSION['incidents'][] = $incident;

        // set cookies for last incident and location (7 days)
        setcookie('last_incident', $incidentType, time() + 7 * 24 * 60 * 60, '/');
        setcookie('last_location', $location, time() + 7 * 24 * 60 * 60, '/');

        echo "<h3>Incident Report Submitted Successfully!</h3>";
        echo "Incident Type: $incidentType <br>";
        echo "Location: $location <br>";
        echo "Description: $description <br>";
        echo "Severity Level: $severity <br>";
        echo '<p><a href="reports.php">View all stored incidents (session)</a></p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Incident Report Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">

<h2>Incident Report Form (POST Method)</h2>

<form method="POST" action="">
    <label>Incident Type:</label><br>
    <input type="text" name="incidentType" value="<?php echo $incidentType ?: ($_COOKIE['last_incident'] ?? ''); ?>">
    <span class="error"><?php echo $errors['incidentType'] ?? ''; ?></span>
    <br><br>

    <label>Location:</label><br>
    <input type="text" name="location" value="<?php echo $location ?: ($_COOKIE['last_location'] ?? ''); ?>">
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

    <input type="submit" value="Submit" class="btn">
</form>

<p>
    <a href="reports.php">View stored incidents (session)</a> |
    <a href="?action=clear" onclick="return confirm('Clear all stored incidents and cookies?');">Clear session & cookies</a>
</p>

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

</div> <!-- .container -->
</body>
</html>