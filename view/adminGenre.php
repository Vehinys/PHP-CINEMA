<?php ob_start(); ?>

<!-- Formulaire d'ajout d'un genre -->
<table>
    <tbody>
        <!-- Formulaire pour ajouter un nouveau genre -->
        <form action="index.php?action=addNouveauGenre" method="post">
            <label for="nomGenre">Ajout d'un genre : </label><br>
            <input required="required" type="text" id="addGenre" name="addGenre" /> <br><br>
            <input type='submit' name='submit'> 
        </form>
        
        <br><br>

        <?php
        // Boucle à travers les résultats de la requête et affiche chaque genre
        foreach ($requete->fetchAll() as $genre) {
        ?>

        <tr>
        
            <!-- Affiche le nom du genre -->
            <td><?= $genre["libelle"] ?></td>

            <td>
                <!-- Formulaire pour modifier le genre -->
                <form action="index.php?action=editGenre&id=<?= $genre['id_genre'] ?>" method="post" style="display:inline;">
                    <input type="text" name="new_libelle" required="required" placeholder="Nouveau nom">
                    <input type="submit" value="Modifier">
                </form>

                <!-- Lien pour supprimer le genre avec une confirmation -->

                <button type="submit"><a href="index.php?action=deleteGenre&id=<?= $genre['id_genre'] ?>" 
                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce genre de film ?');"> Supprimer</a></button>
                
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<br>

<!-- Bouton pour retourner à la liste des genres -->
<button><a href="index.php?action=listGenres"> retour </a></button>

<?php
// Définition du titre de la page et du contenu, et inclusion du template
$titre = "Administration Genre";
$contenu = ob_get_clean();
require "template.php";
?>
