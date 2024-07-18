<?php ob_start(); ?>

<!-- Bouton pour retourner à la liste des films -->
<button><a href="index.php?action=listFilms"> Retour </a></button>

<?php

// Définition du titre de la page et du contenu, et inclusion du template
$titre = "Administration Casting";
$contenu = ob_get_clean();
require "template.php";

?>