<?php
// charts.php

require_once '../mongo_connect.php';

function getChartData() {
    global $mongoClient;
    $db = $mongoClient->selectDatabase('your_database_name'); // Remplacez par le nom de votre base de données

    // Récupération des crédits gagnés par mois
    $creditsCollection = $db->credits;
    $creditsData = $creditsCollection->aggregate([
        ['$group' => ['_id' => ['$substr' => ['$date', 0, 7]], 'total' => ['$sum' => '$amount']]],
        ['$sort' => ['_id' => 1]]
    ]);

    // Récupération des covoiturages par jour
    $ridesCollection = $db->rides;
    $ridesData = $ridesCollection->aggregate([
        ['$group' => ['_id' => '$date', 'count' => ['$sum' => 1]]],
        ['$sort' => ['_id' => 1]]
    ]);

    return [
        'credits' => iterator_to_array($creditsData),
        'rides' => iterator_to_array($ridesData)
    ];
}

$chartData = getChartData();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphiques des Statistiques</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Graphiques des Statistiques</h1>
    </header>
    <main>
        <section>
            <h2>Crédits Gagnés par Mois</h2>
            <canvas id="creditsChart"></canvas>
            <script>
                const creditsData = <?php echo json_encode($chartData['credits']); ?>;
                const labels = creditsData.map(data => data._id);
                const data = creditsData.map(data => data.total);

                const ctx = document.getElementById('creditsChart').getContext('2d');
                const creditsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Crédits Gagnés',
                            data: data,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </section>
        <section>
            <h2>Covoiturages par Jour</h2>
            <canvas id="ridesChart"></canvas>
            <script>
                const ridesData = <?php echo json_encode($chartData['rides']); ?>;
                const rideLabels = ridesData.map(data => data._id);
                const rideCounts = ridesData.map(data => data.count);

                const ctxRides = document.getElementById('ridesChart').getContext('2d');
                const ridesChart = new Chart(ctxRides, {
                    type: 'line',
                    data: {
                        labels: rideLabels,
                        datasets: [{
                            label: 'Covoiturages',
                            data: rideCounts,
                            fill: false,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </section>
    </main>
</body>
</html>