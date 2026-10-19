<?php
$db = new SQLite3(__DIR__ . "/data/events.db");

if (!isset($_GET['id'])) {
    die("Event not found.");
}

$id = intval($_GET['id']);
$stmt = $db->prepare("SELECT * FROM events WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$result = $stmt->execute();
$event = $result->fetchArray();

if (!$event) {
    die("Event not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($event['title']); ?> RSVP</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f8f9fa;
}
</style>
</head>
<body>

<div class="container py-4">

<a href="index.php" class="btn btn-secondary mb-3">← Back</a>

<h3 class="mb-3"><?php echo htmlspecialchars($event['title']); ?></h3>

<?php if(!empty($event['rsvp'])): ?>
<iframe 
    src="<?php echo htmlspecialchars($event['rsvp']); ?>" 
    width="100%" 
    height="900" 
    style="border:none; border-radius:12px;">
</iframe>
<?php else: ?>
<p>No RSVP available.</p>
<?php endif; ?>

</div>

</body>
</html>