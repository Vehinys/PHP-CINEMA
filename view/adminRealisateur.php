<?php ob_start(); ?>

<table>
    <thead>
        <tr>
            <th>Les Realisateurs</th>
        </tr>
    </thead>

    <tbody>
        <?php
        // Récupérer et afficher tous les acteurs
        foreach ($requete->fetchAll() as $listRealisateurs) { ?>
            <tr>
                <td><?= $listRealisateurs["nom"] ?> <?= $listRealisateurs["prenom"] ?></td>
                <td>
                    <form action="index.php?action=editRealisateur" method="post" style="display:inline;">
                        <input type="hidden" name="id_realisateur" value="<?= $listRealisateurs['id_realisateur'] ?>">
                        <input type="text" name="new_nom" required placeholder="Nouveau nom">
                        <input type="text" name="new_prenom" required placeholder="Nouveau prénom">
                        <input type="text" name="new_sexe" required placeholder="Nouveau sexe">
                        <input type="date" name="new_dateNaissance" required placeholder="Nouvelle date de naissance" max="<?= date('Y-m-d'); ?>">
                        <select name="new_role">
                            <option value="acteur">Acteur</option>
                            <option value="realisateur">Réalisateur</option>
                            <option value="les deux">Les deux</option>
                        </select>
                        <br>
                        <input type="submit" value="Modifier">
                    </form>
                    
                    <form action="index.php?action=deleteRealisateur" method="post" style="display:inline;">
                        <input type="hidden" name="id_realisateur" value="<?= $listRealisateurs['id_realisateur'] ?>">
                        <input type="submit" value="Supprimer">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<br>
<button><a href="index.php?action=listRealisateurs">Retour</a></button>

<?php
$titre = "Administration réalisateur";
$contenu = ob_get_clean();
require "template.php";