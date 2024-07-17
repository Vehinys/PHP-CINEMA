<?php ob_start(); ?>
<table>
    <thead>
        <tr>
            <th>Ajoute une personne :</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <form action="index.php?action=addNouveauActeur" method="post">
                    <div>
                        <label for="nom">Nom :</label><br>
                        <input required type="text" id="nom" name="nom" /><br><br>
                    </div>

                    <div>
                        <label for="prenom">Prenom :</label><br>
                        <input required type="text" id="prenom" name="prenom" /><br><br>
                    </div>

                    <div>
                        <label for="sexe">Sexe :</label><br>
                        <input required type="text" id="sexe" name="sexe" /><br><br>
                    </div>

                    <div>
                        <label for="dateNaissance">Date de naissance :</label><br>
                        <input required type="date" id="dateNaissance" name="dateNaissance" min="1800-01-01" max="<?= date('Y-m-d'); ?>" /><br><br>
                    </div>
                    
                    <label for="role">Rôle :</label>
                    <select id="role" name="role">
                        <option value="acteur">Acteur</option>
                        <option value="realisateur">Réalisateur</option>
                        <option value="les deux">Les deux</option>
                    </select>
                    <br><br>
                    <input type='submit' name='submit'>
                </form>
            </td>
        </tr>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th>Les acteurs</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Récupérer et afficher tous les acteurs
        foreach ($requete->fetchAll() as $acteur) { ?>
            <tr>
                <td> <?= $acteur["nom"] ?> <?= $acteur["prenom"] ?></td>
                <td>
                    <form action="index.php?action=editActeur" method="post" style="display:inline;">
                        <input type="hidden" name="id_acteur" value="<?= $acteur['id_acteur'] ?>">
                        <input type="text" name="new_nom" required="required" placeholder="Nouveau nom">
                        <input type="text" name="new_prenom" required="required" placeholder="Nouveau prénom">
                        <input type="text" name="new_sexe" required="required" placeholder="Nouveau sexe">
                        <input type="date" name="new_dateNaissance" required="required" placeholder="Nouvelle date de naissance" max="<?= date('Y-m-d'); ?>">
                        <select name="new_role">
                            <option value="acteur">Acteur</option>
                            <option value="realisateur">Réalisateur</option>
                            <option value="les deux">Les deux</option>
                        </select>
                        <br>
                        <input type="submit" value="Modifier">
                    </form>

                    
                    <?php echo "<a href='index.php?action=deleteActeur&id".$acteur['id_personne']."'>supprimer</a>" ?>

                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<br>
<button><a href="index.php?action=listActeurs">Retour</a></button>



<?php
$titre = "Administration Acteur";
$contenu = ob_get_clean();
require "template.php";
?>
