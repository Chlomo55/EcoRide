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
?>

<!-- Style tableau responsive et écolo -->
<style>
.historique-container {
    max-width: 98vw;
    margin: 40px auto 0 auto;
    background: #f9fff9;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(56, 142, 60, 0.13);
    padding: 32px 18px 24px 18px;
}
.historique-title {
    color: #388e3c;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 28px;
    text-align: center;
    letter-spacing: 1px;
}
.responsive-table {
    width: 100%;
    border-collapse: collapse;
    background: #e8f5e9;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 12px #c8e6c9;
    margin-bottom: 18px;
}
.responsive-table th, .responsive-table td {
    padding: 14px 10px;
    text-align: center;
    border-bottom: 1.5px solid #c8e6c9;
}
.responsive-table th {
    background: linear-gradient(90deg, #43a047 60%, #81c784 100%);
    color: #fff;
    font-size: 1.08rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.responsive-table tr:nth-child(even) {
    background: #f9fff9;
}
.responsive-table tr:hover {
    background: #c8e6c9;
    transition: background 0.2s;
}
.btn, .btn-danger, .btn-primary, .btn-success {
    border: none;
    border-radius: 8px;
    padding: 7px 18px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    margin: 2px 0;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    box-shadow: 0 1px 6px #c8e6c9;
}
.btn-danger {
    background: linear-gradient(90deg, #d84315 60%, #ff7043 100%);
    color: #fff;
}
.btn-danger:hover {
    background: linear-gradient(90deg, #b71c1c 60%, #ff7043 100%);
}
.btn-primary {
    background: linear-gradient(90deg, #388e3c 60%, #81c784 100%);
    color: #fff;
}
.btn-primary:hover {
    background: linear-gradient(90deg, #2e7d32 60%, #66bb6a 100%);
}
.btn-success {
    background: linear-gradient(90deg, #43a047 60%, #81c784 100%);
    color: #fff;
}
.btn-success:hover {
    background: linear-gradient(90deg, #388e3c 60%, #66bb6a 100%);
}
@media (max-width: 900px) {
    .responsive-table thead {
        display: none;
    }
    .responsive-table, .responsive-table tbody, .responsive-table tr, .responsive-table td {
        display: block;
        width: 100%;
    }
    .responsive-table tr {
        margin-bottom: 18px;
        border-radius: 12px;
        box-shadow: 0 1px 6px #c8e6c9;
        background: #e8f5e9;
        padding: 10px 0;
    }
    .responsive-table td {
        text-align: left;
        padding: 10px 16px;
        position: relative;
    }
    .responsive-table td:before {
        content: attr(data-label);
        font-weight: 700;
        color: #388e3c;
        display: block;
        margin-bottom: 4px;
        font-size: 0.98rem;
    }
}
</style>

<div class="historique-container">
    <div class="historique-title">Mon historique de covoiturages</div>
    <?php
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
        echo "<div style='overflow-x:auto;'>";
        echo "<table class='responsive-table'>";
        echo "<thead>
                <tr>
                    <th>Date</th>
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
            echo "<td data-label='Date'>" . htmlspecialchars($row['date_participation']) . "</td>";
            echo "<td data-label='Départ'>" . htmlspecialchars($row['depart']) . "</td>";
            echo "<td data-label='Arrivée'>" . htmlspecialchars($row['arrivee']) . "</td>";
            echo "<td data-label='Heure départ'>" . htmlspecialchars($row['heure_depart']) . "</td>";
            echo "<td data-label='Heure arrivée'>" . htmlspecialchars($row['heure_arrivee']) . "</td>";
            echo "<td data-label='Prix'>" . htmlspecialchars($row['prix']) . " €</td>";
            switch ($row['etat']) {
                case 0:
                    $etat = "<span style='color:#d84315;font-weight:600;'>Annulée</span>";
                    break;
                case 1:
                    $etat = "<span style='color:#fbc02d;font-weight:600;'>En attente</span>";
                    break;
                case 2:
                    $etat = "<span style='color:#388e3c;font-weight:600;'>Actif</span>";
                    break;
                case 3:
                    $etat = "<span style='color:#43a047;font-weight:600;'>Terminé</span>";
                    break;
                default:
                    $etat = "<span style='color:#757575;font-weight:600;'>Inconnu</span>";
                    break;
            }
            echo "<td data-label='État'>$etat</td>";
            echo "<td data-label='Actions'>";
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
        echo "</div>";
    } else {
        echo "<p style='text-align:center;color:#388e3c;font-weight:600;'>Aucun historique trouvé.</p>";
    }
    ?>
</div>
