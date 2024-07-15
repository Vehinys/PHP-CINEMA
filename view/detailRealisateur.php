<?php ob_start(); ?>

<table>

    <!-- <thead>

        <tr>

            <th>details de l'acteur</th>

        </tr>

    </thead>  -->

    <tbody>
    <?php foreach ($requete->fetchAll() as $detailActeur) { ?>

        <tr>
            <td>
                <h2><?= $detailActeur["prenom"] ?> <?= $detailActeur['nom'] ?></h2>
                <h3><?= $detailActeur['naissance'] ?> ( <?= $detailActeur['age'] ?> ans ) | <?= $detailActeur["sexe"] ?></h3>
            </td>
        </tr>
    <?php } ?>
</tbody>

<tbody>
<tr>
            <td>FILM</td>
            <td>ANNEE</td>
            <td>GENRE</td>
        </tr>
    <?php // Récupère et affiche tous les rôles et le nom des films dans lesquels l'acteur a joué
    foreach ($requete2->fetchAll() as $filmDetail) { ?>

        <tr>
            <td> <a href="index.php?action=detailFilm&id=<?= $filmDetail['id_film'] ?>"> <?= $filmDetail["titre"] ?> </a> </td>
            <td><?= $filmDetail["sortie"] ?> ( <?= $filmDetail["age"] ?> ans )</td>
            <td> <?= $filmDetail["genre"] ?> </td> <!-- Nom du rôle correspondant -->
        </tr>
    <?php } ?>
</tbody>


</table>

<?php

$titre = "Film releases - Detail du réalisateur";
$contenu = ob_get_clean();

require "template.php";