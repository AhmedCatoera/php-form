<?php
session_start();

// Delete a single incident by index
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $idx = (int)$_GET['delete'];
    if (isset($_SESSION['incidents'][$idx])) {
        array_splice($_SESSION['incidents'], $idx, 1);
        header('Location: reports.php');
        exit;
    }
}

// Clear all incidents and cookies
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    $_SESSION = [];
    session_unset();
    session_destroy();
    setcookie('last_incident', '', time() - 3600, '/');
    setcookie('last_location', '', time() - 3600, '/');
    header('Location: index.php');
    exit;
}

$incidents = $_SESSION['incidents'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stored Incidents (Session)</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Stored Incidents (Session)</h2>

    <?php if (empty($incidents)): ?>
        <p class="muted">No incidents stored in session.</p>
    <?php else: ?>
        <table class="incidents">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Severity</th>
                    <th>Submitted At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incidents as $i => $inc): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo htmlspecialchars($inc['type']); ?></td>
                        <td><?php echo htmlspecialchars($inc['location']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($inc['description'])); ?></td>
                        <td><?php echo htmlspecialchars($inc['severity']); ?></td>
                        <td><?php echo htmlspecialchars($inc['submitted_at']); ?></td>
                        <td>
                            <a class="link-delete" href="reports.php?delete=<?php echo $i; ?>" onclick="return confirm('Delete this incident?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p class="actions">
        <a href="index.php" class="btn">Back to form</a>
        <a href="reports.php?action=clear" class="btn btn-danger" onclick="return confirm('Clear all stored incidents and cookies?');">Clear all</a>
    </p>
</div>
</body>
</html>
