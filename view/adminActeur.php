<?php ob_start(); ?>

<table>
    <thead>
        <tr>
            <th>Liste des acteurs</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Récupérer et afficher tous les acteurs
        foreach ($requete->fetchAll() as $acteur) { ?>
            <tr>
                <td><a href="index.php?action=detailActeur&id=<?= $acteur['id_acteur']?>"> <?= $acteur["nom"] ?> <?= $acteur["prenom"] ?></a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>            

<?php
$titre = "Administration Acteur";
$contenu = ob_get_clean();
require "template.php";
?>
