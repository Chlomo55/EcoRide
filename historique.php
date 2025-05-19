<?php 
include_once('header.php');

// Gestion de l'annulation d'une course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'annuler_course' && isset($_POST['passager_id'])) {
        $passager_id = intval($_POST['passager_id']);
        $stmt = $pdo->prepare("DELETE FROM passager WHERE id = :passager_id");
        $stmt->execute(['passager_id' => $passager_id]);
        header('Location: historique.php');
        exit;
    }

    // Gestion du changement d'état
    if ($_POST['action'] === 'changer_etat' && isset($_POST['covoiturage_id'], $_POST['nouvel_etat'])) {
        $covoiturage_id = intval($_POST['covoiturage_id']);
        $nouvel_etat = intval($_POST['nouvel_etat']);
        $stmt = $pdo->prepare("UPDATE covoiturage SET etat = :nouvel_etat WHERE id = :covoiturage_id");
        $stmt->execute(['nouvel_etat' => $nouvel_etat, 'covoiturage_id' => $covoiturage_id]);
        header('Location: historique.php');
        exit;
    }
}

// Requête avec jointure entre passager et covoiturage
$historique = $pdo->prepare("
    SELECT 
        passager.id AS passager_id,
        passager.date_participation,
        covoiturage.id AS covoiturage_id,
        covoiturage.depart,
        covoiturage.arrivee,
        covoiturage.heure_depart,
        covoiturage.heure_arrivee,
        covoiturage.prix,
        covoiturage.etat,
        covoiturage.id_chauffeur
    FROM covoiturage
    LEFT JOIN passager ON passager.covoiturage_id = covoiturage.id
    WHERE passager.user_id = :user_id OR covoiturage.id_chauffeur = :user_id
    ORDER BY passager.date_participation DESC
");
$historique->execute(['user_id' => $_SESSION['user_id']]);

if ($historique->rowCount() > 0) {
    echo "<table class='table table-striped'>";
    echo "<thead>
            <tr>
                <th>Date de participation</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Heure départ</th>
                <th>Heure arrivée</th>
                <th>Prix</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
          </thead>";
    echo "<tbody>";
    while ($row = $historique->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['date_participation']) . "</td>";
        echo "<td>" . htmlspecialchars($row['depart']) . "</td>";
        echo "<td>" . htmlspecialchars($row['arrivee']) . "</td>";
        echo "<td>" . htmlspecialchars($row['heure_depart']) . "</td>";
        echo "<td>" . htmlspecialchars($row['heure_arrivee']) . "</td>";
        echo "<td>" . htmlspecialchars($row['prix']) . " €</td>";
        switch ($row['etat']) {
            case 0:
                echo "<td>Annulée</td>";
                break;
            case 1:
                echo "<td>En attente</td>";
                break;
            case 2:
                echo "<td>Actif</td>";
                break;
            case 3:
                echo "<td>Terminé</td>";
                break;
            default:
                echo "<td>Inconnu</td>";
                break;
        }
        echo "<td>";
        // Si l'utilisateur est passager, afficher le bouton pour annuler
        if ($_SESSION['user_id'] !== $row['id_chauffeur']) {
            echo "<form method='POST' style='display:inline;'>
                    <input type='hidden' name='action' value='annuler_course'>
                    <input type='hidden' name='passager_id' value='" . htmlspecialchars($row['passager_id']) . "'>
                    <button type='submit' class='btn btn-danger btn-sm'>Annuler</button>
                  </form>";
        } else {
            // Si l'utilisateur est le chauffeur
            if ($row['etat'] == 1) {
                echo "<form method='POST' style='display:inline;'>
                        <input type='hidden' name='action' value='changer_etat'>
                        <input type='hidden' name='covoiturage_id' value='" . htmlspecialchars($row['covoiturage_id']) . "'>
                        <input type='hidden' name='nouvel_etat' value='2'>
                        <button type='submit' class='btn btn-primary btn-sm'>Commencer</button>
                      </form>";
            } elseif ($row['etat'] == 2) {
                echo "<form method='POST' style='display:inline;'>
                        <input type='hidden' name='action' value='changer_etat'>
                        <input type='hidden' name='covoiturage_id' value='" . htmlspecialchars($row['covoiturage_id']) . "'>
                        <input type='hidden' name='nouvel_etat' value='3'>
                        <button type='submit' class='btn btn-success btn-sm'>Terminer</button>
                      </form>";
            }
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>Aucun historique trouvé.</p>";
}
?>
