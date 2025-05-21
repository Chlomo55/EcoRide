<?php
// Connexion à MongoDB
require_once 'mongo_connect.php';

// Récupération des utilisateurs
$collection = $db->users; // Assurez-vous que la collection s'appelle 'users'
$users = $collection->find();

// Affichage des utilisateurs
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Gestion des Utilisateurs</h1>
        <nav>
            <a href="dashboard.php">Tableau de Bord</a>
            <a href="add_user.php">Ajouter un Utilisateur</a>
            <a href="settings.php">Paramètres</a>
        </nav>
    </header>

    <main>
        <h2>Liste des Utilisateurs</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string)$user['_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo htmlspecialchars((string)$user['_id']); ?>">Modifier</a>
                            <a href="delete_user.php?id=<?php echo htmlspecialchars((string)$user['_id']); ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>