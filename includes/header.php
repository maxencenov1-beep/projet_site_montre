<?php
// $titre peut être défini par la page avant d'inclure ce fichier
$titre = isset($titre) ? "$titre — Heure Juste" : "Heure Juste — Montres d'exception";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="accueil.php" class="logo">Heure Juste</a>
            <nav>
                <a href="accueil.php#collection">Collection</a>
            </nav>
        </div>
    </header>
    <main>
