<?php ob_start(); ?>

<!-- Formulaire d'ajout d'un genre -->


<table class="container_table_listGenres">
    <thead class="container_table_titre">
        <tr>
            <th><div id="rectangle_red"></div>  Les genres </th>
        </tr>
    </thead>
    <br>
    <tbody>
        <?php
        // Récupérer et afficher tous les acteurs
        foreach ($requete->fetchAll() as $genre) {
        ?>
            <tr>
                <td><?= $genre["libelle"] ?></td> <!-- récupérer et afficher le libellé du genre -->

            </tr>
        <?php } ?>
    </tbody>
</table>

<br>

<button> <a href="index.php?action=adminGenre"> Admin </a> </button>



<?php
$titre = "Liste des genres";
$titre_secondaire = "Liste des genres";
$contenu = ob_get_clean();
require "template.php";