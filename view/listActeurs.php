<?php ob_start(); ?>


<?php

$titre = "Film releases - Acteurs";
$contenu = ob_get_clean();

require "template.php";