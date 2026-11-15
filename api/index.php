<?php
// Point d'entrée pour Vercel : toutes les URL du site arrivent ici
// et on inclut la page PHP correspondante.

$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$pages = [
    '/pages/accueil.php' => 'accueil.php',
    '/pages/detail.php'  => 'detail.php',
];

if ($chemin === '/' || $chemin === '/index.php') {
    header('Location: /pages/accueil.php');
    exit();
}

if (!isset($pages[$chemin])) {
    http_response_code(404);
    echo "Page introuvable";
    exit();
}

require __DIR__ . '/../pages/' . $pages[$chemin];
