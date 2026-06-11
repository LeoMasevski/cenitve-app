<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/valuation_assistant.php';
require_once __DIR__ . '/../includes/csrf.php';

require_login();

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

$search = trim($_GET['search'] ?? '');
$purpose_filter = trim($_GET['purpose'] ?? '');
$basis_filter = trim($_GET['basis'] ?? '');
$premise_filter = trim($_GET['premise'] ?? '');

$sort = $_GET['sort'] ?? 'first_inspection_at';
$direction = $_GET['direction'] ?? 'desc';

$allowed_sort_columns = [
    'client_name' => 'client_name',
    'valuation_purpose' => 'valuation_purpose',
    'value_basis' => 'value_basis',
    'value_premise' => 'value_premise',
    'first_inspection_at' => 'first_inspection_at',
    'created_at' => 'created_at'
];

$allowed_directions = ['asc', 'desc'];

if (!array_key_exists($sort, $allowed_sort_columns)) {
    $sort = 'first_inspection_at';
}

if (!in_array($direction, $allowed_directions, true)) {
    $direction = 'desc';
}

$where_conditions = ['user_id = ?'];
$params = [current_user_id()];

if ($search !== '') {
    $where_conditions[] = '(client_name LIKE ? OR client_address LIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}

if ($purpose_filter !== '') {
    $where_conditions[] = 'valuation_purpose = ?';
    $params[] = $purpose_filter;
}

if ($basis_filter !== '') {
    $where_conditions[] = 'value_basis = ?';
    $params[] = $basis_filter;
}

if ($premise_filter !== '') {
    $where_conditions[] = 'value_premise = ?';
    $params[] = $premise_filter;
}

$where_sql = implode(' AND ', $where_conditions);
$order_column = $allowed_sort_columns[$sort];
$order_direction = strtoupper($direction);

$sql = "SELECT *
        FROM valuations
        WHERE $where_sql
        ORDER BY $order_column $order_direction";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$valuations = $stmt->fetchAll();

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

require_once __DIR__ . '/../includes/header.php';

?>

<div class="card">
    <h2>Moje cenitve</h2>

    <script>
        window.csrfToken = "<?php echo escape_html(csrf_token()); ?>";
    </script>

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

    <form method="GET" action="dashboard.php" class="filter-form">
        <div class="filter-grid">
            <div class="form-group">
                <label for="search">Iskanje</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    placeholder="Išči po naročniku ali naslovu"
                    value="<?php echo escape_html($search); ?>"
                >
            </div>

            <div class="form-group">
                <label for="purpose">Namen cenitve</label>
                <select id="purpose" name="purpose">
                    <option value="">Vsi nameni</option>

                    <?php foreach ($valuation_purposes as $purpose): ?>
                        <option
                            value="<?php echo escape_html($purpose); ?>"
                            <?php echo $purpose_filter === $purpose ? 'selected' : ''; ?>
                        >
                            <?php echo escape_html($purpose); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="basis">Podlaga vrednosti</label>
                <select id="basis" name="basis">
                    <option value="">Vse podlage</option>

                    <?php foreach ($value_bases as $basis): ?>
                        <option
                            value="<?php echo escape_html($basis); ?>"
                            <?php echo $basis_filter === $basis ? 'selected' : ''; ?>
                        >
                            <?php echo escape_html($basis); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="premise">Premisa vrednosti</label>
                <select id="premise" name="premise">
                    <option value="">Vse premise</option>

                    <?php foreach ($value_premises as $premise): ?>
                        <option
                            value="<?php echo escape_html($premise); ?>"
                            <?php echo $premise_filter === $premise ? 'selected' : ''; ?>
                        >
                            <?php echo escape_html($premise); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="sort">Razvrsti po</label>
                <select id="sort" name="sort">
                    <option value="first_inspection_at" <?php echo $sort === 'first_inspection_at' ? 'selected' : ''; ?>>
                        Prvi ogled
                    </option>
                    <option value="created_at" <?php echo $sort === 'created_at' ? 'selected' : ''; ?>>
                        Datum ustvarjanja
                    </option>
                    <option value="client_name" <?php echo $sort === 'client_name' ? 'selected' : ''; ?>>
                        Naročnik
                    </option>
                    <option value="valuation_purpose" <?php echo $sort === 'valuation_purpose' ? 'selected' : ''; ?>>
                        Namen cenitve
                    </option>
                    <option value="value_basis" <?php echo $sort === 'value_basis' ? 'selected' : ''; ?>>
                        Podlaga vrednosti
                    </option>
                    <option value="value_premise" <?php echo $sort === 'value_premise' ? 'selected' : ''; ?>>
                        Premisa vrednosti
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="direction">Smer</label>
                <select id="direction" name="direction">
                    <option value="desc" <?php echo $direction === 'desc' ? 'selected' : ''; ?>>
                        Padajoče
                    </option>
                    <option value="asc" <?php echo $direction === 'asc' ? 'selected' : ''; ?>>
                        Naraščajoče
                    </option>
                </select>
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit">Uporabi filtre</button>
            <a class="btn btn-secondary" href="dashboard.php">Ponastavi</a>
        </div>
    </form>

    <?php if (empty($valuations)): ?>
        <p>Ni najdenih cenitev.</p>
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
                    <th>Pametni pregled</th>
                    <th>Akcije</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($valuations as $valuation): ?>
                    <?php $analysis = analyze_valuation($valuation); ?>

                    <tr id="valuation-row-<?php echo (int) $valuation['id']; ?>">
                        <td data-label="Naročnik"><?php echo escape_html($valuation['client_name']); ?></td>
                        <td data-label="Naslov"><?php echo escape_html($valuation['client_address']); ?></td>
                        <td data-label="Namen"><?php echo escape_html($valuation['valuation_purpose']); ?></td>
                        <td data-label="Podlaga vrednosti"><?php echo escape_html($valuation['value_basis']); ?></td>
                        <td data-label="Premisa"><?php echo escape_html($valuation['value_premise']); ?></td>
                        <td data-label="Prvi ogled"><?php echo escape_html($valuation['first_inspection_at']); ?></td>
                        <td data-label="Pametni pregled">
                            <strong><?php echo escape_html($analysis['status']); ?></strong>
                            <br>
                            <span class="completeness">
                                Popolnost: <?php echo (int) $analysis['completeness']; ?>%
                            </span>

                            <?php if (!empty($analysis['warnings'])): ?>
                                <ul class="assistant-list warning-list">
                                    <?php foreach ($analysis['warnings'] as $warning): ?>
                                        <li><?php echo escape_html($warning); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (!empty($analysis['recommendations'])): ?>
                                <ul class="assistant-list recommendation-list">
                                    <?php foreach ($analysis['recommendations'] as $recommendation): ?>
                                        <li><?php echo escape_html($recommendation); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                        <td data-label="Akcije" class="actions-cell">
                            <div class="action-buttons">
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
                            </div>
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