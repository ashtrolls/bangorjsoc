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
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($event['title']); ?> - Bangor University JSoc</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="uploads/logo.png">
<style>
@font-face {
    font-family: 'NotoEmojiFlags';
    unicode-range: U+1F1E6-1F1FF; /* Targets regional indicator symbols */
    src: url(https://raw.githubusercontent.com/googlefonts/noto-emoji/main/fonts/NotoColorEmoji.ttf);
}

body {
    font-family: 'NotoEmojiFlags', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

body {
    background: #f8f9fa;
}
.event-hero-img {
    max-height: 250px;
    width: 100%;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 20px;
}
</style>
</head>
<body>

<div class="container py-4" style="max-width: 800px;">

<a href="index.php" class="btn btn-secondary mb-3">&larr; Back to Home</a>

<div class="card shadow-sm p-4 mb-4">
    <h2 class="mb-3"><?php echo htmlspecialchars($event['title']); ?></h2>
    
    <p class="text-muted mb-2">
        <strong>Date:</strong> 
        <?php 
            $date = new DateTime($event['date']);
            echo $date->format('j F Y');
        ?>
    </p>

    <?php if(!empty($event['photo'])): ?>
    <img src="uploads/<?php echo htmlspecialchars($event['photo']); ?>" alt="Event Photo" class="event-hero-img">
    <?php endif; ?>

    <p class="card-text mb-4"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>

    <h4 class="mb-3">RSVP</h4>
    <?php if(!empty($event['rsvp'])): ?>
    <iframe 
        src="<?php echo htmlspecialchars($event['rsvp']); ?>" 
        width="100%" 
        height="600" 
        style="border:none; border-radius:12px; background:#fff;">
    </iframe>
    <?php else: ?>
    <p class="text-muted">No RSVP available for this event.</p>
    <?php endif; ?>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>