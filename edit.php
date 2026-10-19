<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("Location: login.php");
    exit;
}

$dbFile = __DIR__ . '/data/events.db';
$db = new SQLite3($dbFile);

if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET['id']);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['description'], $_POST['date'], $_POST['type'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $rsvp_link = !empty($_POST['rsvp']) ? trim($_POST['rsvp']) : '';

    $current_photo = $db->querySingle("SELECT photo FROM events WHERE id=$id");
    $photo_name = $current_photo;

    if(isset($_FILES['photo']) && $_FILES['photo']['error'] === 0){
        $target_dir = __DIR__ . '/uploads/';
        if(!is_dir($target_dir)) mkdir($target_dir, 0775, true);
        
        $new_photo_name = time() . '_' . basename($_FILES['photo']['name']);
        if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_dir . $new_photo_name)){
            if(!empty($current_photo)){
                $old_photo_path = __DIR__ . '/uploads/' . $current_photo;
                if(file_exists($old_photo_path)) unlink($old_photo_path);
            }
            $photo_name = $new_photo_name;
        } else {
            $upload_error = "Failed to upload new photo. Check permissions.";
        }
    }

    if(!isset($upload_error)){
        $stmt = $db->prepare("UPDATE events SET title = :title, description = :description, date = :date, type = :type, photo = :photo, rsvp = :rsvp WHERE id = :id");
        $stmt->bindValue(':title', $title, SQLITE3_TEXT);
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':date', $date, SQLITE3_TEXT);
        $stmt->bindValue(':type', $type, SQLITE3_TEXT);
        $stmt->bindValue(':photo', $photo_name, SQLITE3_TEXT);
        $stmt->bindValue(':rsvp', $rsvp_link, SQLITE3_TEXT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        if($stmt->execute()){
            header("Location: dashboard.php");
            exit;
        } else {
            $db_error = "Failed to update event in database.";
        }
    }
}

$stmt = $db->prepare("SELECT * FROM events WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$result = $stmt->execute();
$event = $result->fetchArray(SQLITE3_ASSOC);

if(!$event){
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Event - Bangor Jewish Society</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="uploads/logo.png">
<style>
.preview-img { max-height: 150px; object-fit: cover; margin-bottom: 10px; }
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
<li class="nav-item"><a class="nav-link" href="dashboard.php">Back to Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
</ul>
</div>
</nav>

<div class="container" style="max-width:700px;">
<h2 class="mb-4 text-center">Edit Event</h2>

<?php
if(isset($upload_error)) echo '<div class="alert alert-danger">'.$upload_error.'</div>';
if(isset($db_error)) echo '<div class="alert alert-danger">'.$db_error.'</div>';
?>

<form method="POST" enctype="multipart/form-data">
<div class="mb-3">
<label class="form-label">Event Title</label>
<input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Description</label>
<textarea class="form-control" name="description" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
</div>

<div class="mb-3">
<label class="form-label">Date</label>
<input type="date" class="form-control" name="date" value="<?php echo htmlspecialchars($event['date']); ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Event Type</label>
<select class="form-select" name="type">
<option value="upcoming" <?php if($event['type'] === 'upcoming') echo 'selected'; ?>>Upcoming</option>
<option value="past" <?php if($event['type'] === 'past') echo 'selected'; ?>>Past</option>
</select>
</div>

<div class="mb-3">
<label class="form-label">Current Photo</label>
<div>
<?php if(!empty($event['photo'])): ?>
<img src="uploads/<?php echo htmlspecialchars($event['photo']); ?>" class="preview-img img-thumbnail">
<?php else: ?>
<p class="text-muted">No photo uploaded.</p>
<?php endif; ?>
</div>
<label class="form-label">Replace Photo (optional)</label>
<input type="file" class="form-control" name="photo">
</div>

<div class="mb-3">
<label class="form-label">RSVP Link (optional)</label>
<input type="url" class="form-control" name="rsvp" value="<?php echo htmlspecialchars($event['rsvp']); ?>">
</div>

<button type="submit" class="btn btn-primary w-100">Save Changes</button>
</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>