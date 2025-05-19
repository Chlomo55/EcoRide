<?php
include_once('header.php'); // Inclut le fichier d'en-tête

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php'); // Redirige vers la page de connexion
    exit;
}
?>

<!-- Inclusion des scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-uto3j0v5x+6gk4m7c5q8f5z5f5f5f5f5f5f5f5f5=" crossorigin="anonymous"></script>

<!-- Bienvenue -->
<div>
    <h1>Bienvenue sur votre compte</h1>
    <form method="post" action="">
        <select name="category" id="category">
            <option value="chauffeur">Chauffeur</option>
            <option value="passager">Passager</option>
            <option value="2">Chauffeur et Passager</option>
        </select>
        <button type="submit" name="update_category">Mettre à jour</button>
    </form>

    <?php
    if (isset($_POST['update_category'])) {
        $newCategory = $_POST['category'];
        $userId = $_SESSION['user_id'];

        // Connexion à la base de données
        $conn = new mysqli('localhost', 'root', '', 'ecoride');

        // Vérification de la connexion
        if ($conn->connect_error) {
            die("Échec de la connexion : " . $conn->connect_error);
        }

        // Mise à jour de la catégorie
        $stmt = $conn->prepare("UPDATE user SET category = ? WHERE id = ?");
        $stmt->bind_param("si", $newCategory, $userId);

        if ($stmt->execute()) {
            echo "<p>Catégorie mise à jour avec succès.</p>";
            $_SESSION['category'] = $newCategory; // Met à jour la session
        } else {
            echo "<p>Erreur lors de la mise à jour de la catégorie.</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
    <p>Vous êtes connecté en tant que <?php echo $_SESSION['username']; ?></p>
    <p>Votre categorie est <?php echo $_SESSION['category']?></p>
    <p>Votre email est <?php echo $_SESSION['mail']; ?></p>
    <p>Actuellement il vous reste <?php echo $_SESSION['credit']; ?> crédit(s)</p>
    <p><a href="deconnexion.php">Déconnexion</a></p>
    <p><a href="covoiturage.php">Proposer un covoiturage</a></p>
    <p><a href="vue.php">vue</a></p>
    <p><a href="historique.php">Historique</a></p>
</div>

<?php
if (isset($_SESSION['category']) && ($_SESSION['category'] == 'chauffeur' || $_SESSION['category'] == '2')) {
    include_once('voiture.php');
}
?>

