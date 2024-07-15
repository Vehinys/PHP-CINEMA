<?php ob_start(); ?>

<!-- Formulaire d'ajout d'un genre -->


<table>
    <thead>
        <tr>
            <th>Les genres</th>
        </tr>
    </thead>
    <br>
    <tbody>
        <?php

        // Récupérer et afficher tous les acteurs
        foreach ($requete->fetchAll() as $genre) {

        ?>
            <tr>

                <td><?= $genre["libelle"] ?></td> <!-- récupérer et afficher le prénom de l'acteur -->

            </tr>
        <?php } ?>
    </tbody>
</table>

<br>


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