<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../includes/csrf.php';

redirect_if_logged_in();

require_once __DIR__ . '/../includes/header.php';

$errors = [];

$first_name = '';
$last_name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Varnostni žeton ni veljaven. Prosimo, poskusite ponovno.';
    }

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($first_name === '') {
        $errors[] = 'Ime je obvezno.';
    }

    if ($last_name === '') {
        $errors[] = 'Priimek je obvezen.';
    }

    if ($email === '') {
        $errors[] = 'E-poštni naslov je obvezen.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'E-poštni naslov ni veljaven.';
    }

    if ($password === '') {
        $errors[] = 'Geslo je obvezno.';
    } else {
        $password_errors = validate_password_strength($password);
        $errors = array_merge($errors, $password_errors);
    }

    if ($password !== $password_confirm) {
        $errors[] = 'Gesli se ne ujemata.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $existing_user = $stmt->fetch();

        if ($existing_user) {
            $errors[] = 'Uporabnik s tem e-poštnim naslovom že obstaja.';
        }
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO users (first_name, last_name, email, password_hash)
             VALUES (?, ?, ?, ?)'
        );

        $stmt->execute([
            $first_name,
            $last_name,
            $email,
            $password_hash
        ]);

        header('Location: login.php?registered=1');
        exit;
    }
}

?>

<div class="card">
    <h2>Registracija</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
        <div class="form-group">
            <label for="first_name">Ime</label>
            <input
                type="text"
                id="first_name"
                name="first_name"
                value="<?php echo htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8'); ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="last_name">Priimek</label>
            <input
                type="text"
                id="last_name"
                name="last_name"
                value="<?php echo htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8'); ?>"
                required
            >
        </div>

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
            <small class="field-hint">
                Geslo naj vsebuje vsaj 15 znakov, veliko črko, malo črko, številko in poseben znak.
            </small>
        </div>

        <div class="form-group">
            <label for="password_confirm">Ponovi geslo</label>
            <input
                type="password"
                id="password_confirm"
                name="password_confirm"
                required
            >
        </div>

        <button type="submit">Registriraj se</button>
    </form>

    <p>
        Že imate račun?
        <a href="login.php">Prijavite se tukaj</a>.
    </p>
</div>

<?php

require_once __DIR__ . '/../includes/footer.php';

?>