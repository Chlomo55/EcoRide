<?php
// users_list.php

require_once '../mongo_connect.php';
require_once '../controllers/UserController.php';

$userController = new UserController();
$users = $userController->getAllUsers();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suspend'])) {
    $userId = $_POST['user_id'];
    $userController->suspendUser($userId);
    header("Location: users_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Liste des Utilisateurs</h1>
    </header>
    <main>
        <section>
            <h2>Utilisateurs</h2>
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
                            <td><?php echo htmlspecialchars($user->id); ?></td>
                            <td><?php echo htmlspecialchars($user->name); ?></td>
                            <td><?php echo htmlspecialchars($user->email); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user->id); ?>">
                                    <button type="submit" name="suspend">Suspendre</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>