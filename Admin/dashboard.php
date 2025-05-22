<?php
// dashboard.php

require_once 'mongo_connect.php';

// Fonction pour récupérer les statistiques
function getStatistics() {
    global $db;

    // Total des crédits gagnés (somme des amount)
    $creditsCollection = $db->credits;
    $totalCreditsCursor = $creditsCollection->aggregate([
        ['$group' => ['_id' => null, 'total' => ['$sum' => '$amount']]]
    ]);
    $totalCreditsResult = current(iterator_to_array($totalCreditsCursor));
    $totalCredits = $totalCreditsResult['total'] ?? 0;

    // Covoiturages par jour
    $ridesCollection = $db->rides;
    $dailyRides = $ridesCollection->aggregate([
        ['$group' => ['_id' => '$date', 'count' => ['$sum' => 1]]],
        ['$sort' => ['_id' => 1]]
    ]);

    // Crédits gagnés par jour
    $dailyCredits = $creditsCollection->aggregate([
        ['$group' => ['_id' => '$date', 'total' => ['$sum' => '$amount']]],
        ['$sort' => ['_id' => 1]]
    ]);

    return [
        'totalCredits' => $totalCredits,
        'dailyRides' => iterator_to_array($dailyRides),
        'dailyCredits' => iterator_to_array($dailyCredits)
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Tableau de Bord Administrateur</h1>
    </header>
    <main>
        <section>
            <h2>Statistiques Clés</h2>
            <p>Nombre total de crédits gagnés : <?php echo $statistics['totalCredits']; ?></p>
        </section>
        <section>
            <h3>Covoiturages par jour</h3>
            <canvas id="ridesChart" width="400" height="200"></canvas>
        </section>
        <section>
            <h3>Crédits gagnés par jour</h3>
            <canvas id="creditsChart" width="400" height="200"></canvas>
        </section>
    </main>
    <script>
        // Préparation des données PHP -> JS
        const dailyRides = <?php echo json_encode($statistics['dailyRides']); ?>;
        const dailyCredits = <?php echo json_encode($statistics['dailyCredits']); ?>;

        // Extraction des labels et valeurs pour les graphiques
        const ridesLabels = dailyRides.map(item => item._id);
        const ridesData = dailyRides.map(item => item.count);

        const creditsLabels = dailyCredits.map(item => item._id);
        const creditsData = dailyCredits.map(item => item.total);

        // Graphique covoiturages par jour
        new Chart(document.getElementById('ridesChart'), {
            type: 'bar',
            data: {
                labels: ridesLabels,
                datasets: [{
                    label: 'Nombre de covoiturages',
                    data: ridesData,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Graphique crédits par jour
        new Chart(document.getElementById('creditsChart'), {
            type: 'line',
            data: {
                labels: creditsLabels,
                datasets: [{
                    label: 'Crédits gagnés',
                    data: creditsData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>