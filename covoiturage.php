<?php 
include_once('header.php'); // Inclut le fichier d'en-tête

?>
<div>
    <form method="post">
        <h1>Proposer un covoiturage</h1>
        <label for="depart">Ville de départ:</label><br>
        <input type="text" id="depart" name="depart" required><br><br>
        
        <label for="arrivee">Ville d'arrivée:</label><br>
        <input type="text" id="arrivee" name="arrivee" required><br><br>
        
        <label for="date_depart">Date de départ:</label><br>
        <input type="date" id="date_depart" name="date_depart" required><br><br>
        
        <label for="heure_depart">Heure de départ:</label><br>
        <input type="time" id="heure_depart" name="heure_depart" required><br><br>
        
        <label for="prix">Prix par passager:</label><br>
        <input type="number" id="prix" name="prix" required><br><br>

        <label for="nb_place">Nombre de places disponibles:</label><br>
        <input type="number" id="nb_place" name="nb_place" required><br><br>
        
        <input type="submit" value="Proposer">
    </form>
</div>