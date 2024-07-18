<?php ob_start(); ?>


<h2>Ajouter au casting</h2>

<!-- Formulaire pour ajouter au casting -->
<form action="index.php?action=addCasting" method="post">
    <label for="id_acteur">Acteur :</label>
    <select id="id_acteur" name="id_acteur" required>
        <?php foreach ($acteurs as $acteur): ?>
            <option value="<?= $acteur['id_acteur'] ?>"><?= htmlspecialchars($acteur['nom_complet']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="id_film">Film :</label>
    <select id="id_film" name="id_film" required>
        <?php foreach ($films as $film): ?>
            <option value="<?= $film['id_film'] ?>"><?= htmlspecialchars($film['titre']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="id_role">Rôle :</label>
    <select id="id_role" name="id_role" required>
        <?php foreach ($roles as $role): ?>
            <option value="<?= $role['id_role'] ?>"><?= htmlspecialchars($role['description']) ?></option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Ajouter au casting">
</form>

<!-- Bouton pour retourner à la liste des films -->
<button><a href="index.php?action=listFilms">Retour</a></button>

<?php
// Définition du titre de la page et du contenu, et inclusion du template
$titre = "Administration Casting";
$contenu = ob_get_clean();
require "template.php";
?>