<?php ob_start(); ?>
<table>
    <thead>
        <tr>
            <th>Ajouter un acteur :</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <form action="index.php?action=addNouveauActeur" method="post">
                    <label for="nom">Nom :</label><br>
                    <input type="text" id="nom" name="nom" required><br><br>

                    <label for="prenom">Prénom :</label><br>
                    <input type="text" id="prenom" name="prenom" required><br><br>

                    <label for="sexe">Sexe :</label><br>
                    <select id="sexe" name="sexe" required>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                    </select><br><br>

                    <label>Date de naissance :</label><br>
                    <select name="jour" required>
                        <?php for ($i = 1; $i <= 31; $i++) { ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php } ?>
                    </select>
                    <select name="mois" required>
                        <?php
                        $mois = [
                            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                        ];
                        foreach ($mois as $numero => $nom) {
                            echo "<option value='$numero'>$nom</option>";
                        } ?>
                    </select>
                    <select name="annee" required>
                        <?php
                        $annee_actuelle = date('Y');
                        for ($annee = $annee_actuelle; $annee >= $annee_actuelle - 100; $annee--) {
                            echo "<option value='$annee'>$annee</option>";
                        } ?>
                    </select><br><br>

                    <button type="submit" name="submit">Ajouter</button>
                </form>
            </td>
        </tr>
    </tbody>
</table>

<table>
    <tbody>
        <?php foreach ($requete->fetchAll() as $acteur) { ?>
            <tr>
                <td><a href="index.php?action=detailActeur&id=<?= $acteur['id_acteur'] ?>"><?php echo $acteur["nom"] ?> <?= $acteur["prenom"] ?></a></td>
                <td>
                    <form action="index.php?action=deleteActeur&id=<?= $acteur['id_acteur'] ?>" method="post" style="display:inline;">
                        <input type="hidden" name="id_acteur" value="<?= $acteur['id_acteur'] ?>">
                        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet acteur ?');">Supprimer son role</button>
                    </form>

                    <form action="index.php?action=editActeur&id=<?= $acteur['id_acteur'] ?>" method="post" style="display:inline;">
                        <input type="hidden" name="id_acteur" value="<?= $acteur['id_acteur'] ?>">
                        <input type="text" name="new_nom" required placeholder="Nouveau nom">
                        <input type="text" name="new_prenom" required placeholder="Nouveau prénom">
                        <select name="new_sexe" required>
                            <option value="Homme">Homme</option>
                            <option value="Femme">Femme</option>
                        </select>
                        <input type="date" name="new_dateNaissance" required max="<?= date('Y-m-d'); ?>">
                        <button type="submit">Modifier</button>
                    </form>
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
