<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = new SQLite3(__DIR__ . "/data/events.db");

// Automatically update past events if their date has passed
$today = date('Y-m-d');
$db->exec("UPDATE events SET type='past' WHERE date < '$today' AND type='upcoming'");

// Fetch only active upcoming events for today or later
$results = $db->query("SELECT * FROM events WHERE type='upcoming' AND date >= '$today' ORDER BY date ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<title>Bangor University JSoc (Jewish Society) - Upcoming Events</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Bangor University Jewish Society — Jewish events, activities and community for students in Bangor.">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="uploads/logo.png">

<style>

/* ==============================
   JSOC COLOUR PALETTE
   ============================== */

:root {
    --jsoc-navy: #17365D;
    --jsoc-dark: #102A43;
    --jsoc-blue: #315A82;
    --jsoc-background: #E9EDF1;
    --jsoc-card: #F7F8F9;
    --jsoc-muted: #5F7184;
}


/* ==============================
   GENERAL
   ============================== */

body {
    background-color: var(--jsoc-background);
    color: var(--jsoc-dark);
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Segoe UI Emoji", "Segoe UI Symbol", sans-serif;
}


/* ==============================
   NAVBAR
   ============================== */

.navbar {
    background-color: var(--jsoc-navy) !important;
}


/* ==============================
   EVENT CARDS
   ============================== */

.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: 0.2s;
    cursor: pointer;
    background-color: var(--jsoc-card);
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(16, 42, 67, 0.10);
}

.card-img-top {
    width: 100%;
    aspect-ratio: 16 / 9;
    object-fit: cover;
}

.card-title {
    color: var(--jsoc-dark);
}

.card-text {
    color: #34495E;
}


/* ==============================
   SIGN UP LINK
   ============================== */

.text-primary {
    color: var(--jsoc-navy) !important;
}


/* ==============================
   KEYBOARD ACCESSIBILITY
   ============================== */

a:focus-visible,
button:focus-visible {
    outline: 3px solid var(--jsoc-blue);
    outline-offset: 3px;
}


/* ==============================
   FRESHERS ANIMATED BANNER
   ============================== */

.freshers-bar {
    display: block;
    position: relative;
    overflow: hidden;

    background: var(--jsoc-navy);
    color: white;

    text-decoration: none;

    height: 44px;

    white-space: nowrap;

    border-top: 2px solid var(--jsoc-blue);
    border-bottom: 2px solid var(--jsoc-blue);
}

.freshers-bar:hover {
    background: var(--jsoc-blue);
    color: white;
}


/* Moving content */

.freshers-track {
    display: inline-flex;
    align-items: center;

    height: 100%;

    min-width: max-content;

    animation: freshers-scroll 18s linear infinite;
}


/* Individual moving sections */

.freshers-item {
    display: inline-flex;
    align-items: center;

    margin-right: 60px;

    font-size: 15px;
    font-weight: 600;
}


/* Spinning Star of David */

.freshers-star {
    display: inline-block;

    font-size: 20px;

    margin-right: 12px;

    animation: freshers-spin 4s linear infinite;
}


/* Scrolling animation */

@keyframes freshers-scroll {

    from {
        transform: translateX(0);
    }

    to {
        transform: translateX(-50%);
    }

}


/* Star rotation */

@keyframes freshers-spin {

    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }

}


/* Pause everything when hovered */

.freshers-bar:hover .freshers-track {
    animation-play-state: paused;
}

.freshers-bar:hover .freshers-star {
    animation-play-state: paused;
}


/* Respect reduced-motion preferences */

@media (prefers-reduced-motion: reduce) {

    .freshers-track,
    .freshers-star {
        animation: none;
    }

}


/* ==============================
   CONTACT FOOTER
   ============================== */

.site-footer {
    max-width: 460px;
    margin: 50px auto 30px auto;
    border-radius: 14px;
    box-shadow: 0 6px 20px rgba(16, 42, 67, 0.08);
    padding: 25px 20px;
    text-align: center;
    font-family: Arial, sans-serif;
    background-color: var(--jsoc-card);
}

.site-footer-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--jsoc-dark);
    margin-bottom: 6px;
}

.site-footer-subtitle {
    color: var(--jsoc-muted);
    font-size: 14px;
    margin-bottom: 20px;
}

