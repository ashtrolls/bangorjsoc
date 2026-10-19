<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("Location: login.php");
    exit;
}

// Database
$dbFile = __DIR__ . '/data/events.db';
$db = new SQLite3($dbFile);
$db->exec("CREATE TABLE IF NOT EXISTS events(
    id INTEGER PRIMARY KEY,
    title TEXT,
    description TEXT,
    date TEXT,
    type TEXT,
    photo TEXT,
    rsvp TEXT
)");

// --- AUTO-UPDATE PAST EVENTS ---
$today = date('Y-m-d');
$db->exec("UPDATE events SET type='past' WHERE date < '$today' AND type='upcoming'");

// Handle new event submission
if(isset($_POST['title'], $_POST['description'], $_POST['date'], $_POST['type'])){

    $photo_name = '';

    if(isset($_FILES['photo']) && $_FILES['photo']['error'] === 0){
        $target_dir = __DIR__ . '/uploads/';
        if(!is_dir($target_dir)) mkdir($target_dir, 0775, true);
        $photo_name = time() . '_' . basename($_FILES['photo']['name']);
        if(!move_uploaded_file($_FILES['photo']['tmp_name'], $target_dir.$photo_name)){
            $upload_error = "Failed to upload photo. Check permissions.";
        }
    }

    $rsvp_link = !empty($_POST['rsvp']) ? trim($_POST['rsvp']) : '';

    $stmt = $db->prepare("INSERT INTO events(title,description,date,type,photo,rsvp) VALUES(:title,:description,:date,:type,:photo,:rsvp)");
    $stmt->bindValue(':title', $_POST['title'], SQLITE3_TEXT);
    $stmt->bindValue(':description', $_POST['description'], SQLITE3_TEXT);
    $stmt->bindValue(':date', $_POST['date'], SQLITE3_TEXT);
    $stmt->bindValue(':type', $_POST['type'], SQLITE3_TEXT);
    $stmt->bindValue(':photo', $photo_name, SQLITE3_TEXT);
    $stmt->bindValue(':rsvp', $rsvp_link, SQLITE3_TEXT);
    if(!$stmt->execute()){
        $db_error = "Failed to add event to database.";
    } else {
        $success_msg = "Event added successfully!";
    }
}

// Handle deletion
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $photo_res = $db->querySingle("SELECT photo FROM events WHERE id=$id");
    if($photo_res){
        $photo_path = __DIR__ . '/uploads/' . $photo_res;
        if(file_exists($photo_path)) unlink($photo_path);
    }
    $db->exec("DELETE FROM events WHERE id=$id");
    header("Location: dashboard.php");
    exit;
}

// Fetch all events for display
$events_res = $db->query("SELECT * FROM events ORDER BY date DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Bangor Jewish Society</title>
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
Bangor Jewish Society
</a>
<ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
</ul>
</div>
</nav>

<div class="container" style="max-width:700px;">
<h2 class="mb-4 text-center">Add New Event</h2>

<?php
if(isset($upload_error)) echo '<div class="alert alert-danger">'.$upload_error.'</div>';
if(isset($db_error)) echo '<div class="alert alert-danger">'.$db_error.'</div>';
if(isset($success_msg)) echo '<div class="alert alert-success">'.$success_msg.'</div>';
?>

<form method="POST" enctype="multipart/form-data">
<div class="mb-3">
<input type="text" class="form-control" name="title" placeholder="Event Title" required>
</div>
<div class="mb-3">
<textarea class="form-control" name="description" placeholder="Description" rows="4" required></textarea>
</div>
<div class="mb-3">
<input type="date" class="form-control" name="date" required>
</div>
<div class="mb-3">
<select class="form-select" name="type">
<option value="upcoming">Upcoming</option>
<option value="past">Past</option>
</select>
</div>
<div class="mb-3">
<input type="file" class="form-control" name="photo">
</div>
<div class="mb-3">
<input type="url" class="form-control" name="rsvp" placeholder="RSVP Link (optional)">
</div>
<button type="submit" class="btn btn-success w-100">Add Event</button>
</form>

<hr class="my-5">

<h2 class="mb-4 text-center">All Events</h2>
<div class="row row-cols-1 row-cols-md-2 g-4">
<?php while($row = $events_res->fetchArray()): ?>
<div class="col">
<div class="card h-100 shadow-sm">
<?php if(!empty($row['photo'])): ?>
<img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" class="card-img-top">
<?php endif; ?>
<div class="card-body">
<h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
<p class="card-text"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
<p class="card-text"><small class="text-muted">
<?php 
    $date = new DateTime($row['date']);
    echo $date->format('j F Y');
?>
</small></p>
<?php if(!empty($row['rsvp'])): ?>
<a href="<?php echo htmlspecialchars($row['rsvp']); ?>" class="btn btn-primary btn-sm mb-2" target="_blank">RSVP</a>
<?php endif; ?>
<div class="mt-2">
<a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm float-end" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
</div>
</div>
</div>
</div>
<?php endwhile; ?>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>