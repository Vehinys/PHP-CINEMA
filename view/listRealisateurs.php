<?php ob_start(); ?>


<?php

$titre = "Film releases - Realisateurs";
$contenu = ob_get_clean();

require "template.php";