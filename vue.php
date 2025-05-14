<?php
// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=ecoride", "root", "");

// Récupération des données du formulaire
$depart = $_GET['depart'] ?? '';
$arrivee = $_GET['arrivee'] ?? '';
$date = $_GET['date'] ?? '';
$ecolo = $_GET['ecolo'] ?? '';
$prix_max = $_GET['prix_max'] ?? '';
$duree_max_heures = $_GET['duree_max_heures'] ?? '';
$duree_max_minutes = $_GET['duree_max_minutes'] ?? '';
$note_min = $_GET['note_min'] ?? '';

// Calcul de la durée maximale en minutes
$duree_max = 0;
if ($duree_max_heures !== '' || $duree_max_minutes !== '') {
    $duree_max = ($duree_max_heures * 60) + $duree_max_minutes;
}

// Construction de la requête
$query = "
    SELECT c.*, u.pseudo, u.photo, u.note, v.energie, v.marque, v.modele, v.preferences, u.id as user_id
    FROM covoiturage c
    JOIN voiture v ON c.voiture_id = v.id
    JOIN user u ON v.user_id = u.id
    WHERE c.place > 0
";

$params = [];

if ($depart) {
    $query .= " AND c.depart = :depart";
    $params[':depart'] = $depart;
}
if ($arrivee) {
    $query .= " AND c.arrivee = :arrivee";
    $params[':arrivee'] = $arrivee;
}
if ($date) {
    $query .= " AND DATE(c.heure_depart) = :date";
    $params[':date'] = $date;
}
if ($ecolo === '1') {
    $query .= " AND v.energie = 'electrique'";
}
if ($prix_max !== '') {
    $query .= " AND c.prix <= :prix_max";
    $params[':prix_max'] = $prix_max;
}
if ($duree_max > 0) {
    $query .= " AND TIMESTAMPDIFF(MINUTE, c.heure_depart, c.heure_arrivee) <= :duree_max";
    $params[':duree_max'] = $duree_max;
}
if ($note_min !== '') {
    $query .= " AND u.note >= :note_min";
    $params[':note_min'] = $note_min;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche de Covoiturage</title>
</head>
<body>
    <h1>Rechercher un covoiturage</h1>
    <form method="get">
        <label>Départ: <input type="text" name="depart" required></label><br>
        <label>Arrivée: <input type="text" name="arrivee" required></label><br>
        <label>Date: <input type="date" name="date" required></label><br>
        <label>Voyage écologique seulement <input type="checkbox" name="ecolo" value="1"></label><br>
        <label>Prix max: <input type="number" name="prix_max" step="0.01"></label><br>
        <label>Durée max: 
            <input type="number" name="duree_max_heures" placeholder="Heures" min="0"> h 
            <input type="number" name="duree_max_minutes" placeholder="Minutes" min="0" max="59"> min
        </label><br>
        <label>Note minimale du conducteur: <input type="number" name="note_min" min="0" max="5"></label><br>
        <input type="submit" value="Rechercher">
    </form>

    <h2>Résultats</h2>
    <?php if (count($results) > 0): ?>
        <?php foreach ($results as $r): ?>
            <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                <p><strong>Conducteur :</strong> <?= htmlspecialchars($r['pseudo']) ?> (Note : <?= $r['note'] ?>/5)</p>
                <p><img src="data:image/jpeg;base64,<?= base64_encode($r['photo']) ?>" width="100" /></p>
                <p><strong>Départ :</strong> <?= $r['depart'] ?> à <?= date('H:i', strtotime($r['heure_depart'])) ?> (<?= date('d/m/Y', strtotime($r['heure_depart'])) ?>)</p>
                <p><strong>Arrivée :</strong> <?= $r['arrivee'] ?> à <?= date('H:i', strtotime($r['heure_arrivee'])) ?></p>
                <?php
                    $duration = strtotime($r['heure_arrivee']) - strtotime($r['heure_depart']);
                    $hours = floor($duration / 3600);
                    $minutes = floor(($duration % 3600) / 60);
                ?>
                <p><strong>Durée :</strong> <?= $hours ?>h<?= $minutes > 0 ? $minutes : '' ?></p>
                <p><strong>Prix :</strong> <?= $r['prix'] ?> €</p>
                <p><strong>Places restantes :</strong> <?= $r['place'] ?></p>
                <p><strong>Écologique :</strong> <?= strtolower($r['energie']) == 'electrique' ? 'Oui' : 'Non' ?></p>
                <form method="get" action="detail.php">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <input type="submit" value="Détail">
                </form>
            </div>
        <?php endforeach; ?>
    <?php elseif ($depart && $arrivee && $date): ?>
        <p>Aucun covoiturage trouvé à cette date.</p>
        <?php
        // Proposition de la prochaine date possible
        $stmt2 = $pdo->prepare("
            SELECT DATE(heure_depart) as next_date
            FROM covoiturage
            WHERE depart = :depart AND arrivee = :arrivee AND place > 0 AND DATE(heure_depart) > :date
            ORDER BY heure_depart ASC
            LIMIT 1
        ");
        $stmt2->execute([':depart' => $depart, ':arrivee' => $arrivee, ':date' => $date]);
        $suggestion = $stmt2->fetch();
        if ($suggestion):
        ?>
            <p>Voulez-vous essayer le <?= $suggestion['next_date'] ?> à la place ?</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
