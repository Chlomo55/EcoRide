<?php 
require_once('pdo.php');
?>

<div>
    <h1>Inscription</h1>
    <form action="inscription.php" method="POST">
        <label for="pseudo">Pseudo :</label><br>
        <input type="text" id="pseudo" name="pseudo" required><br><br>
        
        <label for="mail">Adresse mail :</label><br>
        <input type="mail" id="mail" name="mail" required><br><br>
        
        <label for="pass">Mot de passe :</label><br>
        <input type="pass" id="pass" name="pass" required><br><br>
        
        <input type="submit" value="S'inscrire">
    </form>
    <p>Déjà inscrit ? <a href="connexion.php">Connectez-vous ici</a></p>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pseudo = $_POST['pseudo'];
        $mail = $_POST['mail'];
        $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT); // On hash le mot de passe

        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO user (pseudo, mail, pass, credit, role) VALUES (?, ?, ?, ?, ?)");
        $role = 'user'; // Valeur par défaut pour le rôle
        $credit = 25; // Valeur par défaut pour le crédit
        if ($stmt->execute([$pseudo, $mail, $pass, $credit, $role])) {
            echo "<p style='color: green;'>Inscription réussie !</p>";
        } else {
            echo "<p style='color: red;'>Erreur lors de l'inscription.</p>";
        }
    }
    ?>
</div>