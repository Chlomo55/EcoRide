<?php 
include_once('header.php'); // Inclut le fichier d'en-tête

 if (!isset($_SESSION['user_id'])) {
     header('Location: connexion.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
     exit;
 }
 else {  ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-uto3j0v5x+6gk4m7c5q8f5z5f5f5f5f5f5f5f5f5f5=" crossorigin="anonymous"></script>
 <!-- Bienvenue -->
    <div>
        <h1>Bienvenue sur votre compte</h1>
        <select name="category" id="category">
            <option value="chauffeur">Chauffeur</option>
            <option value="passager">Passager</option>
            <option value="2">Chauffeur et Passager</option>
        </select>
        <p>Vous êtes connecté en tant que <?php echo $_SESSION['username']; ?></p>
        <p>Votre email est <?php echo $_SESSION['mail']?></p>
        <p>Actuellement il vous reste <?php echo $_SESSION['credit']?> credit(s) </p>
        <p><a href="deconnexion.php">Déconnexion</a></p>
        <p><a href="covoiturage.php">Proposer un covoiturage</a></p>
 <?php } ?>


 <!-- Vehicule -->
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
    </form>
 </div>

 <script>
    $(document).ready(function() {
        // Cache la div "vos-preferences" au début
        $('.vos-preferences').hide();

        // Affiche la div "vos-preferences" au clic sur le bouton "ajout-preferences"
        $('.ajout-preferences').click(function(e) {
            e.preventDefault();
            $('.vos-preferences').show();

            // Réinitialise les préférences par défaut si aucun input n'existe
            if ($('.vos-preferences div').length === 0) {
                const defaultPreferences = `
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
                `;
                $('#prefplus').before(defaultPreferences); // Ajoute les inputs avant le bouton "Nouvelle préférence"
            }

            $(this).prop('disabled', true); // Désactive le bouton "ajout-preferences"
        });

        // Ajoute un nouvel input pour une nouvelle préférence au clic sur le bouton "prefplus"
        $('#prefplus').click(function(e) {
            e.preventDefault();
            let lastPreference = $('.vos-preferences div:last input').attr('id');
            let preferenceCount = lastPreference ? parseInt(lastPreference.split('_')[1]) : 0;
            preferenceCount++;
            const newPreference = `
                <div>
                    <label for="pref_${preferenceCount}">Préférence ${preferenceCount}</label>
                    <input type="text" id="pref_${preferenceCount}" name="pref_${preferenceCount}">
                    <button class="remove-preference" style="color: red;">&#10006;</button>
                </div>
            `;
            // Ajoute le nouvel input juste avant le bouton "Nouvelle préférence"
            $('#prefplus').before(newPreference);
        });

        // Supprime un input de préférence au clic sur la croix rouge
        $(document).on('click', '.remove-preference', function(e) {
            e.preventDefault();
            $(this).parent().remove();

            // Réorganise les numéros des préférences après suppression
            $('.vos-preferences div').each(function(index) {
                const newIndex = index + 1;
                $(this).find('label').attr('for', `pref_${newIndex}`).text(`Préférence ${newIndex}`);
                $(this).find('input').attr('id', `pref_${newIndex}`).attr('name', `pref_${newIndex}`);
            });

            // Si tous les inputs sont supprimés, cache la div "vos-preferences" et réactive le bouton "ajout-preferences"
            if ($('.vos-preferences div').length === 0) {
                $('.vos-preferences').hide();
                $('.ajout-preferences').prop('disabled', false);
            }
        });
    });
 </script>
