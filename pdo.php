<?php 
// Use Heroku environment variables if available, otherwise fallback to localhost
$host = getenv('MYSQL_HOST') ?: 'localhost';
$dbname = getenv('MYSQL_DATABASE') ?: 'ecoride';
$user = getenv('MYSQL_USER') ?: 'root';
$password = getenv('MYSQL_PASSWORD') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold; font-family: Arial, sans-serif;'>
            Erreur : Impossible de se connecter à la base de données. " . $e->getMessage() . "
          </div>";
    exit;
}
?>