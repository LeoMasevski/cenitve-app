<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            header('Location: dashboard.php');
            exit;
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

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
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