<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Cenitve nepremičnin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="container header-content">
        <h1>Cenitve nepremičnin</h1>

        <nav>
            <a href="index.php">Domov</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Cenitve</a>
                <a href="valuation_create.php">Dodaj cenitev</a>
                <a href="logout.php">Odjava</a>
            <?php else: ?>
                <a href="login.php">Prijava</a>
                <a href="register.php">Registracija</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container">