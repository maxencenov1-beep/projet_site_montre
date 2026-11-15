<?php
require_once __DIR__ . '/../config/database.php';

$id_montre = filter_input(INPUT_GET, 'id_montre', FILTER_VALIDATE_INT);

$montre = false;
if ($id_montre) {
    // Requête préparée : l'id vient de l'URL
    $stmt = $pdo->prepare("
        SELECT montres.*,
               marques.nom AS marque, marques.pays_origine, marques.annee_creation,
               categories.nom AS categorie,
               mouvements.type AS mouvement
        FROM montres
        INNER JOIN marques ON marques.id_marque = montres.id_marque
        LEFT JOIN categories ON categories.id_categorie = montres.id_categorie
        LEFT JOIN mouvements ON mouvements.id_mouvement = montres.id_mouvement
        WHERE montres.id_montre = :id
    ");
    $stmt->execute(['id' => $id_montre]);
    $montre = $stmt->fetch(PDO::FETCH_OBJ);
}

if (!$montre) {
    http_response_code(404);
    $titre = 'Montre introuvable';
    require_once __DIR__ . '/../includes/header.php';
    echo "<section class='container vide'><h1>Montre introuvable</h1>
          <p><a href='accueil.php' class='bouton'>Retour à la collection</a></p></section>";
    require_once __DIR__ . '/../includes/footer.php';
    exit();
}

// Autres montres de la même marque
$stmt = $pdo->prepare("
    SELECT id_montre, nom, image_url, prix
    FROM montres
    WHERE id_marque = :marque AND id_montre <> :id
    ORDER BY nom
    LIMIT 4
");
$stmt->execute(['marque' => $montre->id_marque, 'id' => $montre->id_montre]);
$similaires = $stmt->fetchAll(PDO::FETCH_OBJ);

// Affiche un nombre sans décimales inutiles : 42.50 -> 42,5 ; 41.00 -> 41
function nombre($valeur) {
    return str_replace('.', ',', rtrim(rtrim((string)$valeur, '0'), '.'));
}

// Lignes du tableau de caractéristiques (les valeurs vides sont ignorées)
$caracteristiques = [
    'Référence'  => $montre->reference,
    'Catégorie'  => $montre->categorie,
    'Mouvement'  => $montre->mouvement,
    'Diamètre'   => $montre->diametre_mm !== null ? nombre($montre->diametre_mm) . ' mm' : null,
    'Étanchéité' => $montre->etancheite_m !== null ? $montre->etancheite_m . ' m' : null,
    'Boîtier'    => $montre->matiere_boitier,
    'Bracelet'   => $montre->matiere_bracelet,
    'Cadran'     => $montre->couleur_cadran,
    'Maison'     => trim($montre->marque
                    . ($montre->pays_origine ? " — $montre->pays_origine" : '')
                    . ($montre->annee_creation ? ", depuis $montre->annee_creation" : '')),
];

$titre = "$montre->marque $montre->nom";
require_once __DIR__ . '/../includes/header.php';
?>

<section class="detail container">
    <a href="accueil.php#collection" class="retour">← Retour à la collection</a>

    <div class="detail-grille">
        <div class="detail-img">
            <?php if ($montre->image_url): ?>
                <img src="<?= htmlspecialchars($montre->image_url) ?>" alt="<?= htmlspecialchars($titre) ?>">
            <?php endif; ?>
        </div>

        <div class="detail-texte">
            <p class="marque"><?= htmlspecialchars($montre->marque) ?></p>
            <h1><?= htmlspecialchars($montre->nom) ?></h1>
            <?php if ($montre->prix !== null): ?>
                <p class="prix grand"><?= number_format($montre->prix, 2, ',', ' ') ?> €</p>
            <?php endif; ?>
            <?php if ($montre->description): ?>
                <p class="description"><?= nl2br(htmlspecialchars($montre->description)) ?></p>
            <?php endif; ?>

            <h2>Caractéristiques</h2>
            <dl class="caracteristiques">
                <?php foreach ($caracteristiques as $libelle => $valeur): ?>
                    <?php if ($valeur !== null && $valeur !== ''): ?>
                        <dt><?= $libelle ?></dt>
                        <dd><?= htmlspecialchars($valeur) ?></dd>
                    <?php endif; ?>
                <?php endforeach; ?>
            </dl>
        </div>
    </div>
</section>

<?php if (!empty($similaires)): ?>
<section class="collection">
    <div class="container">
        <div class="section-titre">
            <h2>Du même horloger</h2>
        </div>
        <div class="grille">
            <?php foreach ($similaires as $autre): ?>
                <a class="carte" href="detail.php?id_montre=<?= $autre->id_montre ?>">
                    <div class="carte-img">
                        <?php if ($autre->image_url): ?>
                            <img src="<?= htmlspecialchars($autre->image_url) ?>" alt="<?= htmlspecialchars($autre->nom) ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    <div class="carte-texte">
                        <h3><?= htmlspecialchars($autre->nom) ?></h3>
                        <?php if ($autre->prix !== null): ?>
                            <p class="prix"><?= number_format($autre->prix, 0, ',', ' ') ?> €</p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
