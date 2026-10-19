<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<title>404 - The Dreidel Has Left the Building | Bangor University JSoc</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="icon" type="image/png" href="uploads/logo.png">

<style>

:root {
    --jsoc-navy: #17365D;
    --jsoc-dark: #102A43;
    --jsoc-blue: #315A82;
    --jsoc-background: #E9EDF1;
    --jsoc-card: #F7F8F9;
    --jsoc-muted: #5F7184;
}

body {
    margin: 0;
    background-color: var(--jsoc-background);
    color: var(--jsoc-dark);
    font-family: Arial, sans-serif;
}


/* ==============================
   NAVBAR
   ============================== */

.navbar {
    background-color: var(--jsoc-navy) !important;
}

.navbar-brand img {
    object-fit: contain;
}


/* ==============================
   404 CONTENT
   ============================== */

.error-container {
    min-height: calc(100vh - 72px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}

.error-card {
    max-width: 650px;
    width: 100%;
    text-align: center;

    background-color: var(--jsoc-card);

    border-radius: 20px;

    padding: 45px 30px;

    box-shadow: 0 10px 30px rgba(16, 42, 67, 0.10);
}


/* ==============================
   DREIDEL
   ============================== */

.dreidel {
    font-size: 85px;

    display: inline-block;

    margin-bottom: 10px;

    animation: spin 2.5s ease-in-out infinite;
}

@keyframes spin {

    0% {
        transform: rotate(0deg);
    }

    35% {
        transform: rotate(20deg);
    }

    70% {
        transform: rotate(-20deg);
    }

    100% {
        transform: rotate(0deg);
    }

}


/* ==============================
   ERROR NUMBER
   ============================== */

.error-code {
    font-size: 76px;
    font-weight: 800;

    color: var(--jsoc-navy);

    margin: 0;

    line-height: 1;
}


/* ==============================
   TEXT
   ============================== */

.error-title {
    font-size: 30px;
    font-weight: 700;

    margin-top: 20px;
    margin-bottom: 12px;
}

.error-text {
    color: var(--jsoc-muted);

    font-size: 17px;

    line-height: 1.6;

    max-width: 520px;

    margin: 0 auto 25px auto;
}


/* ==============================
   SILLY MESSAGE
   ============================== */

.excuse {
    background-color: #E9EDF1;

    border-radius: 12px;

    padding: 15px 18px;

    margin: 25px auto;

    max-width: 500px;

    color: var(--jsoc-dark);

    font-size: 15px;
}

.excuse strong {
    color: var(--jsoc-navy);
}


/* ==============================
   BUTTON
   ============================== */

.home-button {
    display: inline-block;

    background-color: var(--jsoc-navy);

    color: white;

    text-decoration: none;

    padding: 12px 24px;

    border-radius: 8px;

    font-weight: 600;

    transition: 0.2s;
}

.home-button:hover {
    background-color: var(--jsoc-dark);

    color: white;

    transform: translateY(-2px);
}


/* ==============================
   SMALL PRINT
   ============================== */

.small-print {
    margin-top: 25px;

    font-size: 13px;

    color: var(--jsoc-muted);
}


/* ==============================
   MOBILE
   ============================== */

@media (max-width: 576px) {

    .error-card {
        padding: 35px 20px;
    }

    .error-code {
        font-size: 62px;
    }

    .error-title {
        font-size: 25px;
    }

    .dreidel {
        font-size: 70px;
    }

}

</style>

</head>


<body>


<!-- ==============================
     NAVBAR
     ============================== -->

<nav class="navbar navbar-dark">

    <div class="container-fluid">

        <a class="navbar-brand d-flex align-items-center"
           href="index.php">

            <img src="uploads/logo.png"
                 alt="Bangor University JSoc logo"
                 width="40"
                 height="40"
                 class="me-2">

            Bangor University JSoc

        </a>

    </div>

</nav>


<!-- ==============================
     404 CONTENT
     ============================== -->

<div class="error-container">

    <div class="error-card">


        <!-- SILLY DREIDEL -->

        <div class="dreidel" aria-hidden="true">
            🕎
        </div>


        <!-- ERROR -->

        <h1 class="error-code">
            404
        </h1>


        <h2 class="error-title">
            Oops. The dreidel spun away.
        </h2>


        <p class="error-text">

            You've somehow managed to find a page that doesn't exist.

            <br>

            We're not entirely sure how you got here.

        </p>


        <!-- SILLY EXCUSE -->

        <div class="excuse">

            <strong>Official JSoc explanation:</strong><br>

            The page was here a minute ago, but someone spun the
            dreidel too hard and it disappeared.

        </div>


        <!-- BUTTON -->

        <a href="index.php"
           class="home-button">

            🏠 Take me back to JSoc

        </a>


        <div class="small-print">

            No dreidels were harmed in the making of this 404 page.

        </div>


    </div>

</div>


</body>
</html>