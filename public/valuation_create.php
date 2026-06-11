<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$errors = [];

$client_name = '';
$client_address = '';
$valuation_purpose = '';
$value_basis = '';
$value_premise = '';
$first_inspection_at = '';

$valuation_purposes = [
    'zavarovano posojanje',
    'sodni postopek',
    'stečajni postopek',
    'računovodsko poročanje',
    'davčni postopek',
    'poslovna odločitev naročnika'
];

$value_bases = [
    'tržna vrednost',
    'likvidacijska vrednost',
    'tržna najemnina',
    'pravična vrednost'
];

$value_premises = [
    'sedanja ali obstoječa uporaba',
    'najgospodarnejša uporaba',
    'redna likvidacija'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = trim($_POST['client_name'] ?? '');
    $client_address = trim($_POST['client_address'] ?? '');
    $valuation_purpose = trim($_POST['valuation_purpose'] ?? '');
    $value_basis = trim($_POST['value_basis'] ?? '');
    $value_premise = trim($_POST['value_premise'] ?? '');
    $first_inspection_at = trim($_POST['first_inspection_at'] ?? '');

    if ($client_name === '') {
        $errors[] = 'Naziv naročnika je obvezen.';
    }

    if ($client_address === '') {
        $errors[] = 'Naslov naročnika je obvezen.';
    }

    if (!in_array($valuation_purpose, $valuation_purposes, true)) {
        $errors[] = 'Izbran namen cenitve ni veljaven.';
    }

    if (!in_array($value_basis, $value_bases, true)) {
        $errors[] = 'Izbrana podlaga vrednosti ni veljavna.';
    }

    if (!in_array($value_premise, $value_premises, true)) {
        $errors[] = 'Izbrana premisa vrednosti ni veljavna.';
    }

    if ($first_inspection_at === '') {
        $errors[] = 'Datum in ura prvega ogleda sta obvezna.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'INSERT INTO valuations (
                user_id,
                client_name,
                client_address,
                valuation_purpose,
                value_basis,
                value_premise,
                first_inspection_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            current_user_id(),
            $client_name,
            $client_address,
            $valuation_purpose,
            $value_basis,
            $value_premise,
            $first_inspection_at
        ]);

        header('Location: dashboard.php?created=1');
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';

?>

<div class="card">
    <h2>Dodaj novo cenitev</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo escape_html($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="valuation_create.php">
        <div class="form-group">
            <label for="client_name">Naziv naročnika</label>
            <input
                type="text"
                id="client_name"
                name="client_name"
                value="<?php echo escape_html($client_name); ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="client_address">Naslov naročnika</label>
            <input
                type="text"
                id="client_address"
                name="client_address"
                value="<?php echo escape_html($client_address); ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="valuation_purpose">Namen cenitve</label>
            <select id="valuation_purpose" name="valuation_purpose" required>
                <option value="">-- Izberite namen cenitve --</option>

                <?php foreach ($valuation_purposes as $purpose): ?>
                    <option
                        value="<?php echo escape_html($purpose); ?>"
                        <?php echo $valuation_purpose === $purpose ? 'selected' : ''; ?>
                    >
                        <?php echo escape_html($purpose); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="value_basis">Podlaga vrednosti</label>
            <select id="value_basis" name="value_basis" required>
                <option value="">-- Izberite podlago vrednosti --</option>

                <?php foreach ($value_bases as $basis): ?>
                    <option
                        value="<?php echo escape_html($basis); ?>"
                        <?php echo $value_basis === $basis ? 'selected' : ''; ?>
                    >
                        <?php echo escape_html($basis); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="value_premise">Premisa vrednosti</label>
            <select id="value_premise" name="value_premise" required>
                <option value="">-- Izberite premiso vrednosti --</option>

                <?php foreach ($value_premises as $premise): ?>
                    <option
                        value="<?php echo escape_html($premise); ?>"
                        <?php echo $value_premise === $premise ? 'selected' : ''; ?>
                    >
                        <?php echo escape_html($premise); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="first_inspection_at">Prvi ogled</label>
            <input
                type="datetime-local"
                id="first_inspection_at"
                name="first_inspection_at"
                value="<?php echo escape_html($first_inspection_at); ?>"
                required
            >
        </div>

        <button type="submit">Shrani cenitev</button>
        <a class="btn" href="dashboard.php">Prekliči</a>
    </form>
</div>

<?php

require_once __DIR__ . '/../includes/footer.php';

?>