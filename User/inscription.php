<?php 
require_once('pdo.php');
?>

<div>
    <h1>Inscription</h1>
    <form action="inscription.php" method="POST" enctype="multipart/form-data">
        <label for="pseudo">Pseudo :</label><br>
        <input type="text" id="pseudo" name="pseudo" required><br><br>

        <label for="photo">Photo de profil :</label><br>
        <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.gif" required><br><br>
        
        <label for="mail">Adresse mail :</label><br>
        <input type="email" id="mail" name="mail" required><br><br>
        
        <label for="pass">Mot de passe :</label><br>
        <input type="password" id="pass" name="pass" required><br><br>

        <input type="submit" value="S'inscrire">
    </form>

    <p>Déjà inscrit ? <a href="connexion.php">Connectez-vous ici</a></p>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $mail = filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL);
        $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);

        if (!$mail) {
            echo "<p style='color: red;'>Adresse mail invalide.</p>";
            exit;
        }

        // Traitement de l'image
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $photoTmpPath = $_FILES['photo']['tmp_name'];
            $photoName = $_FILES['photo']['name'];
            $photoExtension = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));

            if (in_array($photoExtension, $allowedExtensions)) {
                $photoData = file_get_contents($photoTmpPath);
            } else {
                echo "<p style='color: red;'>Extension non autorisée. Formats acceptés : jpg, jpeg, png, gif.</p>";
                exit;
            }
        } else {
            echo "<p style='color: red;'>Erreur lors du téléchargement de la photo.</p>";
            exit;
        }

        // Insertion dans la base de données
        $stmt = $pdo->prepare("INSERT INTO user (pseudo, mail, pass, photo, credit, role) VALUES (?, ?, ?, ?, ?, ?)");
        $credit = 25;
        $role = 'user';

        try {
            $stmt->execute([$pseudo, $mail, $pass, $photoData, $credit, $role]);
            echo "<p style='color: green;'>Inscription réussie !</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur lors de l'inscription : " . $e->getMessage() . "</p>";
        }
    }
    ?>
</div>
