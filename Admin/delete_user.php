<?php
// Use Heroku environment variables if available, otherwise fallback to localhost
$host = getenv('MYSQL_HOST') ?: 'localhost';
$dbname = getenv('MYSQL_DATABASE') ?: 'ecoride'; // Remplace par le nom de ta base
$user = getenv('MYSQL_USER') ?: 'root';
$pass = getenv('MYSQL_PASSWORD') ?: '';

if (isset($_GET['id'])) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("DELETE FROM user WHERE id = ?");
        $stmt->execute([intval($_GET['id'])]);
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
header('Location: /Admin/users.php');
exit;