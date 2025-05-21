<?php
include_once('header.php'); // Inclut le fichier d'en-tête

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php'); // Redirige vers la page de connexion
    exit;
}
?>
<!-- Styles personnalisés verts écolos -->
<style>
body {
    background: linear-gradient(135deg, #e8f5e9 0%, #a5d6a7 100%);
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    padding: 0;
}
.compte-container {
    max-width: 520px;
    margin: 48px auto 0 auto;
    background: #f9fff9;
    border-radius: 22px;
    box-shadow: 0 8px 32px rgba(56, 142, 60, 0.13);
    padding: 40px 36px 36px 36px;
    text-align: center;
    position: relative;
    border: 2px solid #c8e6c9;
}
.compte-container h1 {
    color: #388e3c;
    margin-bottom: 28px;
    font-size: 2.3rem;
    letter-spacing: 1.5px;
    font-weight: 700;
    text-shadow: 0 2px 8px #c8e6c9;
}
form {
    margin-bottom: 32px;
}
.input-green, select {
    padding: 11px 18px;
    border-radius: 9px;
    border: 1.5px solid #a5d6a7;
    background: #e8f5e9;
    color: #2e7d32;
    font-size: 1.05rem;
    margin-right: 12px;
    transition: border 0.2s, box-shadow 0.2s;
    box-shadow: 0 1px 4px #c8e6c9;
}
.input-green:focus, select:focus {
    border: 2px solid #43a047;
    outline: none;
    box-shadow: 0 0 0 2px #a5d6a7;
}
button[type="submit"] {
    background: linear-gradient(90deg, #43a047 60%, #81c784 100%);
    color: #fff;
    border: none;
    border-radius: 9px;
    padding: 11px 28px;
    font-size: 1.08rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(56, 142, 60, 0.10);
    transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
    letter-spacing: 0.5px;
}
button[type="submit"]:hover {
    background: linear-gradient(90deg, #388e3c 60%, #66bb6a 100%);
    transform: translateY(-2px) scale(1.04);
    box-shadow: 0 4px 16px rgba(56, 142, 60, 0.18);
}
.compte-info {
    margin-bottom: 34px;
    background: #e8f5e9;
    border-radius: 12px;
    padding: 18px 0 10px 0;
    box-shadow: 0 1px 6px #c8e6c9;
}
.compte-info p {
    color: #2e7d32;
    font-size: 1.13rem;
    margin: 10px 0;
}
.compte-info span {
    color: #388e3c;
    font-weight: 700;
}
.links {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 10px;
}
.links a {
    display: inline-block;
    background: linear-gradient(90deg, #43a047 60%, #81c784 100%);
    color: #fff;
    text-decoration: none;
    padding: 12px 0;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.08rem;
    transition: background 0.2s, box-shadow 0.2s, color 0.2s, transform 0.1s;
    box-shadow: 0 2px 8px rgba(56, 142, 60, 0.10);
    letter-spacing: 0.5px;
}
.links a:hover {
    background: linear-gradient(90deg, #388e3c 60%, #66bb6a 100%);
    color: #e8f5e9;
    box-shadow: 0 4px 16px rgba(56, 142, 60, 0.18);
    transform: scale(1.03);
}
.success-message {
    background: #e8f5e9;
    color: #388e3c;
    border: 1.5px solid #a5d6a7;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 20px;
    font-weight: 600;
    box-shadow: 0 1px 6px #c8e6c9;
}
.error-message {
    background: #fff3e0;
    color: #d84315;
    border: 1.5px solid #ffccbc;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 20px;
    font-weight: 600;
    box-shadow: 0 1px 6px #ffe0b2;
}
@media (max-width: 600px) {
    .compte-container {
        padding: 18px 6px 18px 6px;
        max-width: 98vw;
    }
    .links a {
        font-size: 1rem;
        padding: 10px 0;
    }
    .compte-info {
        padding: 10px 0 6px 0;
    }
}
</style>
<!-- Inclusion des scripts -->
<script crossorigin="anonymous" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script crossorigin="anonymous" integrity="sha256-uto3j0v5x+6gk4m7c5q8f5z5f5f5f5f5f5f5f5f5=" src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<div class="compte-container">
    <h1>Bienvenue sur votre compte</h1>
    <form action="" method="post">
        <select class="input-green" id="category" name="category">
            <option value="chauffeur" <?php if(isset($_SESSION['category']) && $_SESSION['category']=='chauffeur') echo 'selected'; ?>>Chauffeur</option>
            <option value="passager" <?php if(isset($_SESSION['category']) && $_SESSION['category']=='passager') echo 'selected'; ?>>Passager</option>
            <option value="2" <?php if(isset($_SESSION['category']) && $_SESSION['category']=='2') echo 'selected'; ?>>Chauffeur et Passager</option>
        </select>
        <button name="update_category" type="submit">Mettre à jour</button>
    </form>
    <?php
    if (isset($_POST['update_category'])) {
        $newCategory = $_POST['category'];
        $userId = $_SESSION['user_id'];

        // Connexion à la base de données
        $conn = new mysqli('localhost', 'root', '', 'ecoride');

        // Vérification de la connexion
        if ($conn->connect_error) {
            echo '<div class="error-message">Échec de la connexion : ' . $conn->connect_error . '</div>';
        } else {
            // Mise à jour de la catégorie
            $stmt = $conn->prepare("UPDATE user SET category = ? WHERE id = ?");
            $stmt->bind_param("si", $newCategory, $userId);

            if ($stmt->execute()) {
                echo '<div class="success-message">Catégorie mise à jour avec succès.</div>';
                $_SESSION['category'] = $newCategory; // Met à jour la session
            } else {
                echo '<div class="error-message">Erreur lors de la mise à jour de la catégorie.</div>';
            }

            $stmt->close();
            $conn->close();
        }
    }
    ?>
    <div class="compte-info">
        <p>Vous êtes connecté en tant que <span><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
        <p>Votre catégorie est <span><?php echo htmlspecialchars($_SESSION['category']); ?></span></p>
        <p>Votre email est <span><?php echo htmlspecialchars($_SESSION['mail']); ?></span></p>
        <p>Actuellement il vous reste <span><?php echo htmlspecialchars($_SESSION['credit']); ?></span> crédit(s)</p>
    </div>
    <div class="links">
        <a href="deconnexion.php">Déconnexion</a>
        <a href="covoiturage.php">Proposer un covoiturage</a>
        <a href="vue.php">Vue</a>
        <a href="historique.php">Historique</a>
    </div>
</div>
<?php
if (isset($_SESSION['category']) && ($_SESSION['category'] == 'chauffeur' || $_SESSION['category'] == '2')) {
    include_once('voiture.php');
}
?>


<!-- Bloc de préférences dynamiques -->
<div id="preferences-container"></div>
<button type="button" onclick="ajouterPreference()">Ajouter une préférence</button>

<style>
.preference-item {
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.remove-btn {
    cursor: pointer;
    color: red;
    font-weight: bold;
}
</style>

<script>
let compteurPreference = 0;

function ajouterPreference() {
    compteurPreference++;

    const container = document.getElementById('preferences-container');

    const div = document.createElement('div');
    div.classList.add('preference-item');

    const label = document.createElement('label');
    label.textContent = `Préférence ${compteurPreference}`;
    label.classList.add('preference-label');

    const input = document.createElement('input');
    input.type = 'text';
    input.name = `preference[]`;

    const boutonSuppression = document.createElement('span');
    boutonSuppression.textContent = '❌';
    boutonSuppression.classList.add('remove-btn');
    boutonSuppression.onclick = function() {
        container.removeChild(div);
        reindexPreferences();
    };

    div.appendChild(label);
    div.appendChild(input);
    div.appendChild(boutonSuppression);

    container.appendChild(div);
}

function reindexPreferences() {
    const items = document.querySelectorAll('#preferences-container .preference-item');
    compteurPreference = 0;
    items.forEach((item, index) => {
        compteurPreference++;
        const label = item.querySelector('.preference-label');
        label.textContent = `Préférence ${compteurPreference}`;
    });
}
</script>
