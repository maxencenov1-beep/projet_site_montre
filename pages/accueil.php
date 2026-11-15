<?php
require_once __DIR__ . '/../config/database.php';

// Catégories pour le filtre
$categories = $pdo->query("SELECT id_categorie, nom FROM categories ORDER BY nom")->fetchAll(PDO::FETCH_OBJ);

// Catégorie choisie dans l'URL (?categorie=3), sinon toutes les montres
$id_categorie = filter_input(INPUT_GET, 'categorie', FILTER_VALIDATE_INT);

$sql = "
    SELECT montres.id_montre, montres.nom, montres.image_url, montres.prix, marques.nom AS marque
    FROM montres
    INNER JOIN marques ON marques.id_marque = montres.id_marque
";
if ($id_categorie) {
    $sql .= " WHERE montres.id_categorie = :categorie";
}
$sql .= " ORDER BY marques.nom, montres.nom";

$select = $pdo->prepare($sql);
$select->execute($id_categorie ? ['categorie' => $id_categorie] : []);
$montres = $select->fetchAll(PDO::FETCH_OBJ);

$titre = 'Accueil';
require_once __DIR__ . '/../includes/header.php';
?>

<section id="collection" class="collection">
    <div class="container">
        <div class="section-titre">
            <h2>La collection</h2>
            <p class="muted"><?= count($montres) ?> modèle<?= count($montres) > 1 ? 's' : '' ?></p>
        </div>

        <nav class="filtres">
            <a href="accueil.php#collection" class="<?= !$id_categorie ? 'actif' : '' ?>">Toutes</a>
            <?php foreach ($categories as $categorie): ?>
                <a href="accueil.php?categorie=<?= $categorie->id_categorie ?>#collection"
                   class="<?= $id_categorie === $categorie->id_categorie ? 'actif' : '' ?>">
                    <?= htmlspecialchars($categorie->nom) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <?php if (empty($montres)): ?>
            <p class="muted">Aucune montre dans cette catégorie.</p>
        <?php else: ?>
            <div class="grille">
                <?php foreach ($montres as $montre): ?>
                    <a class="carte" href="detail.php?id_montre=<?= $montre->id_montre ?>">
                        <div class="carte-img">
                            <?php if ($montre->image_url): ?>
                                <img src="<?= htmlspecialchars($montre->image_url) ?>"
                                     alt="<?= htmlspecialchars("$montre->marque $montre->nom") ?>" loading="lazy">
                            <?php endif; ?>
                        </div>
                        <div class="carte-texte">
                            <p class="marque"><?= htmlspecialchars($montre->marque) ?></p>
                            <h3><?= htmlspecialchars($montre->nom) ?></h3>
                            <?php if ($montre->prix !== null): ?>
                                <p class="prix"><?= number_format($montre->prix, 0, ',', ' ') ?> €</p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