.site-footer-contact {
    margin: 12px 0;
    font-size: 17px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.site-footer-contact img {
    width: 22px;
    height: 22px;
}

.site-footer-contact a {
    color: var(--jsoc-dark);
    text-decoration: underline;
}

.site-footer-contact a:hover {
    color: var(--jsoc-blue);
}


/* ==============================
   SUPPORTING ORGANISATIONS
   ============================== */

.supporting-section {
    text-align: center;
    margin: 45px auto 40px auto;
}

.jsoc-main-logo {
    width: 140px;
    height: 140px;
    object-fit: contain;
    margin-bottom: 20px;
}

.supporting-title {
    margin: 0 0 20px 0;
    font-size: 16px;
    color: var(--jsoc-muted);
    font-weight: 500;
}

.supported-logos {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 45px;
    margin: 30px auto 45px auto;
    padding: 0 20px;
    flex-wrap: wrap;
}

.standwithus-logo {
    width: 210px;
    max-width: 45vw;
    height: auto;
    object-fit: contain;
}

.ujs-logo {
    width: 120px;
    max-width: 28vw;
    height: 120px;
    object-fit: contain;
}

.chaplaincy-logo {
    width: 140px;
    max-width: 35vw;
    height: auto;
    object-fit: contain;
}


/* ==============================
   MOBILE
   ============================== */

@media (max-width: 576px) {

    .supported-logos {
        gap: 25px;
    }

    .standwithus-logo {
        width: 170px;
    }

    .ujs-logo {
        width: 90px;
        height: 90px;
    }

    .chaplaincy-logo {
        width: 120px;
    }

    .site-footer {
        margin-left: 15px;
        margin-right: 15px;
    }

    .freshers-item {
        font-size: 14px;
        margin-right: 45px;
    }

}

</style>
</head>


<body>


<!-- ==============================
     NAVBAR
     ============================== -->

<nav class="navbar navbar-expand-lg navbar-dark mb-4">

<div class="container-fluid">

<a class="navbar-brand d-flex align-items-center"
   href="index.php">

<img src="uploads/logo.png"
     alt="Bangor University JSoc logo"
     width="40"
     height="40"
     class="me-2">

Bangor University JSoc (Jewish Society)

</a>


<button class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation">

    <span class="navbar-toggler-icon"></span>

</button>


<div class="collapse navbar-collapse"
     id="navbarNav">

<ul class="navbar-nav ms-auto">

<li class="nav-item">

<a class="nav-link"
   href="past.php">

Past Events

</a>

</li>


<li class="nav-item">

<a class="nav-link"
   href="login.php">

Admin

</a>

</li>

</ul>

</div>

</div>

</nav>


<!-- ==============================
     FRESHERS ANIMATED BANNER
     ============================== -->

<a href="join.php"
   target="_blank"
   rel="noopener noreferrer"
   class="freshers-bar">

    <div class="freshers-track">

        <span class="freshers-item">

            <span class="freshers-star">&#10017;</span>

            FRESHER? JOIN BANGOR JSOC!

        </span>


        <span class="freshers-item">

            <span class="freshers-star">&#10017;</span>

            ALL students welcome

            <span class="freshers-star">&#10017;</span>

        </span>


        <span class="freshers-item">

            <span class="freshers-star">&#10017;</span>

            JOIN OUR FRESHERS WHATSAPP GROUP &rarr; PRESS ME!

        </span>


        <span class="freshers-item">

            <span class="freshers-star">&#10017;</span>

            Events &bull; Food &bull; Friends &bull; Jewish life

            <span class="freshers-star">&#10017;</span>

        </span>


        <!-- Duplicate content for seamless scrolling -->

        <span class="freshers-item">

            <span class="freshers-star">&#10017;</span>

            FRESHER? JOIN BANGOR JSOC!

        </span>


        <span class="freshers-item">

            <span class="freshers-star">&#10017;</span>

            ALL students welcome

            <span class="freshers-star">&#10017;</span>

        </span>


        <span class="freshers-item">

            <span class="freshers-star">&#10017;</span>

            JOIN OUR FRESHERS WHATSAPP GROUP &rarr; PRESS ME

        </span>


        <span class="freshers-item">

            <span class="freshers-star">&#10017;</span>

            Events &bull; Food &bull; Friends &bull; Jewish life

            <span class="freshers-star">&#10017;</span>

        </span>

    </div>

</a>


<!-- ==============================
     CONTENT
     ============================== -->

<div class="container">

<h1 class="text-center mb-4">

    Upcoming Events:

</h1>


<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">


<?php while($row = $results->fetchArray()): ?>


<div class="col">


<a href="event.php?id=<?php echo urlencode($row['id']); ?>"
   class="text-decoration-none text-dark">


<div class="card h-100">


<?php if(!empty($row['photo'])): ?>

<img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>"
     class="card-img-top"
     alt="<?php echo htmlspecialchars($row['title']); ?>">

<?php endif; ?>


<div class="card-body">


<h5 class="card-title">

<?php echo htmlspecialchars($row['title']); ?>

</h5>


<p class="card-text">

<?php echo nl2br(htmlspecialchars($row['description'])); ?>

</p>


<p class="card-text">

<small class="text-muted">

<?php

$date = new DateTime($row['date']);

echo $date->format('j F Y');

?>

</small>

</p>


<p class="text-primary">

<strong>View Event &rarr;</strong>

</p>


</div>

</div>


</a>

</div>


<?php endwhile; ?>


</div>

</div>


<!-- ==============================
     CONTACT FOOTER
     ============================== -->

<div class="site-footer">


<div class="site-footer-title">

Bangor University JSoc

</div>


<div class="site-footer-subtitle">

Jewish student community at Bangor University

</div>


<p class="site-footer-contact">


<img src="/uploads/email-icon.png"
     alt="Email">


<a href="mailto:contact@bangorjsoc.com">

contact@bangorjsoc.com

</a>


</p>


<p class="site-footer-contact">


<img src="/uploads/instagram-icon.png"
     alt="Instagram">


<a href="https://www.instagram.com/bangorjsoc"
   target="_blank"
   rel="noopener noreferrer">

@bangorjsoc

</a>


</p>


</div>


<!-- ==============================
     SUPPORTING ORGANISATIONS
     ============================== -->

<div class="supporting-section">


<img src="/uploads/logo.png"
     alt="Bangor University JSoc"
     class="jsoc-main-logo">


<p class="supporting-title">

    With support from:

</p>


<div class="supported-logos">


<img src="/uploads/standwithusuk.png"
     alt="StandWithUs UK"
     class="standwithus-logo">


<img src="/uploads/ujs.png"
     alt="UJS"
     class="ujs-logo">


<img src="/uploads/chaplaincy.png"
     alt="University Jewish Chaplaincy"
     class="chaplaincy-logo">


</div>

</div>


<!-- ==============================
     BOOTSTRAP
     ============================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>