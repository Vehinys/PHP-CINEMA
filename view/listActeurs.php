<?php ob_start(); ?>

<table>
    <thead>
        <tr>
            <th>liste des acteurs</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Récupérer et afficher tous les acteurs
        foreach ($requete->fetchAll() as $listActeurs) { ?>
            <tr>
                <td><a href="index.php?action=detailActeur&id=<?= $listActeurs['id_acteur']?>"> <?= $listActeurs["nom"] ?> <?= $listActeurs["prenom"] ?></a></td> <!-- récupérer et afficher le nom de l'acteur -->

            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Formulaire pour ajouter un nouvel acteur ou une nouvelle actrice à la base de donnée -->
<form action="index.php?action=ajouterNouvelActeur" method="post">
    <!-- choisir le nom -->
    <label for="nom">Nom :</label><br>
    <input required="required" type="text" id="nom" name="nom" /><br>
    <input type="submit" name="submit">
</form>

<?php
$titre = "Liste des Acteurs";
$contenu = ob_get_clean();
require "template.php";
?>
