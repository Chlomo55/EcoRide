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
    <select name="category" id="category">
        <option value="chauffeur">Chauffeur</option>
        <option value="passager">Passager</option>
        <option value="2">Chauffeur et Passager</option>
    </select>
    <p>Vous êtes connecté en tant que <?php echo $_SESSION['username']; ?></p>
    <p>Votre email est <?php echo $_SESSION['mail']; ?></p>
    <p>Actuellement il vous reste <?php echo $_SESSION['credit']; ?> crédit(s)</p>
    <p><a href="deconnexion.php">Déconnexion</a></p>
    <p><a href="covoiturage.php">Proposer un covoiturage</a></p>
    <p><a href="vue.php">vue</a></p>
</div>

<?php
// Gestion de l'enregistrement du véhicule
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $immatriculation = $_POST['immatriculation'];
    $premiere_immat = $_POST['1er'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $couleur = $_POST['couleur'];
    $energie = $_POST['energie'] === 'autre' ? $_POST['autre_energie'] : $_POST['energie'];
    $nb_place = $_POST['nb_place'];
    $fumeur = $_POST['fumeur'];
    $animaux = $_POST['animaux'];

    // Récupère les préférences
    $preferences = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'pref_') === 0 && !empty($value)) {
            $preferences[] = $value;
        }
    }
    $preferences_user = implode(', ', $preferences);

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO voiture (user_id, immatriculation, date, marque, modele, couleur, energie, nb_place, fumeur, animaux, preferences) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$_SESSION['user_id'], $immatriculation, $premiere_immat, $marque, $modele, $couleur, $energie, $nb_place, $fumeur, $animaux, $preferences_user])) {
        echo "<p style='color: green;'>Véhicule et préférences enregistrés avec succès !</p>";
    } else {
        echo "<p style='color: red;'>Erreur lors de l'enregistrement du véhicule et des préférences.</p>";
    }
}

// Récupération des véhicules de l'utilisateur
$voiture_user_id = $pdo->prepare("SELECT * FROM voiture WHERE user_id = ?");
$voiture_user_id->execute([$_SESSION['user_id']]);
$voitures = $voiture_user_id->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Affichage des véhicules -->
<?php if (count($voitures) > 0): ?>
    <div class="card">
        <h3><?php echo count($voitures) === 1 ? "Votre véhicule enregistré" : "Vos véhicules enregistrés"; ?></h3>
        <?php foreach ($voitures as $row): ?>
            <p><strong>Immatriculation:</strong> <?php echo htmlspecialchars($row['immatriculation']); ?></p>
            <p><strong>Date de première immatriculation:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
            <p><strong>Marque:</strong> <?php echo htmlspecialchars($row['marque']); ?></p>
            <p><strong>Modèle:</strong> <?php echo htmlspecialchars($row['modele']); ?></p>
            <p><strong>Couleur:</strong> <?php echo htmlspecialchars($row['couleur']); ?></p>
            <p><strong>Énergie:</strong> <?php echo htmlspecialchars($row['energie']); ?></p>
            <p><strong>Fumeur:</strong> <?php echo htmlspecialchars($row['fumeur']) === 1 ? "Oui" : "Non"; ?></p>
            <p><strong>Animaux:</strong> <?php echo htmlspecialchars($row['animaux']) === 1 ? "Oui" : "Non"; ?></p>
            <p><strong>Vos préférences:</strong>
                <?php if (empty($row['preferences'])): ?>
                    Aucune préférence enregistrée.
                <?php else: ?>
                    <ul>
                        <?php foreach (explode(', ', $row['preferences']) as $preference): ?>
                            <li><?php echo htmlspecialchars($preference); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </p>
            <p><strong>Nombre de places disponibles:</strong> <?php echo htmlspecialchars($row['nb_place']); ?></p>
            <hr>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="card">
        <h3>Aucun véhicule enregistré</h3>
        <p>Veuillez enregistrer un véhicule pour proposer un covoiturage.</p>
    </div>
<?php endif; ?>

