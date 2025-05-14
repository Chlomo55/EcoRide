<?php
if (!isset($_GET['id'])) {
    echo "Covoiturage introuvable.";
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=ecoride", "root", "");

$id = $_GET['id'];

$query = "
    SELECT c.*, u.pseudo, u.photo, u.note, u.id as user_id, u.avis, v.marque, v.modele, v.energie, v.preferences
    FROM covoiturage c
    JOIN voiture v ON c.voiture_id = v.id
    JOIN user u ON v.user_id = u.id
    WHERE c.id = :id
";

$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $id]);
$covoit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$covoit) {
    echo "Covoiturage non trouvé.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail du Covoiturage</title>
</head>
<body>
    <h1>Détail du trajet</h1>
    <div style="border:1px solid #ccc; padding:15px; margin-bottom:20px;">
        <h2>Conducteur : <?= htmlspecialchars($covoit['pseudo']) ?> (Note : <?= $covoit['note'] ?>/5)</h2>
        <img src="data:image/jpeg;base64,<?= base64_encode($covoit['photo']) ?>" width="100"><br><br>
        <p><strong>Avis :</strong> <?= $covoit['avis'] ?></p>

        <h3>Itinéraire</h3>
        <p><strong>Départ :</strong> <?= $covoit['depart'] ?> - <?= date('d/m/Y H:i', strtotime($covoit['heure_depart'])) ?></p>
        <p><strong>Arrivée :</strong> <?= $covoit['arrivee'] ?> - <?= date('d/m/Y H:i', strtotime($covoit['heure_arrivee'])) ?></p>
        <?php
        $duration = strtotime($covoit['heure_arrivee']) - strtotime($covoit['heure_depart']);
        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        ?>
        <p><strong>Durée :</strong> <?= $hours ?>h<?= $minutes > 0 ? $minutes . 'min' : '' ?></p>
        <p><strong>Prix :</strong> <?= $covoit['prix'] ?> €</p>
        <p><strong>Places restantes :</strong> <?= $covoit['place'] ?></p>
        <p><strong>Voyage écologique :</strong> <?= strtolower($covoit['energie']) == 'electrique' ? 'Oui' : 'Non' ?></p>

        <h3>Véhicule</h3>
        <p><strong>Marque :</strong> <?= $covoit['marque'] ?></p>
        <p><strong>Modèle :</strong> <?= $covoit['modele'] ?></p>
        <p><strong>Énergie :</strong> <?= $covoit['energie'] ?></p>

        <h3>Préférences du conducteur</h3>
        <p><?= nl2br(htmlspecialchars($covoit['preferences'])) ?></p>

        <br>
        <a href="vue.php">← Retour à la recherche</a>
    </div>
</body>
</html>
