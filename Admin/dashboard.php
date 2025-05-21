<?php
// dashboard.php

require_once 'mongo_connect.php';

// Fonction pour récupérer les statistiques
function getStatistics() {
    global $mongoClient;
    $db = $mongoClient->selectDatabase('your_database_name'); // Remplacez par le nom de votre base de données

    // Exemple de récupération des crédits gagnés
    $creditsCollection = $db->credits;
    $totalCredits = $creditsCollection->countDocuments();

    // Exemple de récupération des covoiturages par jour
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
    </main>
</body>
</html>