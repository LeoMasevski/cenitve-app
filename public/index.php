<?php
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Spletna aplikacija za upravljanje cenitev nepremičnin</h2>

    <p>
        Aplikacija omogoča registracijo uporabnika, prijavo ter osnovno upravljanje
        cenitev nepremičnin.
    </p>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>
            <a class="btn" href="dashboard.php">Pregled cenitev</a>
        </p>
    <?php else: ?>
        <p>
            <a class="btn" href="login.php">Prijava</a>
            <a class="btn" href="register.php">Registracija</a>
        </p>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>