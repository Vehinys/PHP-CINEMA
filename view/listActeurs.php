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
<br><br>


<!-- Formulaire pour ajouter un nouvel acteur ou une nouvelle actrice à la base de donnée -->
<form action="index.php?action=addNouveauActeur" method="post">
    <!-- choisir le nom -->
    <div class="">

    <label for="nom">Nom :</label><br>
    <input required="required" type="text" id="nom" name="nom" /><br><br>

    </div>

    <div class="">

        <label for="prenom">Prenom :</label><br>
        <input required="required" type="text" id="prenom" name="prenom" /><br><br>

    </div>

    <div class="">

        <label for="sexe">Sexe</label><br>
        <input required="required" type="text" id="sexe" name="sexe" /><br><br>

    </div>

    <div class="">

        <label for="dateNaissance">Date de naissance :</label><br>
        <input required="required" type="date" id="dateNaissance" name="dateNaissance" min="1800-01-01" max="<?= date('Y-m-d');?>" /><br><br>

    </div>


  <legend>Rôle</legend>

    <div>
        <input type="radio" id="contactChoice1" name="contact" value="email" />
        <label for="contactChoice1">Acteur</label>
    </div>

    <div>
        <input type="radio" id="contactChoice2" name="contact" value="telephone" />
        <label for="contactChoice2">Réalisateur</label>
    </div>

    <div>
        <input type="radio" id="contactChoice3" name="contact" value="courrier" />
        <label for="contactChoice3">Les deux</label>
    </div><br>

    <input type="submit" name="submit">

</form>

<?php
$titre = "Liste des Acteurs";
$contenu = ob_get_clean();
require "template.php";
?>
