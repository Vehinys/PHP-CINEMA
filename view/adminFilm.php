<?php ob_start(); ?>

<form action="index.php?action=addCasting" method="post">

        <!-- Choisir le film  -->
        <label for="film">Film :</label><br>
        <select name="film" id="film">    
                <?php
                    // alimenter la liste déroulante avec les films
                    foreach($films->fetchAll() as $film) {
                        echo '<option value="'. $film["id_film"].'">' . $film["titre"].'</option>';
                    }
                ?>
        </select><br>

         <!-- Choisir l'acteur ou l'actrice -->           
        <label for="acteur">Acteur ou actrice :</label><br>
        <select name="acteur" id="acteur">    
                <?php
                    // alimenter la liste déroulante avec les acteur.trices
                    foreach($acteurs->fetchAll() as $acteur) {
                        echo '<option value="'. $acteur["id_acteur"].'">' . $acteur['acteur'].'</option>';
                    }
                ?>
        </select><br>

        <!-- Choisir le rôle (présent en BDD)  -->
        <label for="role">Rôle :</label><br>
        <select name="role" id="role">    
                <?php
                    // alimenter la liste déroulante avec les rôles
                    foreach($roles->fetchAll() as $role) {
                        echo '<option value="'. $role["id_role"].'">' . $role['nomRole'].'</option>';
                    }
                ?>
        </select><br>

        
        <input type='submit' name='submit'>
</form>
<br>

<!-- Bouton pour retourner à la liste des films -->
<button><a href="index.php?action=listFilms">Retour</a></button>

<?php
$titre = "Administration Film";
$contenu = ob_get_clean();
require "template.php";
?>
