<?php
require_once 'mongo_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    if ($name && $email) {
        $db->users->insertOne(['name' => $name, 'email' => $email]);
        header('Location: users.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Utilisateur</title>
</head>
<body>
    <h1>Ajouter un Utilisateur</h1>
    <form method="post">
        <label>Nom : <input type="text" name="name" required></label><br>
        <label>Email : <input type="email" name="email" required></label><br>
        <button type="submit">Ajouter</button>
    </form>
    <a href="users.php">Retour</a>
</body>
</html>