<?php
// dashboard_view.php

require_once '../mongo_connect.php';
require_once '../controllers/UserController.php';
require_once '../controllers/EmployeeController.php';

// Récupérer les statistiques
function getStatistics() {
    global $mongoClient;
    $db = $mongoClient->selectDatabase('your_database_name');

    $creditsCollection = $db->credits;
    $totalCredits = $creditsCollection->countDocuments();

    $ridesCollection = $db->rides;
    $dailyRides = $ridesCollection->aggregate([
        ['$group' => ['_id' => '$date', 'count' => ['$sum' => 1]]],
        ['$sort' => ['_id' => 1]]
    ]);

    return [
        'totalCredits' => $totalCredits,
        'dailyRides' => iterator_to_array($dailyRides)
    ];
}

$statistics = getStatistics();
$userController = new UserController();
$employeeController = new EmployeeController();
$users = $userController->getAllUsers();
$employees = $employeeController->getAllEmployees();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Tableau de Bord Administrateur</h1>
    </header>
    <main>
        <section>
            <h2>Statistiques Clés</h2>
            <p>Nombre total de crédits gagnés : <?php echo $statistics['totalCredits']; ?></p>
            <h3>Covoiturages par jour</h3>
            <ul>
                <?php foreach ($statistics['dailyRides'] as $ride): ?>
                    <li><?php echo htmlspecialchars($ride['_id']); ?> : <?php echo $ride['count']; ?> covoiturages</li>
                <?php endforeach; ?>
            </ul>
        </section>
        <section>
            <h2>Gestion des Comptes</h2>
            <h3>Utilisateurs</h3>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li><?php echo htmlspecialchars($user['name']); ?> - <a href="suspend_user.php?id=<?php echo $user['id']; ?>">Suspendre</a></li>
                <?php endforeach; ?>
            </ul>
            <h3>Employés</h3>
            <ul>
                <?php foreach ($employees as $employee): ?>
                    <li><?php echo htmlspecialchars($employee['name']); ?> - <a href="suspend_employee.php?id=<?php echo $employee['id']; ?>">Suspendre</a></li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>
</html>