<!-- Formulaire d'enregistrement de véhicule -->
<div class="chauffeur-div">
    <form method="post">
        <h3>Votre véhicule</h3>
        <div>
            <label for="immatriculation">Votre plaque d'immatriculation</label>
            <input type="text" id="immatriculation" name="immatriculation" required>
        </div>
        <div>
            <label for="1er">Date de votre première immatriculation</label>
            <input type="date" id="1er" name="1er" required>
        </div>
        <div>
            <label for="marque">Marque de votre véhicule</label>
            <input type="text" id="marque" name="marque" required>
        </div>
        <div>
            <label for="modele">Modèle de votre véhicule</label>
            <input type="text" id="modele" name="modele" required>
        </div>
        <div>
            <label for="couleur">Couleur de votre véhicule</label>
            <input type="text" id="couleur" name="couleur" required>
        </div>
        <div>
            <label for="energie">Énergie</label>
            <select name="energie" id="energie">
                <option value="essence">Essence</option>
                <option value="diesel">Diesel</option>
                <option value="electrique">Électrique</option>
                <option value="hybride">Hybride</option>
                <option value="autre">Autre</option>
            </select>
        </div>
        <div id="autre-energie-div" style="display: none;">
            <label for="autre_energie">Veuillez préciser l'énergie</label>
            <input type="text" id="autre_energie" name="autre_energie">
        </div>
        <div>
            <label for="nb_place">Nombre de places disponibles</label>
            <input type="number" id="nb_place" name="nb_place" required>
        </div>
        <div>
            <p>Vos préférences</p>
            <div>
                <label for="fumeur">Votre trajet est :</label>
                <input type="radio" id="fumeur" name="fumeur" value="fumeur" required> Fumeur
                <input type="radio" id="non-fumeur" name="fumeur" value="non-fumeur"> Non-Fumeur
            </div>
            <div>
                <label for="animaux">Acceptez-vous les animaux ?</label>
                <input type="radio" id="oui" name="animaux" value="oui" required> Oui
                <input type="radio" id="non" name="animaux" value="non"> Non
            </div>
            <button class="ajout-preferences">Ajouter des préférences</button>
            <div class="vos-preferences">
                <div>
                    <label for="pref_1">Préférence 1</label>
                    <input type="text" id="pref_1" name="pref_1">
                    <button class="remove-preference" style="color: red;">&#10006;</button>
                </div>
                <div>
                    <label for="pref_2">Préférence 2</label>
                    <input type="text" id="pref_2" name="pref_2">
                    <button class="remove-preference" style="color: red;">&#10006;</button>
                </div>
                <button id="prefplus">
                    <p>Nouvelle préférence</p>
                </button>
            </div>
            <br>
            <button type="submit">Enregistrer</button>
        </div>
    </form>
</div>

<!-- Scripts JS -->
<script>
    $(document).ready(function() {
        // Gestion de l'énergie "Autre"
        $('#energie').change(function() {
            if ($(this).val() === 'autre') {
                $('#autre-energie-div').show();
            } else {
                $('#autre-energie-div').hide();
                $('#autre_energie').val('');
            }
        });

        // Gestion des préférences
        $('.vos-preferences').hide();
        $('.ajout-preferences').click(function(e) {
            e.preventDefault();
            $('.vos-preferences').show();
            $(this).prop('disabled', true);
        });

        $('#prefplus').click(function(e) {
            e.preventDefault();
            let preferenceCount = $('.vos-preferences div').length + 1;
            const newPreference = `
                <div>
                    <label for="pref_${preferenceCount}">Préférence ${preferenceCount}</label>
                    <input type="text" id="pref_${preferenceCount}" name="pref_${preferenceCount}">
                    <button class="remove-preference" style="color: red;">&#10006;</button>
                </div>
            `;
            $('#prefplus').before(newPreference);
        });

        $(document).on('click', '.remove-preference', function(e) {
            e.preventDefault();
            $(this).parent().remove();
            if ($('.vos-preferences div').length === 0) {
                $('.vos-preferences').hide();
                $('.ajout-preferences').prop('disabled', false);
            }
        });
    });
</script>
