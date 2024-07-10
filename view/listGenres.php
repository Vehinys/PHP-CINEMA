<?php ob_start(); ?>

<!-- Affiche le nb total de Genres enregistrés en BDD (table genre) -->

<!-- Formulaire d'ajout d'un genre -->
<form action="index.php?action=addNouveauGenre" method="post">
    <!-- édition du libellé du genre -->
    <label for="nomGenre">Nom du genre :</label><br>
    <input required="required" type="text" id="addGenre" name="addGenre" /><br>
    <input type='submit' name='submit'>
</form>

<?php
$titre = "Liste des genres";
$titre_secondaire = "Liste des genres";
$contenu = ob_get_clean();
require "template.php";