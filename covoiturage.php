<?php 
include_once('header.php'); // Inclut le fichier d'en-tête

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $voiture = $_POST['voiture'];
    $depart = $_POST['depart'];
    $arrivee = $_POST['arrivee'];
    $heure_depart = $_POST['heure_depart'];
    $heure_arrivee = $_POST['heure_arrivee'];
    $prix = $_POST['prix'];

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO covoiturage (voiture_id, depart, arrivee, heure_depart, heure_arrivee, prix) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$voiture, $depart, $arrivee, $heure_depart, $heure_arrivee, $prix])) {
        echo "<p style='color: green;'>Covoiturage proposé avec succès !</p>";
    } else {
        echo "<p style='color: red;'>Erreur lors de la proposition du covoiturage.</p>";
    }
}
?>
<div>
    <form method="post">
        <h1>Proposer un covoiturage</h1>

        <label for="voiture">Sélectionnez votre véhicule:</label><br>
        <?php 
        $select = $pdo->prepare("SELECT * FROM voiture WHERE user_id = ?");
        $select->execute([$_SESSION['user_id']]);   
        ?>
        <div>
            <select name="voiture" id="voiture">
                <?php while ($row = $select->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>">
                        <?php echo htmlspecialchars($row['marque'] . ' ' . $row['modele'] . ' - ' . $row['immatriculation']); ?>
                    </option>
                <?php endwhile; ?>
        </select> 
        </div>
       

        <label for="depart">Lieu de départ:</label><br>
        <input type="text" id="depart" name="depart" required><br><br>
        
        <label for="arrivee">Lieu d'arrivée:</label><br>
        <input type="text" id="arrivee" name="arrivee" required><br><br>
        
        <label for="heure_depart">Date et heure de départ:</label><br>
        <input type="datetime-local" id="heure_depart" name="heure_depart" required><br><br>
        
        <label for="heure_arrivee">Date et heure d'arrivée:</label><br>
        <input type="datetime-local" id="heure_arrivee" name="heure_arrivee" required><br><br>
        
        <label for="prix">Prix par passager:</label><br>
        <input type="number" id="prix" name="prix" required><br><br>


        <input type="submit" value="Proposer">
    </form>
</div>