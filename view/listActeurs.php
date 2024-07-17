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
        foreach ($requete->fetchAll() as $listActeurs) { ?>
            <tr>
                <td><a href="index.php?action=detailActeur&id=<?= $listActeurs['id_acteur']?>"> <?= $listActeurs["nom"] ?> <?= $listActeurs["prenom"] ?></a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<br><br>

<button> <a href="index.php?action=adminActeur"> Admin </a> </button>

<?php
$titre = "Liste des Acteurs";
$contenu = ob_get_clean();
require "template.php";
?>
