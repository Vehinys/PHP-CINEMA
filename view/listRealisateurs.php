<?php ob_start(); ?>


<table>
    <thead>
        <tr>
            <th>liste des realisateurs</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Récupérer et afficher tous les acteurs
        foreach ($requete->fetchAll() as $listRealisateurs) { ?>
            <tr>
                <td><a href="index.php?action=detailRealisateur&id=<?= $listRealisateurs['id_realisateur']?>"> <?= $listRealisateurs["nom"] ?> <?= $listRealisateurs["prenom"] ?></a></td> <!-- récupérer et afficher le nom de l'acteur -->

            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
$titre = "Film releases - Realisateurs";
$contenu = ob_get_clean();
require "view/template.php";