<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

redirect_if_logged_in();

require_once __DIR__ . '/../includes/header.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    if (!isset($_SESSION['login_blocked_until'])) {
        $_SESSION['login_blocked_until'] = 0;
    }

    if ($_SESSION['login_blocked_until'] > time()) {
        $errors[] = 'Preveč neuspešnih poskusov prijave. Poskusite ponovno čez nekaj časa.';
    }

    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Varnostni žeton ni veljaven. Prosimo, poskusite ponovno.';
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '') {
        $errors[] = 'E-poštni naslov je obvezen.';
    }

    if ($password === '') {
        $errors[] = 'Geslo je obvezno.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'];
            $_SESSION['last_activity'] = time();

            $_SESSION['login_attempts'] = 0;
            $_SESSION['login_blocked_until'] = 0;

            header('Location: dashboard.php');
            exit;
        }

        $_SESSION['login_attempts']++;

        if ($_SESSION['login_attempts'] >= 5) {
            $_SESSION['login_blocked_until'] = time() + 60;
            $_SESSION['login_attempts'] = 0;

            $errors[] = 'Preveč neuspešnih poskusov prijave. Poskusite ponovno čez nekaj časa.';
        } else {
            $errors[] = 'Napačen e-poštni naslov ali geslo.';
        }
    }
}

?>

<div class="card">
    <h2>Prijava</h2>

    <?php if (isset($_GET['registered'])): ?>
        <div class="success">
            Registracija je bila uspešna. Sedaj se lahko prijavite.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['timeout'])): ?>
        <div class="error">
            Seja je potekla zaradi neaktivnosti. Prosimo, prijavite se ponovno.
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
        <div class="form-group">
            <label for="email">E-pošta</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="password">Geslo</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >
        </div>

        <button type="submit">Prijava</button>
    </form>

    <p>
        Še nimate računa?
        <a href="register.php">Registrirajte se tukaj</a>.
    </p>
</div>

<?php

require_once __DIR__ . '/../includes/footer.php';

?>