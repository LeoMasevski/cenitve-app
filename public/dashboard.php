<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$stmt = $pdo->prepare(
    'SELECT *
     FROM valuations
     WHERE user_id = ?
     ORDER BY first_inspection_at DESC'
);

$stmt->execute([current_user_id()]);
$valuations = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';

?>

<div class="card">
    <h2>Moje cenitve</h2>

    <?php if (isset($_GET['created'])): ?>
        <div class="success">
            Cenitev je bila uspešno dodana.
        </div>
    <?php endif; ?>

    <p>
        Pozdravljeni, <?php echo escape_html($_SESSION['user_name']); ?>.
    </p>

    <p>
        <a class="btn" href="valuation_create.php">Dodaj novo cenitev</a>
    </p>

    <?php if (empty($valuations)): ?>
        <p>Trenutno še nimate dodanih cenitev.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Naročnik</th>
                    <th>Naslov</th>
                    <th>Namen</th>
                    <th>Podlaga vrednosti</th>
                    <th>Premisa</th>
                    <th>Prvi ogled</th>
                    <th>Akcije</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($valuations as $valuation): ?>
                    <tr id="valuation-row-<?php echo (int) $valuation['id']; ?>">
                        <td><?php echo escape_html($valuation['client_name']); ?></td>
                        <td><?php echo escape_html($valuation['client_address']); ?></td>
                        <td><?php echo escape_html($valuation['valuation_purpose']); ?></td>
                        <td><?php echo escape_html($valuation['value_basis']); ?></td>
                        <td><?php echo escape_html($valuation['value_premise']); ?></td>
                        <td><?php echo escape_html($valuation['first_inspection_at']); ?></td>
                        <td>
                            <a class="btn" href="valuation_edit.php?id=<?php echo (int) $valuation['id']; ?>">
                                Uredi
                            </a>

                            <button
                                type="button"
                                class="btn btn-danger delete-valuation-btn"
                                data-id="<?php echo (int) $valuation['id']; ?>"
                            >
                                Izbriši
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php

require_once __DIR__ . '/../includes/footer.php';

?>