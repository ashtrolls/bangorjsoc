<?php
$db = new SQLite3(__DIR__ . "/data/events.db");
$results = $db->query("SELECT * FROM events WHERE type='past' ORDER BY date DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Bangor University JSoc (Jewish Society) - Past Events</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="uploads/logo.png">
<style>
.card-img-top { height: 200px; object-fit: cover; }
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
<div class="container-fluid">
<a class="navbar-brand d-flex align-items-center" href="index.php">
<img src="uploads/logo.png" alt="BJS Logo" width="50" height="50" class="d-inline-block align-text-top me-2">
Bangor University JSoc (Jewish Society)
</a>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
<li class="nav-item"><a class="nav-link" href="login.php">Admin</a></li>
</ul>
</div>
</div>
</nav>

<div class="container">
<h1 class="mb-4 text-center">Past Events</h1>
<div class="row row-cols-1 row-cols-md-3 g-4">
<?php while($row = $results->fetchArray()): ?>
<div class="col">
<div class="card h-100 shadow-sm">
<?php if(!empty($row['photo'])): ?>
<img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" class="card-img-top">
<?php endif; ?>
<div class="card-body">
<h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
<p class="card-text"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
<p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($row['date']); ?></small></p>
<?php if(!empty($row['rsvp'])): ?>
<a href="<?php echo htmlspecialchars($row['rsvp']); ?>" class="btn btn-primary btn-sm" target="_blank">RSVP</a>
<?php endif; ?>
</div>
</div>
</div>
<?php endwhile; ?>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
