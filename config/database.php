<?php
require_once __DIR__ . '/../vendor/autoload.php';

// En local : variables lues dans le fichier .env (à la racine du projet).
// Sur Vercel : pas de .env, les variables viennent des réglages du projet.
Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

function env($nom, $defaut = null) {
    $valeur = $_ENV[$nom] ?? getenv($nom);
    return ($valeur === false || $valeur === null) ? $defaut : $valeur;
}

$host = env('DB_HOST');        // ex : gateway01.eu-central-1.prod.aws.tidbcloud.com
$db   = env('DB_NAME');        // catalogue_montres
$user = env('DB_USERNAME');    // ex : xxxxxxxx.root
$pass = env('DB_PASSWORD');
$port = env('DB_PORT', '4000'); // 4000 pour TiDB

// Diagnostic : si une variable n'est pas définie (ex. oubliée sur Vercel), on le dit clairement
$manquantes = [];
foreach (['DB_HOST', 'DB_NAME', 'DB_USERNAME', 'DB_PASSWORD'] as $nom) {
    if (env($nom) === null || env($nom) === '') {
        $manquantes[] = $nom;
    }
}
if ($manquantes) {
    http_response_code(500);
    die("<p style='font-family:sans-serif;padding:2rem'>Variables d'environnement manquantes : "
        . implode(', ', $manquantes)
        . "<br>Ajoute-les dans Vercel (Settings → Environment Variables) puis fais Redeploy.</p>");
}

$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// TiDB Cloud impose une connexion chiffrée (SSL)
if (env('DB_SSL', 'true') === 'true') {
    $options[PDO::MYSQL_ATTR_SSL_CA] = __DIR__ . '/cacert.pem';
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    die("<p style='font-family:sans-serif;padding:2rem'>Connexion à la base de données impossible : "
        . htmlspecialchars($e->getMessage()) . "</p>");
}
