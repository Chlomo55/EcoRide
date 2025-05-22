<?php 
require_once('header.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['pass'];
    
    // Prépare la requête pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT * FROM user WHERE pseudo = ? OR mail = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['pass'])) {
        $_SESSION['user_logged_in'] = true; // Indique que l'utilisateur est connecté
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['pseudo']; 
        $_SESSION['mail'] = $user['mail'];
        $_SESSION['credit'] = $user['credit'];
        $_SESSION['category'] = $user['category'];
        header('Location: compte.php'); // Redirige vers la page d'accueil
        exit;
    } else {
        echo "<p style='color: red;'>Identifiants incorrects.</p>";
    }
}

?>
<div>
    <form method="post">
        <h1>Connexion</h1>
        <label for="username">Adresse mail ou pseudo:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="pass">Mot de passe :</label><br>
        <input type="password" id="pass" name="pass" required><br><br>
        
        <input type="submit" value="Se connecter">
        <p>Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous ici</a></p>
    </form>
</div>