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

<!-- Formulaire pour ajouter un nouvel acteur ou une nouvelle actrice à la base de données -->
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

    <input type="submit" name="submit">
</form>

<?php
$titre = "Liste des Acteurs";
$contenu = ob_get_clean();
require "template.php";
?>
